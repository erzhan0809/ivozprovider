<?php

declare(strict_types=1);

namespace Ivoz\Ast\Domain\Model\VoicemailMessage;

use Assert\Assertion;
use Ivoz\Core\Application\DataTransferObjectInterface;
use Ivoz\Core\Domain\Model\ChangelogTrait;
use Ivoz\Core\Domain\Model\EntityInterface;
use Ivoz\Core\Application\ForeignKeyTransformerInterface;
use Ivoz\Ast\Domain\Model\VoicemailMessage\RecordingFile;
use Ivoz\Provider\Domain\Model\User\UserInterface;
use Ivoz\Provider\Domain\Model\ResidentialDevice\ResidentialDeviceInterface;
use Ivoz\Provider\Domain\Model\User\User;
use Ivoz\Provider\Domain\Model\ResidentialDevice\ResidentialDevice;

/**
* VoicemailMessageAbstract
* @codeCoverageIgnore
*/
abstract class VoicemailMessageAbstract
{
    use ChangelogTrait;

    protected $dir;

    protected $msgnum;

    protected $context;

    protected $macrocontext;

    protected $callerid;

    protected $origtime;

    protected $duration;

    protected $recording;

    protected $flag;

    protected $category;

    protected $mailboxuser;

    protected $mailboxcontext;

    /**
     * column: msg_id
     */
    protected $msgId;

    /**
     * @var RecordingFile | null
     */
    protected $recordingFile;

    /**
     * @var UserInterface | null
     */
    protected $user;

    /**
     * @var ResidentialDeviceInterface | null
     */
    protected $residentialDevice;

    /**
     * Constructor
     */
    protected function __construct(
        string $dir,
        RecordingFile $recordingFile
    ) {
        $this->setDir($dir);
        $this->setRecordingFile($recordingFile);
    }

    abstract public function getId();

    public function __toString()
    {
        return sprintf(
            "%s#%s",
            "VoicemailMessage",
            $this->getId()
        );
    }

    /**
     * @return void
     * @throws \Exception
     */
    protected function sanitizeValues()
    {
    }

    /**
     * @param mixed $id
     */
    public static function createDto($id = null): VoicemailMessageDto
    {
        return new VoicemailMessageDto($id);
    }

    /**
     * @internal use EntityTools instead
     * @param VoicemailMessageInterface|null $entity
     * @param int $depth
     * @return VoicemailMessageDto|null
     */
    public static function entityToDto(EntityInterface $entity = null, $depth = 0)
    {
        if (!$entity) {
            return null;
        }

        Assertion::isInstanceOf($entity, VoicemailMessageInterface::class);

        if ($depth < 1) {
            return static::createDto($entity->getId());
        }

        if ($entity instanceof \Doctrine\ORM\Proxy\Proxy && !$entity->__isInitialized()) {
            return static::createDto($entity->getId());
        }

        /** @var VoicemailMessageDto $dto */
        $dto = $entity->toDto($depth - 1);

        return $dto;
    }

    /**
     * Factory method
     * @internal use EntityTools instead
     * @param VoicemailMessageDto $dto
     * @return self
     */
    public static function fromDto(
        DataTransferObjectInterface $dto,
        ForeignKeyTransformerInterface $fkTransformer
    ) {
        Assertion::isInstanceOf($dto, VoicemailMessageDto::class);

        $recordingFile = new RecordingFile(
            $dto->getRecordingFileFileSize(),
            $dto->getRecordingFileMimeType(),
            $dto->getRecordingFileBaseName()
        );

        $self = new static(
            $dto->getDir(),
            $recordingFile
        );

        $self
            ->setMsgnum($dto->getMsgnum())
            ->setContext($dto->getContext())
            ->setMacrocontext($dto->getMacrocontext())
            ->setCallerid($dto->getCallerid())
            ->setOrigtime($dto->getOrigtime())
            ->setDuration($dto->getDuration())
            ->setRecording($dto->getRecording())
            ->setFlag($dto->getFlag())
            ->setCategory($dto->getCategory())
            ->setMailboxuser($dto->getMailboxuser())
            ->setMailboxcontext($dto->getMailboxcontext())
            ->setMsgId($dto->getMsgId())
            ->setUser($fkTransformer->transform($dto->getUser()))
            ->setResidentialDevice($fkTransformer->transform($dto->getResidentialDevice()));

        $self->initChangelog();

        return $self;
    }

