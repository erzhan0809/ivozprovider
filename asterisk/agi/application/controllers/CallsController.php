<?php
require_once("BaseController.php");
use IvozProvider\Mapper\Sql as Mapper;
use Agi\Action\DDIAction;
use Agi\Action\ExtensionAction;
use Agi\Action\ExternalCallAction;
use Agi\Action\UserCallAction;
use Agi\Action\HuntGroupAction;
use Agi\Action\IVRAction;
use Agi\Action\ServiceAction;
use Agi\Action\FaxCallAction;

/**
 * @brief Controller for Incoming and Outgoing calls
 *
 * This controllers is invoked from different contexts of dialplan and routes
 * the call based on configuretion
 *
 * Following actions are defined in this controller
 * - incoming: Handle incoming calls from external numbers
 * - outgoing: Handle outgoing calls from registered users
 * - forwards: Handle channel redirections and transfers
 * - dialstatus: Handle post-call user call forwards
 *
 * @package AGI
 * @subpackage CallsController
 * @author Gaizka Elexpe <gaizka@irontec.com>
 * @author Ivan Alonso [kaian] <kaian@irontec.com>
 */
class CallsController extends BaseController
{
    /**
     * @brief Incomming from from external numbers
     */
    public function incomingAction ()
    {
        // Get Dialed number
        $exten = $this->agi->getExtension();

        // Check if incoming DDI is for us
        $DDIMapper = new Mapper\DDIs();
        $ddi = $DDIMapper->findOneByField("DDI", $exten);
        if (empty($ddi)) {
            $this->agi->error("DDI %s not found in database.", $exten);
            return;
        }

        // Mark this call as external
        $this->agi->setCallType("external");

        // Set Outgoing Channels X-CID header variable
        $callid = $this->agi->getSIPHeader("Call-Id");
        if (!empty($callid)) {
            $this->agi->setVariable("__CALL_ID", $callid);
        }

        // Get company MusicClass: company, Generic or default
        $company = $ddi->getCompany();
        $this->agi->setVariable("__COMPANYID", $company->getId());
        $this->agi->setVariable("CHANNEL(musicclass)", $company->getMusicClass());

        // Process this DDI
        $ddiAction = new DDIAction($this);
        $ddiAction
            ->setDDI($ddi)
            ->process();
    }

