<?php

namespace Ivoz\Ast\Domain\Model\Voicemail;

/**
 * Voicemail
 */
class Voicemail extends VoicemailAbstract implements VoicemailInterface
{
    const EMPTY_VOICEMAIL_ADDRESS = "nobody@domain.invalid";

    use VoicemailTrait;

    /**
     * @return array
     */
    public function getChangeSet(): array
    {
        return parent::getChangeSet();
    }

    /**
     * Get id
     * @codeCoverageIgnore
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