    /**
     * @internal use EntityTools instead
     * @param VoicemailMessageDto $dto
     * @return self
     */
    public function updateFromDto(
        DataTransferObjectInterface $dto,
        ForeignKeyTransformerInterface $fkTransformer
    ) {
        Assertion::isInstanceOf($dto, VoicemailMessageDto::class);

        $recordingFile = new RecordingFile(
            $dto->getRecordingFileFileSize(),
            $dto->getRecordingFileMimeType(),
            $dto->getRecordingFileBaseName()
        );

        $this
            ->setDir($dto->getDir())
            ->setMsgnum($dto->getMsgnum())
            ->setContext($dto->getContext())
            ->setMacrocontext($dto->getMacrocontext())
            ->setCallerid($dto->getCallerid())
            ->setOrigtime($dto->getOrigtime())
            ->setDuration($dto->getDuration())
            ->setRecording($dto->getRecording())
            ->setFlag($dto->getFlag())
            ->setCategory($dto->getCategory())
            ->setMailboxuser($dto->getMailboxuser())
            ->setMailboxcontext($dto->getMailboxcontext())
            ->setMsgId($dto->getMsgId())
            ->setRecordingFile($recordingFile)
            ->setUser($fkTransformer->transform($dto->getUser()))
            ->setResidentialDevice($fkTransformer->transform($dto->getResidentialDevice()));

        return $this;
    }

    /**
     * @internal use EntityTools instead
     * @param int $depth
     */
    public function toDto($depth = 0): VoicemailMessageDto
    {
        return self::createDto()
            ->setDir(self::getDir())
            ->setMsgnum(self::getMsgnum())
            ->setContext(self::getContext())
            ->setMacrocontext(self::getMacrocontext())
            ->setCallerid(self::getCallerid())
            ->setOrigtime(self::getOrigtime())
            ->setDuration(self::getDuration())
            ->setRecording(self::getRecording())
            ->setFlag(self::getFlag())
            ->setCategory(self::getCategory())
            ->setMailboxuser(self::getMailboxuser())
            ->setMailboxcontext(self::getMailboxcontext())
            ->setMsgId(self::getMsgId())
            ->setRecordingFileFileSize(self::getRecordingFile()->getFileSize())
            ->setRecordingFileMimeType(self::getRecordingFile()->getMimeType())
            ->setRecordingFileBaseName(self::getRecordingFile()->getBaseName())
            ->setUser(User::entityToDto(self::getUser(), $depth))
            ->setResidentialDevice(ResidentialDevice::entityToDto(self::getResidentialDevice(), $depth));
    }

    /**
     * @return array
     */
    protected function __toArray()
    {
        return [
            'dir' => self::getDir(),
            'msgnum' => self::getMsgnum(),
            'context' => self::getContext(),
            'macrocontext' => self::getMacrocontext(),
            'callerid' => self::getCallerid(),
            'origtime' => self::getOrigtime(),
            'duration' => self::getDuration(),
            'recording' => self::getRecording(),
            'flag' => self::getFlag(),
            'category' => self::getCategory(),
            'mailboxuser' => self::getMailboxuser(),
            'mailboxcontext' => self::getMailboxcontext(),
            'msg_id' => self::getMsgId(),
            'recordingFileFileSize' => self::getRecordingFile()->getFileSize(),
            'recordingFileMimeType' => self::getRecordingFile()->getMimeType(),
            'recordingFileBaseName' => self::getRecordingFile()->getBaseName(),
            'userId' => self::getUser() ? self::getUser()->getId() : null,
            'residentialDeviceId' => self::getResidentialDevice() ? self::getResidentialDevice()->getId() : null
        ];
    }

    protected function setDir(string $dir): static
    {
        Assertion::maxLength($dir, 255, 'dir value "%s" is too long, it should have no more than %d characters, but has %d characters.');

        $this->dir = $dir;

        return $this;
    }

    public function getDir(): string
    {
        return $this->dir;
    }

    protected function setMsgnum(?int $msgnum = null): static
    {
        $this->msgnum = $msgnum;

        return $this;
    }

