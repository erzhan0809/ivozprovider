<?php

namespace Ivoz\Ast\Domain\Model\VoicemailMessage;

use Ivoz\Core\Application\DataTransferObjectInterface;
use Ivoz\Core\Application\Model\DtoNormalizer;
use Ivoz\Provider\Domain\Model\User\UserDto;
use Ivoz\Provider\Domain\Model\ResidentialDevice\ResidentialDeviceDto;

/**
* VoicemailMessageDtoAbstract
* @codeCoverageIgnore
*/
abstract class VoicemailMessageDtoAbstract implements DataTransferObjectInterface
{
    use DtoNormalizer;

    /**
     * @var string
     */
    private $dir;

    /**
     * @var int|null
     */
    private $msgnum;

    /**
     * @var string|null
     */
    private $context;

    /**
     * @var string|null
     */
    private $macrocontext;

    /**
     * @var string|null
     */
    private $callerid;

    /**
     * @var int|null
     */
    private $origtime;

    /**
     * @var int|null
     */
    private $duration;

    /**
     * @var string|null
     */
    private $recording;

    /**
     * @var string|null
     */
    private $flag;

    /**
     * @var string|null
     */
    private $category;

    /**
     * @var string|null
     */
    private $mailboxuser;

    /**
     * @var string|null
     */
    private $mailboxcontext;

    /**
     * @var string|null
     */
    private $msgId;

    /**
     * @var int
     */
    private $id;

    /**
     * @var int|null
     */
    private $recordingFileFileSize;

    /**
     * @var string|null
     */
    private $recordingFileMimeType;

    /**
     * @var string|null
     */
    private $recordingFileBaseName;

    /**
     * @var UserDto | null
     */
    private $user;

    /**
     * @var ResidentialDeviceDto | null
     */
    private $residentialDevice;

    public function __construct($id = null)
    {
        $this->setId($id);
    }

    /**
    * @inheritdoc
    */
    public static function getPropertyMap(string $context = '', string $role = null)
    {
        if ($context === self::CONTEXT_COLLECTION) {
            return ['id' => 'id'];
        }

        return [
            'dir' => 'dir',
            'msgnum' => 'msgnum',
            'context' => 'context',
            'macrocontext' => 'macrocontext',
            'callerid' => 'callerid',
            'origtime' => 'origtime',
            'duration' => 'duration',
            'recording' => 'recording',
            'flag' => 'flag',
            'category' => 'category',
            'mailboxuser' => 'mailboxuser',
            'mailboxcontext' => 'mailboxcontext',
            'msgId' => 'msgId',
            'id' => 'id',
            'recordingFile' => [
                'fileSize',
                'mimeType',
                'baseName',
            ],
            'userId' => 'user',
            'residentialDeviceId' => 'residentialDevice'
        ];
    }

    /**
    * @return array
    */
    public function toArray($hideSensitiveData = false)
    {
        $response = [
            'dir' => $this->getDir(),
            'msgnum' => $this->getMsgnum(),
            'context' => $this->getContext(),
            'macrocontext' => $this->getMacrocontext(),
            'callerid' => $this->getCallerid(),
            'origtime' => $this->getOrigtime(),
            'duration' => $this->getDuration(),
            'recording' => $this->getRecording(),
            'flag' => $this->getFlag(),
            'category' => $this->getCategory(),
            'mailboxuser' => $this->getMailboxuser(),
            'mailboxcontext' => $this->getMailboxcontext(),
            'msgId' => $this->getMsgId(),
            'id' => $this->getId(),
            'recordingFile' => [
                'fileSize' => $this->getRecordingFileFileSize(),
                'mimeType' => $this->getRecordingFileMimeType(),
                'baseName' => $this->getRecordingFileBaseName(),
            ],
            'user' => $this->getUser(),
            'residentialDevice' => $this->getResidentialDevice()
        ];

        if (!$hideSensitiveData) {
            return $response;
        }

        foreach ($this->sensitiveFields as $sensitiveField) {
            if (!array_key_exists($sensitiveField, $response)) {
                throw new \Exception($sensitiveField . ' field was not found');
            }
            $response[$sensitiveField] = '*****';
        }

        return $response;
    }

