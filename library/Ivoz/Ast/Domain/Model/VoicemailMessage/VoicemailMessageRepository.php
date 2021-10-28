<?php

namespace Ivoz\Ast\Domain\Model\VoicemailMessage;

interface VoicemailMessageRepository
{
    /**
     * @param $mailbox
     * @param $context
     * @param $num
     * @return VoicemailMessageInterface
     */
    public function findIndexMessageByMailboxContextNum($mailbox, $context, $msgnum);
}
