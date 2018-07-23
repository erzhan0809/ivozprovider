<?php

namespace spec\Ivoz\Kam\Domain\Service\TrunksLcrRuleTarget;

use Ivoz\Core\Domain\Service\EntityPersisterInterface;
use Ivoz\Kam\Domain\Model\TrunksLcrGateway\TrunksLcrGatewayInterface;
use Ivoz\Kam\Domain\Model\TrunksLcrRule\TrunksLcrRuleInterface;
use Ivoz\Kam\Domain\Model\TrunksLcrRuleTarget\TrunksLcrRuleTarget;
use Ivoz\Kam\Domain\Model\TrunksLcrRuleTarget\TrunksLcrRuleTargetInterface;
use Ivoz\Kam\Domain\Model\TrunksLcrRuleTarget\TrunksLcrRuleTargetDto;
use Ivoz\Provider\Domain\Model\Carrier\CarrierInterface;
use Ivoz\Provider\Domain\Model\CarrierServer\CarrierServerInterface;
use Ivoz\Provider\Domain\Model\OutgoingRouting\OutgoingRoutingInterface;
use Ivoz\Kam\Domain\Service\TrunksLcrRuleTarget\CreateByOutgoingRouting;
use Ivoz\Kam\Domain\Model\TrunksLcrRuleTarget\TrunksLcrRuleTargetRepository;
use Ivoz\Core\Application\Service\EntityTools;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use spec\HelperTrait;

class CreateByOutgoingRoutingSpec extends ObjectBehavior
{
    use HelperTrait;

    /**
     * @var EntityPersisterInterface
     */
    protected $entityPersister;

    /**
     * @var TrunksLcrRuleTargetRepository
     */
    protected $trunksLcrRuleTargetRepository;

    /**
     * @var EntityTools
     */
    protected $entityTools;

    public function let(
        EntityPersisterInterface $entityPersister,
        TrunksLcrRuleTargetRepository $trunksLcrRuleTargetRepository,
        EntityTools $entityTools
    ) {
        $this->entityPersister = $entityPersister;
        $this->trunksLcrRuleTargetRepository = $trunksLcrRuleTargetRepository;
        $this->entityTools = $entityTools;

        $this->beConstructedWith(
            $entityPersister,
            $trunksLcrRuleTargetRepository,
            $entityTools
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CreateByOutgoingRouting::class);
    }

    function it_throws_exeption_on_empty_carrier(
        OutgoingRoutingInterface $outgoingRouting
    ) {
        $exception = new \Exception('Carrier not found');
        $this
            ->shouldThrow($exception)
            ->during('execute', [$outgoingRouting]);
    }

    function it_creates_lcr_rule_targets(
        OutgoingRoutingInterface $outgoingRouting,
        CarrierInterface $carrier,
        CarrierServerInterface $carrierServer,
        TrunksLcrGatewayInterface $lcrGateway,
        TrunksLcrRuleInterface $lcrRule
    ) {
        $this->getterProphecy(
            $outgoingRouting,
            [
                'getCarrier' => $carrier,
                'getLcrRules' => [$lcrRule],
                'getPriority' => 1,
                'getWeight' => 2,
                'getId' => 3
            ]
        );

        $carrier
            ->getServers()
            ->willReturn([$carrierServer]);

        $carrierServer
            ->getLcrGateway()
            ->willReturn($lcrGateway)
            ->shouldBeCalled();

        $lcrRule
            ->getId()
            ->willReturn(4);

        $lcrGateway
            ->getId()
            ->willReturn(5);

        $this
            ->entityPersister
            ->persistDto(
                Argument::type(TrunksLcrRuleTargetDto::class),
                null
            )
            ->shouldBeCalled();

        $this->execute($outgoingRouting);
    }



    function it_updates_lcr_rule_targets(
        OutgoingRoutingInterface $outgoingRouting,
        CarrierInterface $carrier,
        CarrierServerInterface $carrierServer,
        TrunksLcrGatewayInterface $lcrGateway,
        TrunksLcrRuleInterface $lcrRule,
        TrunksLcrRuleTargetInterface $trunksLcrRuleTarget
    ) {
        $this->getterProphecy(
            $outgoingRouting,
            [
                'getCarrier' => $carrier,
                'getLcrRules' => [$lcrRule],
                'getPriority' => 1,
                'getWeight' => 2,
                'getId' => 3
            ]
        );

        $trunksLcrRuleTargetObject = $trunksLcrRuleTarget->getWrappedObject();
        $this
            ->entityTools
            ->entityToDto($trunksLcrRuleTargetObject)
            ->willReturn(TrunksLcrRuleTarget::createDto());

        $this
            ->trunksLcrRuleTargetRepository
            ->findRuleTarget(
                Argument::type(OutgoingRoutingInterface::class),
                Argument::type(TrunksLcrRuleInterface::class),
                Argument::type(TrunksLcrGatewayInterface::class)
            )
            ->willReturn($trunksLcrRuleTargetObject);

        $carrier
            ->getServers()
            ->willReturn([$carrierServer]);

        $carrierServer
            ->getLcrGateway()
            ->willReturn($lcrGateway)
            ->shouldBeCalled();

        $lcrRule
            ->getId()
            ->willReturn(4);

        $lcrGateway
            ->getId()
            ->willReturn(5);

        $this
            ->entityPersister
            ->persistDto(
                Argument::type(TrunksLcrRuleTargetDto::class),
                $trunksLcrRuleTargetObject
            )
            ->shouldBeCalled();

        $this->execute($outgoingRouting);
    }
}
