<?php

namespace Ivoz\Provider\Infrastructure\Persistence\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Ivoz\Provider\Domain\Model\DdiProvider\DdiProviderRepository;
use Ivoz\Provider\Domain\Model\DdiProvider\DdiProvider;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * DdiProviderDoctrineRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DdiProviderDoctrineRepository extends ServiceEntityRepository implements DdiProviderRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DdiProvider::class);
    }
}
