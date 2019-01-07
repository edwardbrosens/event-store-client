<?php

namespace Madkom\EventStore\Client\Domain\Socket\Communication\Type;

use EventStore\Client\Messages\SubscriptionConfirmation;
use Madkom\EventStore\Client\Domain\Socket\Communication\Communicable;
use Madkom\EventStore\Client\Domain\Socket\Message\MessageType;
use Madkom\EventStore\Client\Domain\Socket\Message\SocketMessage;

/**
 * Class SubscriptionConfirmation
 * @package Madkom\EventStore\Client\Domain\Socket\Communication\Type
 * @author  Dariusz Gafka <d.gafka@madkom.pl>
 */
class SubscriptionConfirmationHandler implements Communicable
{

    /**
     * @inheritDoc
     */
    public function handle(MessageType $messageType, $correlationID, $data)
    {
        $dataObject = new SubscriptionConfirmation();
        $dataObject->mergeFromString($data);

        return new SocketMessage($messageType, $correlationID, $dataObject);
    }

}
