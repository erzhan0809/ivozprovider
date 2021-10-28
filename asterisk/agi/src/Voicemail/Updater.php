<?php

namespace Voicemail;

use Assert\Assertion;
use Doctrine\ORM\EntityManagerInterface;
use Ivoz\Ast\Domain\Model\Voicemail\Voicemail;
use Ivoz\Ast\Domain\Model\VoicemailMessage\VoicemailMessage;
use Ivoz\Ast\Domain\Model\VoicemailMessage\VoicemailMessageDto;
use Ivoz\Core\Application\Service\EntityTools;
use PhpMimeMailParser\Parser;
use RouteHandlerAbstract;

class Updater extends RouteHandlerAbstract
{
    const VM_CATEGORY    = 0;
    const VM_NAME        = 1;
    const VM_MAILBOX     = 2;
    const VM_CONTEXT     = 3;
    const VM_DUR         = 4;
    const VM_MSGNUM      = 5;
    const VM_CALLERID    = 6;
    const VM_CIDNAME     = 7;
    const VM_CIDNUM      = 8;
    const VM_DATE        = 9;
    const VM_MESSAGEFILE = 10;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var EntityTools
     */
    protected $entityTools;

    /**
     * @var Parser
     */
    protected $parser;

    /**
     * Sender constructor.
     * @param EntityManagerInterface $em
     * @param EntityTools $entityTools
     * @param Parser $parser
     */
    public function __construct(
        EntityManagerInterface $em,
        EntityTools $entityTools,
        Parser $parser
    ) {
        $this->em = $em;
        $this->entityTools = $entityTools;
        $this->parser = $parser;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function process()
    {
        // Load Email data
        $this->parser->setStream(fopen("php://stdin", "r"));

        // Get Voicemail data from body content
        $vmdata = explode(PHP_EOL, $this->parser->getMessageBody());

        /** @var \Ivoz\Ast\Domain\Model\Voicemail\VoicemailRepository $vmRepository */
        $vmRepository = $this->em->getRepository(Voicemail::class);

        /** @var \Ivoz\Ast\Domain\Model\Voicemail\VoicemailInterface $vm */
        $vm = $vmRepository->findByMailboxAndContext(
            $vmdata[self::VM_MAILBOX],
            $vmdata[self::VM_CONTEXT]
        );

        // No voicemail, this should not happen
        Assertion::notNull(
            $vm,
            sprintf(
                "Unable to find voicemail for %s@%s",
                $vmdata[self::VM_MAILBOX],
                $vmdata[self::VM_CONTEXT]
            )
        );

        /** @var \Ivoz\Provider\Domain\Model\User\UserInterface $user */
        $user = $vm->getUser();
        Assertion::notNull(
            $user,
            sprintf(
                "Unable to find user for voicemail %s@%s",
                $vmdata[self::VM_MAILBOX],
                $vmdata[self::VM_CONTEXT]
            )
        );

        /** @var \Ivoz\Ast\Domain\Model\VoicemailMessage\VoicemailMessageRepository $vmMessageRepository */
        $vmMessageRepository = $this->em->getRepository(VoicemailMessage::class);

        /** @var \Ivoz\Ast\Domain\Model\Voicemail\VoicemailInterface $vm */
        $vmMessage = $vmMessageRepository->findIndexMessageByMailboxContextNum(
            $vmdata[self::VM_MAILBOX],
            $vmdata[self::VM_CONTEXT],
            $vmdata[self::VM_MSGNUM]
        );

        // No voicemail message, this should not happen
        Assertion::notNull(
            $vmMessage,
            sprintf(
                "Unable to find voicemail message for %s@%s:%s",
                $vmdata[self::VM_MAILBOX],
                $vmdata[self::VM_CONTEXT],
                $vmdata[self::VM_MSGNUM]
            )
        );

        /** @var VoicemailMessageDto $vmMessageDto */
        $vmMessageDto = $this->entityTools->entityToDto($vmMessage);

        $residentialId = ($vm->getResidentialDevice()) ?
            $vm->getResidentialDevice()->getId() :
            null;

        $userId = ($vm->getUser()) ?
            $vm->getUser()->getId() :
            null;

        // Update voicemail message data
        $vmMessageDto
            ->setResidentialDeviceId($residentialId)
            ->setUser($userId);

        $this->entityTools->persistDto($vmMessageDto, $vmMessage, true);


    }
}
