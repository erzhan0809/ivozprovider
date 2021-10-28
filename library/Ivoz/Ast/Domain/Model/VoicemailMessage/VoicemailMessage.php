<?php
declare(strict_types = 1);

namespace Ivoz\Ast\Domain\Model\VoicemailMessage;

class VoicemailMessage extends VoicemailMessageAbstract implements VoicemailMessageInterface
{
    use VoicemailMessageTrait;

    public function getId()
    {
        return $this->id; 
    }

}

