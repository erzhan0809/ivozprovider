<?php

namespace Ivoz\Kam\Domain\Model\TrunksCdr;

use Doctrine\Common\Collections\Selectable;
use Doctrine\Common\Persistence\ObjectRepository;

interface TrunksCdrRepository extends ObjectRepository, Selectable
{
    /**
     * This method expects results to be marked as parsed as soon as they're used:
     * a.k.a it does not apply any query offset, just a limit
     *
     * @param int $batchSize
     * @param array|null $order
     * @return \Generator
     */
    public function getUnparsedCallsGeneratorWithoutOffset(int $batchSize, array $order = null);

    /**
     * @param array $ids
     * @return mixed
     */
    public function resetParsed(array $ids);

    /**
     * @param array $billableCallIds
     * @return int affected rows
     */
    public function resetOrphanCgrids(array $billableCallIds) :int;

    /**
     * @param string $callid
     * @return TrunksCdrInterface[]
     */
    public function findByCallid($callid);

    /**
     * @param string $callid
     * @return TrunksCdrInterface | null
     */
    public function findOneByCallid($callid);
}