    public function getMsgnum(): ?int
    {
        return $this->msgnum;
    }

    protected function setContext(?string $context = null): static
    {
        if (!is_null($context)) {
            Assertion::maxLength($context, 80, 'context value "%s" is too long, it should have no more than %d characters, but has %d characters.');
        }

        $this->context = $context;

        return $this;
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    protected function setMacrocontext(?string $macrocontext = null): static
    {
        if (!is_null($macrocontext)) {
            Assertion::maxLength($macrocontext, 80, 'macrocontext value "%s" is too long, it should have no more than %d characters, but has %d characters.');
        }

        $this->macrocontext = $macrocontext;

        return $this;
    }

    public function getMacrocontext(): ?string
    {
        return $this->macrocontext;
    }

    protected function setCallerid(?string $callerid = null): static
    {
        if (!is_null($callerid)) {
            Assertion::maxLength($callerid, 80, 'callerid value "%s" is too long, it should have no more than %d characters, but has %d characters.');
        }

        $this->callerid = $callerid;

        return $this;
    }

    public function getCallerid(): ?string
    {
        return $this->callerid;
    }

    protected function setOrigtime(?int $origtime = null): static
    {
        $this->origtime = $origtime;

        return $this;
    }

    public function getOrigtime(): ?int
    {
        return $this->origtime;
    }

    protected function setDuration(?int $duration = null): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    protected function setRecording(?string $recording = null): static
    {
        $this->recording = $recording;

        return $this;
    }

    public function getRecording(): ?string
    {
        return $this->recording;
    }

    protected function setFlag(?string $flag = null): static
    {
        if (!is_null($flag)) {
            Assertion::maxLength($flag, 30, 'flag value "%s" is too long, it should have no more than %d characters, but has %d characters.');
        }

        $this->flag = $flag;

        return $this;
    }

    public function getFlag(): ?string
    {
        return $this->flag;
    }

    protected function setCategory(?string $category = null): static
    {
        if (!is_null($category)) {
            Assertion::maxLength($category, 30, 'category value "%s" is too long, it should have no more than %d characters, but has %d characters.');
        }

        $this->category = $category;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    protected function setMailboxuser(?string $mailboxuser = null): static
    {
        if (!is_null($mailboxuser)) {
            Assertion::maxLength($mailboxuser, 30, 'mailboxuser value "%s" is too long, it should have no more than %d characters, but has %d characters.');
        }

        $this->mailboxuser = $mailboxuser;

        return $this;
    }

    public function getMailboxuser(): ?string
    {
        return $this->mailboxuser;
    }

    protected function setMailboxcontext(?string $mailboxcontext = null): static
    {
        if (!is_null($mailboxcontext)) {
            Assertion::maxLength($mailboxcontext, 30, 'mailboxcontext value "%s" is too long, it should have no more than %d characters, but has %d characters.');
        }

        $this->mailboxcontext = $mailboxcontext;

        return $this;
    }

    public function getMailboxcontext(): ?string
    {
        return $this->mailboxcontext;
    }

    protected function setMsgId(?string $msgId = null): static
    {
        if (!is_null($msgId)) {
            Assertion::maxLength($msgId, 40, 'msgId value "%s" is too long, it should have no more than %d characters, but has %d characters.');
        }

        $this->msgId = $msgId;

        return $this;
    }

    public function getMsgId(): ?string
    {
        return $this->msgId;
    }

    public function getRecordingFile(): RecordingFile
    {
        return $this->recordingFile;
    }

    protected function setRecordingFile(RecordingFile $recordingFile): static
    {
        $isEqual = $this->recordingFile && $this->recordingFile->equals($recordingFile);
        if ($isEqual) {
            return $this;
        }

        $this->recordingFile = $recordingFile;
        return $this;
    }

    protected function setUser(?UserInterface $user = null): static
    {
        $this->user = $user;

        return $this;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    protected function setResidentialDevice(?ResidentialDeviceInterface $residentialDevice = null): static
    {
        $this->residentialDevice = $residentialDevice;

        return $this;
    }

    public function getResidentialDevice(): ?ResidentialDeviceInterface
    {
        return $this->residentialDevice;
    }
}
