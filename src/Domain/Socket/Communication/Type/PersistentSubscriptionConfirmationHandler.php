<?php

namespace Madkom\EventStore\Client\Domain\Socket\Communication\Type;

use EventStore\Client\Messages\PersistentSubscriptionConfirmation;
use Madkom\EventStore\Client\Domain\Socket\Communication\Communicable;
use Madkom\EventStore\Client\Domain\Socket\Message\MessageType;
use Madkom\EventStore\Client\Domain\Socket\Message\SocketMessage;

/**
 * Class PersistentSubscriptionConfirmationHandler
 *
 * @package Madkom\EventStore\Client\Domain\Socket\Communication\Type
 * @author Jur Jean
 */
class PersistentSubscriptionConfirmationHandler implements Communicable
{

    /**
     * @inheritDoc
     */
    public function handle(MessageType $messageType, $correlationID, $data)
    {
        $dataObject = new PersistentSubscriptionConfirmation();
        $dataObject->mergeFromString($data);

        return new SocketMessage($messageType, $correlationID, $dataObject);
    }

}
