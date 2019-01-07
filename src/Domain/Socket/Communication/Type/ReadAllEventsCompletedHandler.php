<?php

namespace Madkom\EventStore\Client\Domain\Socket\Communication\Type;

use EventStore\Client\Messages\ReadAllEventsCompleted;
use Madkom\EventStore\Client\Domain\Socket\Communication\Communicable;
use Madkom\EventStore\Client\Domain\Socket\Message\MessageType;
use Madkom\EventStore\Client\Domain\Socket\Message\SocketMessage;

/**
 * Class ReadAllEventsForwardCompleted
 * @package Madkom\EventStore\Client\Domain\Socket\Communication\Type
 * @author  Dariusz Gafka <d.gafka@madkom.pl>
 */
class ReadAllEventsCompletedHandler implements Communicable
{

    /**
     * @inheritdoc
     */
    public function handle(MessageType $messageType, $correlationID, $data)
    {
        $dataObject = new ReadAllEventsCompleted();
        $dataObject->mergeFromString($data);

        return new SocketMessage($messageType, $correlationID, $dataObject);
    }

}