    /**
     * @brief Outgoing calls from terminals to Extensions, Services o World
     */
    public function outgoingAction ()
    {
        /**
         * Determine who is placing this call:
         * - SIPTRANSFER: set by asterisk on blind transfers
         * - Diversion:   set by asterisk on 302 Moved SIP Message
         * - CallerID:    set by asterisk reading From: SIP Header
         */
        if ($this->agi->getVariable("SIPTRANSFER")) {
            $transfererURI = $this->agi->getVariable("SIPREFERREDBYHDR");
            $callerid = $this->agi->extractURI($transfererURI, "num");
        } else if ($forwader = $this->agi->getRedirecting('from-num')) {
            $callerid = $forwader;
        } else {
            $callerid = $this->agi->getPeer();
        }

        if (! $callerid) {
            $this->agi->error("Call without valid callerid. Dropping.");
            return;
        }

        // Get caller peer
        $terminalMapper = new Mapper\Terminals();
        $terminal = $terminalMapper->findOneByField("name", $callerid);

        if (empty($terminal)) {
            $this->agi->error("Terminal %s not found.", $callerid);
            return;
        }

        // Get caller user
        $user = $terminal->getUser();
        if (empty($user)) {
            $this->agi->error("Terminal %s has no user.", $terminal->getId());
            return;
        }

        // Get caller extension
        $extension = $user->getExtension();
        if (empty($extension)) {
            $this->agi->error("User %s has no extension.", $user->getId());
            return;
        }

        // Set Company/Brand/Generic Music class
        $company = $user->getCompany();
        $this->agi->setVariable("__COMPANYID", $company->getId());
        $this->agi->setVariable("CHANNEL(musicclass)", $company->getMusicClass());

        // Check User's permission to does this call
        $exten = $this->agi->getExtension();

        // Mark this call as generated from user
        $this->agi->setCallType("internal");

        // Set Outgoing Channels X-CID header variable
        $callid = $this->agi->getSIPHeader("Call-Id");
        if (!empty($callid)) {
            $this->agi->setVariable("__CALL_ID", $callid);
        }

        // Set user language
        $this->agi->setVariable("CHANNEL(language)", $user->getLanguageCode());

        // Some output
        $this->agi->verbose("Processing ougoing call from %s [%s] to number %s",
                        $user->getFullName(), $user->getId(), $exten);

        // Check if this extension starts with '*' code
        if (strpos($exten, '*') === 0) {
            if (($service = $company->getService($exten))) {
                $this->agi->verbose("Number %s belongs to a company service.", $exten);

                // Handle service code
                $serviceAction = new ServiceAction($this);
                $serviceAction
                    ->setUser($user)
                    ->setService($service)
                    ->process();

            } else {
                // Decline this call if not matching service is found
                $this->agi->verbose("Invalid Service code %s for comany %d", $exten, $company->getId());
                $this->agi->hangup();
            }

        // Check if this is an extension call
        } elseif (($dstExtension = $company->getExtension($exten))) {
            $this->agi->verbose("Number %s belongs to a company extension [%d].", $exten, $dstExtension->getId());

            // Update who is redirecting this call
            if ($this->agi->getRedirecting('from-num')) {
                $this->agi->setRedirecting('from-num', $extension->getNumber());
            }

            // Handle extension
            $extensionAction = new ExtensionAction($this);
            $extensionAction
                ->setExtension($dstExtension)
                ->process();

        // This number don't belong to IvozProvider
        } else {
            $this->agi->verbose("Number %s is handled as external DDI.", $exten);

            // Update who is calling
            if (isset($transfererURI) && !empty($transfererURI)) {
                // Nothing to do here?
            } else if (isset($forwader) && !empty($forwader)) {
                $this->agi->setRedirecting('from-name,i', $user->getFullName());
                $this->agi->setRedirecting('from-num,i', $user->getOutgoingDDINumber());
                $this->agi->setRedirecting('from-tag,i',   $user->getExtensionNumber());
            }

            // Otherwise, handle this call as external
            $externalCallAction = new ExternalCallAction($this);
            $externalCallAction
                ->setUser($user)
                ->setDestination($exten)
                ->process();
        }
    }

    /**
     * @brief Process User after call status
     */
    public function userstatusAction ()
    {
        // FIXME Process Dialed user dialstatus FIXME
        $iface = $this->agi->getVariable("DIAL_DST");
        $iface = preg_replace('/^\w+\//', '', $iface);
        $terminalMapper = new Mapper\Terminals();
        $terminal = $terminalMapper->findOneByField("name", $iface);
        if (empty($terminal)) {
            $this->agi->error("Terminal %s not found in database. (BUG?)", $iface);
            return;
        }

        // Get user from the terminal.
        $user = $terminal->getUser();
        if (empty($user)) {
            $this->agi->error("Terminal %s has no user (BUG?).", $iface);
            return;
        }

        // ProcessDialStatus
        $userAction = new UserCallAction($this);
        $userAction
            ->setUser($user)
            ->processDialStatus();

    }

    /**
     * @brief Process IVR after call status
     */
    public function ivrstatusAction ()
    {
        $dialStatus = $this->agi->getVariable("DIALSTATUS");

        // Noone picked up
        if ($dialStatus == "NOANSWER") {
            $ivrId = $this->agi->getVariable("IVRID");

            // Get IVRCommon..
            $ivrCommonMapper = new Mapper\IVRCommon();
            $ivr = $ivrCommonMapper->find($ivrId);

            // Or IVRcustom...
            if (empty($ivr)) {
                $ivrCustomMapper = new Mapper\IVRCustom();
                $ivr = $ivrCustomMapper->find($ivrId);
            }

            // Process NoAnswer handler
            $ivrAction = new IVRAction($this);
            $ivrAction
                ->setIvr($ivr)
                ->processTimeout();
        }
    }

