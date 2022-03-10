<?php

namespace Agi\Action;

use Agi\Agents\AgentInterface;
use Agi\Agents\UserAgent;
use Agi\Wrapper;
use Ivoz\Provider\Domain\Model\ResidentialDevice\ResidentialDeviceInterface;
use Ivoz\Provider\Domain\Model\User\UserInterface;

class VoicemailAction
{
    /**
     * @var Wrapper
     */
    protected $agi;

    /**
     * @var AgentInterface|null
     */
    protected $voicemail;

    /**
     * @var bool
     */
    protected $playBanner = false;

    /**
     * VoicemailAction constructor.
     *
     * @param Wrapper $agi
     */
    public function __construct(
        Wrapper $agi
    ) {
        $this->agi = $agi;
    }

    /**
     * @param bool $playBanner
     * @return $this
     */
    public function setPlayBanner(bool $playBanner)
    {
        $this->playBanner = $playBanner;
        return $this;
    }

    /**
     * @param AgentInterface|null $voicemail
     * @return $this
     */
    public function setVoicemai(AgentInterface $voicemail = null)
    {
        $this->voicemail = $voicemail;
        return $this;
    }

    public function process()
    {
        $voicemail = $this->voicemail;
        if (is_null($voicemail)) {
            $this->agi->error("Voicemail is not properly defined. Check configuration.");
            $this->agi->hangup();
            return;
        }

        // Some feedback for asterisk cli
        $this->agi->notice("Processing Voicemail of %s", $voicemail);

        if ($voicemail->getEnabledt()) {
            // Run the voicemail
            $vmopts = "";
            if ($this->playBanner) {
                if ($voicemail->getLocution()) {
                    $this->agi->verbose("Playing custom Voicemail Locution.");
                    $this->agi->playbackLocution($voicemail->getLocution());
                    $vmopts .= "s";     // Skip welcome message
                } else {
                    $vmopts .= "u";
                }
            } else {
                $vmopts .= "s";         // Skip welcome message
            }


            $this->agi->voicemail($voicemail->getVoicemailName(), $vmopts);
        } else {
            $this->agi->error("%s is disabled.", $voicemail);
            $this->agi->busy();
        }
    }
}
