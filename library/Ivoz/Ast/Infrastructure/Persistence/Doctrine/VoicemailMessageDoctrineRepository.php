<?php

namespace Ivoz\Ast\Infrastructure\Persistence\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Ivoz\Ast\Domain\Model\VoicemailMessage\VoicemailMessage;
use Ivoz\Ast\Domain\Model\VoicemailMessage\VoicemailMessageInterface;
use Ivoz\Ast\Domain\Model\VoicemailMessage\VoicemailMessageRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * VoicemailMessageDoctrineRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VoicemailMessageDoctrineRepository extends ServiceEntityRepository implements VoicemailMessageRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VoicemailMessage::class);
    }

    /**
     * @param $mailbox
     * @param $context
     * @param $num
     * @return VoicemailMessageInterface
     */
    public function findIndexMessageByMailboxContextNum($mailbox, $context, $msgnum)
    {
        $dir = sprintf("%s/%s/%s/%s/%s",
            "/opt/irontec/ivozprovider/storage",
            "asterisk/spool/voicemail",
            $mailbox,
            $context,
            "INBOX"
        );

        /** @var VoicemailMessageInterface $response */
        $response = $this->findOneBy([
            'dir' => $dir,
            'msgnum' => $msgnum
        ]);

        return $response;
    }
}