    public function setDir(?string $dir): static
    {
        $this->dir = $dir;

        return $this;
    }

    public function getDir(): ?string
    {
        return $this->dir;
    }

    public function setMsgnum(?int $msgnum): static
    {
        $this->msgnum = $msgnum;

        return $this;
    }

    public function getMsgnum(): ?int
    {
        return $this->msgnum;
    }

    public function setContext(?string $context): static
    {
        $this->context = $context;

        return $this;
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function setMacrocontext(?string $macrocontext): static
    {
        $this->macrocontext = $macrocontext;

        return $this;
    }

    public function getMacrocontext(): ?string
    {
        return $this->macrocontext;
    }

    public function setCallerid(?string $callerid): static
    {
        $this->callerid = $callerid;

        return $this;
    }

    public function getCallerid(): ?string
    {
        return $this->callerid;
    }

    public function setOrigtime(?int $origtime): static
    {
        $this->origtime = $origtime;

        return $this;
    }

    public function getOrigtime(): ?int
    {
        return $this->origtime;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setRecording(?string $recording): static
    {
        $this->recording = $recording;

        return $this;
    }

    public function getRecording(): ?string
    {
        return $this->recording;
    }

    public function setFlag(?string $flag): static
    {
        $this->flag = $flag;

        return $this;
    }

    public function getFlag(): ?string
    {
        return $this->flag;
    }

    public function setCategory(?string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setMailboxuser(?string $mailboxuser): static
    {
        $this->mailboxuser = $mailboxuser;

        return $this;
    }

    public function getMailboxuser(): ?string
    {
        return $this->mailboxuser;
    }

    public function setMailboxcontext(?string $mailboxcontext): static
    {
        $this->mailboxcontext = $mailboxcontext;

        return $this;
    }

    public function getMailboxcontext(): ?string
    {
        return $this->mailboxcontext;
    }

    public function setMsgId(?string $msgId): static
    {
        $this->msgId = $msgId;

        return $this;
    }

    public function getMsgId(): ?string
    {
        return $this->msgId;
    }

    public function setId($id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setRecordingFileFileSize(?int $recordingFileFileSize): static
    {
        $this->recordingFileFileSize = $recordingFileFileSize;

        return $this;
    }

    public function getRecordingFileFileSize(): ?int
    {
        return $this->recordingFileFileSize;
    }

    public function setRecordingFileMimeType(?string $recordingFileMimeType): static
    {
        $this->recordingFileMimeType = $recordingFileMimeType;

        return $this;
    }

    public function getRecordingFileMimeType(): ?string
    {
        return $this->recordingFileMimeType;
    }

    public function setRecordingFileBaseName(?string $recordingFileBaseName): static
    {
        $this->recordingFileBaseName = $recordingFileBaseName;

        return $this;
    }

    public function getRecordingFileBaseName(): ?string
    {
        return $this->recordingFileBaseName;
    }

    public function setUser(?UserDto $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getUser(): ?UserDto
    {
        return $this->user;
    }

    public function setUserId($id): static
    {
        $value = !is_null($id)
            ? new UserDto($id)
            : null;

        return $this->setUser($value);
    }

    public function getUserId()
    {
        if ($dto = $this->getUser()) {
            return $dto->getId();
        }

        return null;
    }

    public function setResidentialDevice(?ResidentialDeviceDto $residentialDevice): static
    {
        $this->residentialDevice = $residentialDevice;

        return $this;
    }

    public function getResidentialDevice(): ?ResidentialDeviceDto
    {
        return $this->residentialDevice;
    }

    public function setResidentialDeviceId($id): static
    {
        $value = !is_null($id)
            ? new ResidentialDeviceDto($id)
            : null;

        return $this->setResidentialDevice($value);
    }

    public function getResidentialDeviceId()
    {
        if ($dto = $this->getResidentialDevice()) {
            return $dto->getId();
        }

        return null;
    }
}
