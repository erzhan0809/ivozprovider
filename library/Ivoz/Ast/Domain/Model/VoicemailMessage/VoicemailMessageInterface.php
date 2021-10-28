<?php

namespace Ivoz\Ast\Domain\Model\VoicemailMessage;

use Ivoz\Core\Domain\Model\EntityInterface;
use Ivoz\Provider\Domain\Model\User\UserInterface;
use Ivoz\Provider\Domain\Model\ResidentialDevice\ResidentialDeviceInterface;

/**
* VoicemailMessageInterface
*/
interface VoicemailMessageInterface extends EntityInterface
{

    public function getDir(): string;

    public function getMsgnum(): ?int;

    public function getContext(): ?string;

    public function getMacrocontext(): ?string;

    public function getCallerid(): ?string;

    public function getOrigtime(): ?int;

    public function getDuration(): ?int;

    public function getRecording(): ?string;

    public function getFlag(): ?string;

    public function getCategory(): ?string;

    public function getMailboxuser(): ?string;

    public function getMailboxcontext(): ?string;

    public function getMsgId(): ?string;

    public function getRecordingFile(): RecordingFile;

    public function getUser(): ?UserInterface;

    public function getResidentialDevice(): ?ResidentialDeviceInterface;

    public function isInitialized(): bool;
}
