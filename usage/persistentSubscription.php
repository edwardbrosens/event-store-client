<?php

use EventStore\Client\Messages\ConnectToPersistentSubscription;
use EventStore\Client\Messages\NotHandled;
use EventStore\Client\Messages\PersistentSubscriptionAckEvents;
use EventStore\Client\Messages\PersistentSubscriptionConfirmation;
use EventStore\Client\Messages\PersistentSubscriptionStreamEventAppeared;
use Madkom\EventStore\Client\Application\Api\EventStore;
use Madkom\EventStore\Client\Domain\Socket\Message\Credentials;
use Madkom\EventStore\Client\Domain\Socket\Message\MessageType;
use Madkom\EventStore\Client\Domain\Socket\Message\SocketMessage;
use Madkom\EventStore\Client\Infrastructure\InMemoryLogger;
use Madkom\EventStore\Client\Infrastructure\ReactStream;

require_once(__DIR__ .  '/../vendor/autoload.php');

$loop = React\EventLoop\Factory::create();
$connector = new \React\Socket\Connector($loop);
$connector->connect('tcp://localhost:1113')
    ->then(function (\React\Socket\ConnectionInterface $connection) use ($loop) {

        $eventStore = new EventStore(new ReactStream($connection), new InMemoryLogger());

        $subscriptionId = '';

        /**
         * Don't set allowed in flight messages too high or else the event loop is
         * broken.
         */
        $connectToPersistentSubscription = new ConnectToPersistentSubscription();
        $connectToPersistentSubscription->setSubscriptionId('ctd');
        $connectToPersistentSubscription->setEventStreamId('6c9506b5-f5c7-452c-965e-31a0a77f9268');
        $connectToPersistentSubscription->setAllowedInFlightMessages(1);

        $eventStore->sendMessage(new SocketMessage(
            new MessageType(MessageType::CONNECT_TO_PERSISTENT_SUBSCRIPTION),
            null,
            $connectToPersistentSubscription,
            new Credentials('admin', 'changeit')
        ));

//        $eventStore->addAction(MessageType::HEARTBEAT_REQUEST, function (SocketMessage $socketMessage) use($eventStore) {
//            echo "Heartbeat request don't forget to send heartbeat response";
//            $heartbeatRepsonseMessage = new SocketMessage(new MessageType(MessageType::HEARTBEAT_RESPONSE), $socketMessage->getCorrelationID(), null, new Credentials('admin', 'changeit'));
//            $eventStore->sendMessage($heartbeatRepsonseMessage);
//        });

        $eventStore->addAction(MessageType::NOT_HANDLED, function (SocketMessage $socketMessage) use($eventStore) {
            echo "Heartbeat request don't forget to send heartbeat response";

            $notHandled = new NotHandled();
            $notHandled->mergeFromString($socketMessage->getData());
            $reason = $notHandled->getReason();


            $heartbeatRepsonseMessage = new SocketMessage(new MessageType(MessageType::HEARTBEAT_RESPONSE), $socketMessage->getCorrelationID(), null, new Credentials('admin', 'changeit'));
            $eventStore->sendMessage($heartbeatRepsonseMessage);
        });

        $eventStore->addAction(MessageType::PERSISTENT_SUBSCRIPTION_CONFIRMATION, function(SocketMessage $socketMessage) use (&$subscriptionId) {
            /** @var PersistentSubscriptionConfirmation $socketMessageData */
            $socketMessageData = $socketMessage->getData();
            $subscriptionId = $socketMessageData->getSubscriptionId();

            echo 'Subscription confirmed: ' . $subscriptionId . "\n";
        });
        $eventStore->addAction(MessageType::SUBSCRIPTION_DROPPED, function(SocketMessage $socketMessage) use ($loop) {
            echo "Subscription dropped, bye!";
            $loop->stop();
        });


        $eventStore->addAction(MessageType::PERSISTENT_SUBSCRIPTION_STREAM_EVENT_APPEARED, function(SocketMessage $socketMessage) use ($eventStore, &$subscriptionId) {
            echo "Event appeared, don't forget to ack!\n";

            /** @var PersistentSubscriptionStreamEventAppeared $socketMessageData */
            $socketMessageData = $socketMessage->getData();

            /**
             * Acking is important, and will not work without $socketMessage->getCorrelationID() provided.
             */
            $ack = new PersistentSubscriptionAckEvents();
            $ack->setSubscriptionId($subscriptionId);
            $ack->setProcessedEventIds([$socketMessageData->getEvent()->getEvent()->getEventId()]);
            $eventStore->sendMessage(new SocketMessage(
                new MessageType(MessageType::PERSISTENT_SUBSCRIPTION_ACK_EVENTS),
                $socketMessage->getCorrelationID(),
                $ack,
                new Credentials('admin', 'changeit')
            ));
        });

        $eventStore->run();
    },
    function(Exception $e) use ($loop) {
        echo sprintf(
            'Unable to connect: %s in file %s on line %s%s',
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            PHP_EOL
        );
        echo sprintf(
            'Trace:%s%s%s',
            PHP_EOL,
            $e->getTraceAsString(),
            PHP_EOL
        );
        $loop->stop();
    }
);

$loop->run();
