<?php

namespace Madkom\EventStore\Client\Domain\Socket\Communication\Type;

use EventStore\Client\Messages\PersistentSubscriptionStreamEventAppeared;
use Madkom\EventStore\Client\Domain\Socket\Communication\Communicable;
use Madkom\EventStore\Client\Domain\Socket\Message\MessageType;
use Madkom\EventStore\Client\Domain\Socket\Message\SocketMessage;

/**
 * Class PersistentSubscriptionStreamEventAppearedHandler
 *
 * @package Madkom\EventStore\Client\Domain\Socket\Communication\Type
 * @author Jur Jean
 */
class PersistentSubscriptionStreamEventAppearedHandler implements Communicable
{

    /**
     * @inheritDoc
     */
    public function handle(MessageType $messageType, $correlationID, $data)
    {
        $dataObject = new PersistentSubscriptionStreamEventAppeared();
        $dataObject->mergeFromString($data);

        return new SocketMessage($messageType, $correlationID, $dataObject);
    }

}
