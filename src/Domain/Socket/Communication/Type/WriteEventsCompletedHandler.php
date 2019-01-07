<?php

namespace Madkom\EventStore\Client\Domain\Socket\Communication\Type;

use EventStore\Client\Messages\WriteEventsCompleted;
use Madkom\EventStore\Client\Domain\Socket\Communication\Communicable;
use Madkom\EventStore\Client\Domain\Socket\Message\MessageType;
use Madkom\EventStore\Client\Domain\Socket\Message\SocketMessage;

/**
 * Class WriteEventsCompleted
 * @package Madkom\EventStore\Client\Domain\Socket\Communication\Type
 * @author  Dariusz Gafka <d.gafka@madkom.pl>
 */
class WriteEventsCompletedHandler implements Communicable
{

    /**
     * @inheritDoc
     */
    public function handle(MessageType $messageType, $correlationID, $data)
    {
        $dataObject = new WriteEventsCompleted();
        $dataObject->mergeFromString($data);

        return new SocketMessage($messageType, $correlationID, $dataObject);
    }

}
