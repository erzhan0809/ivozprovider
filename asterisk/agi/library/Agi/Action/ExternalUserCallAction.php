<?php

namespace Agi\Action;

/**
 * @class ExternalUserCallAction
 *
 * @brief Manage outgoing external calls generated by an user
 *
 */

class ExternalUserCallAction extends ExternalCallAction
{
    protected $_number;

    public function setDestination($number)
    {
        $this->_number = $number;
        return $this;
    }

    public function process()
    {
        // Local variables
        $user = $this->_caller;
        $number = $this->_number;

        // Get company from the caller
        $company = $user->getCompany();

        // Some feedback for asterisk cli
        $this->agi->notice("Processing External call from \e[0;32m%s [user%d]\e[0;93m to %s",
            $user->getFullName(), $user->getId(), $number);

        // Check if dialed number has company's outbound prefix
        if (!$this->checkCompanyOutboundPrefix($number)) {
            $this->agi->error("Destination number %s without [company%d] outbound prefix",
                            $number, $company->getId());
            $this->agi->decline();
            return;
        }

        // Convert to E.164 format
        $e164number = $user->preferredToE164($number);

        // Check the user has this call allowed in its ACL
        if (!$user->isAllowedToCall($e164number)) {
            $this->agi->error("User is not allowed to call %s", $e164number);
            $this->agi->decline();
            return;
        }

        // Check if outgoing call can be tarificated
        if (!$this->checkTarificable($e164number)) {
            $this->agi->error("Destination %s can not be billed.", $e164number);
            $this->agi->decline();
            return;
        }

        // Get Ougoing presentation
        $ddi = $user->getOutgoingDDI();

        // Update caller displayed number
        $this->updateOriginConnectedLine($e164number);
        // Check if DDI has recordings enabled
        $this->checkDDIRecording($ddi);
        // Check if DDI belong to platform
        $this->checkDDIBounced($e164number);

        // We need Outgoing DDI for external call presentation
        if (!$ddi) {
            $this->agi->error("User %s [friend%d] has not OutgoingDDI configured", $user->getName(), $user->getId());
            $this->agi->decline();
            return;
        }

        // Call the PSJIP endpoint
        $this->agi->setVariable("DIAL_DST", "PJSIP/" . $e164number . '@proxytrunks');
        $this->agi->setVariable("DIAL_OPTS", "");
        $this->agi->redirect('call-world', $e164number);
    }
}