    /**
     * @brief Process fax after call status
     */
    public function faxstatusAction ()
    {
        // FIXME Process Dialed fax dialstatus FIXME
        $faxid = $this->agi->getVariable("FAXIN_ID");
        $faxInOutMapper = new Mapper\FaxesInOut();
        $faxInOut = $faxInOutMapper->find($faxid);
        if (empty($faxInOut)) {
            $this->agi->error("Fax %s not found in database. (BUG?)", $faxid);
            return;
        }

        // ProcessDialStatus
        $faxAction = new FaxCallAction($this);
        $faxAction
            ->setFax($faxInOut->getFax())
            ->setFaxInOut($faxInOut)
            ->processDialStatus();

    }

    /**
     * @brief Process fax after call status
     */
    public function faxoutAction ()
    {
        $faxOutId = $this->agi->getVariable("FAXOUT_ID");
        if (! $faxOutId) {
            $this->agi->error("No FAX_ID found in this channel.");
            $this->agi->hangup();
            return;
        }


        // Get Fax file filename
        $faxInOutMapper = new Mapper\FaxesInOut();
        $faxOut = $faxInOutMapper->find($faxOutId);
        if (! $faxOut) {
            $this->agi->error("There is no Fax with id $faxOutId");
            $this->agi->hangup();
            return;
        }

        $this->agi->setVariable("__COMPANYID", $faxOut->getFax()->getCompanyId());

        // ProcessDialStatus
        $faxAction = new FaxCallAction($this);
        $faxAction
        ->setFax($faxOut->getFax())
        ->setFaxInOut($faxOut)
        ->sendFax();

    }

    /**
     * @brief Call a user from a Huntgroup
     */
    public  function hgcalluserAction()
    {
        // Get running Huntgroup
        $huntgroupId = $this->agi->getVariable("HG_ID");
        $huntgroupMapper = new Mapper\HuntGroups();
        $huntgroup = $huntgroupMapper->find($huntgroupId);

        // Continue processing
        $hungroupAction = new HuntGroupAction($this);
        $hungroupAction
            ->setHuntGroup($huntgroup)
            ->call();
    }

    /**
     * @brief Process Huntgroup after call status
     */
    public function hgstatusAction ()
    {
        // Get running Huntgroup
        $huntgroupId = $this->agi->getVariable("HG_ID");
        $huntgroupMapper = new Mapper\HuntGroups();
        $huntgroup = $huntgroupMapper->find($huntgroupId);

        // Continue processing
        $hungroupAction = new HuntGroupAction($this);
        $hungroupAction
            ->setHuntGroup($huntgroup)
            ->processHuntgroupStatus();
    }

    /**
     * @brief Add SIP Headers for proxies
     */
    public function addheadersAction()
    {
        $companyId = $this->agi->getVariable("COMPANYID");
        $companyMapper = new Mapper\Companies();
        $company = $companyMapper->find($companyId);

        // Add headers for Friendly Kamailio  Proxy;-))
        $this->agi->setSIPHeader("X-Call-Id",            $this->agi->getVariable("CALL_ID"));
        $this->agi->setSIPHeader("X-Info-CompanyId",     $company->getId());
        $this->agi->setSIPHeader("X-Info-CompanyName",   $company->getName());
        $this->agi->setSIPHeader("X-Info-MediaRelaySet", $company->getMediaRelaySetsId());

        // Set Callee information.
        // Use channelname to get this information because in case of ringall hungroup
        // this action will be invoked once per generated channel
        $peer = $this->agi->getPeer();
        $terminal = $company->getTerminal($peer);
        if (!empty($terminal)) {
            $extension = $terminal->getUser()->getExtension();
            $this->agi->setSIPHeader("X-Info-Callee",    $extension->getNumber());
        } else {
            $this->agi->setSIPHeader("X-Info-MaxCalls",  $company->getExternalMaxCalls());
        }

        // Set special headers for Fax outgoing calls
        if ($this->agi->getVariable("FAXOUT_ID")) {
            $this->agi->setSIPHeader("X-Info-Special", "fax");
        }

        // Set Special header for Forwarding
        if ($this->agi->getRedirecting('from-tag')) {
            $this->agi->setSIPHeader("X-Info-ForwardExt", $this->agi->getRedirecting('from-tag'));
        }
    }
}
