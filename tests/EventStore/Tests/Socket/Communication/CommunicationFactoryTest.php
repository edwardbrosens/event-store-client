<?php

/**
 * Class CommunicationFactoryTest
 * @author Dariusz Gafka <dgafka.mail@gmail.com>
 */
class CommunicationFactoryTest extends PHPUnit_Framework_TestCase
{

    /** @var  \EventStore\Client\Domain\Socket\Communication\CommunicationFactory */
    private $communicationFactory;

    public function setUp()
    {
        $this->communicationFactory = new \EventStore\Client\Domain\Socket\Communication\CommunicationFactory();
    }

    /**
     * @test
     */
    public function it_should_create_new_communication()
    {
        $messageType = $this->prophesize('EventStore\Client\Domain\Socket\Message\MessageType');
        $messageType->getType()->willReturn(\EventStore\Client\Domain\Socket\Message\MessageType::HEARTBEAT_RESPONSE);

        PHPUnit_Framework_Assert::assertInstanceOf('EventStore\Client\Domain\Socket\Communication\Type\HeartBeatResponse', $this->communicationFactory->create($messageType->reveal()));
    }

}