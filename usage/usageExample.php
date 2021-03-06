<?php
require_once(__DIR__ .  '/../vendor/autoload.php');

/**
    Example is used with React stream, but you can use whatever library you want to as long as it implement Madkom\EventStore\Client\Domain\Socket\Stream
    Your EventStore must be up, to handle connection
 */

$loop = React\EventLoop\Factory::create();

$dnsResolverFactory = new React\Dns\Resolver\Factory();
$dns = $dnsResolverFactory->createCached(null, $loop);


$connector = new React\SocketClient\Connector($loop, $dns);

$resolvedConnection = $connector->create(gethostbyname('es'), 1113);
$resolvedConnection->then(function (React\Stream\Stream $stream) {

    // We create Event Store API Object
    $eventStore = new \Madkom\EventStore\Client\Application\Api\EventStore(new \Madkom\EventStore\Client\Infrastructure\ReactStream($stream), new \Madkom\EventStore\Client\Infrastructure\InMemoryLogger());

    // We add bunch of listeners, because API is asynchronous
    $eventStore->addAction(\Madkom\EventStore\Client\Domain\Socket\Message\MessageType::HEARTBEAT_REQUEST, function() {
        echo "I response to ES heartbeat request\n";
    });

    $eventStore->addAction(\Madkom\EventStore\Client\Domain\Socket\Message\MessageType::WRITE_EVENTS_COMPLETED, function($data) {
        echo "Added new event: \n";
//        print_r($data);
    });

    $eventStore->addAction(\Madkom\EventStore\Client\Domain\Socket\Message\MessageType::READ_STREAM_EVENTS_FORWARD_COMPLETED, function($data){
        print_r($data);
    });

    //We start to listen for event we added
    $eventStore->run();


    //Now let's try writing messages to stream
    $eventStreamId = 'someteststream';

//    Add new event to stream
    $event = new \EventStore\Client\Messages\NewEvent();
    $event->setData(json_encode(['test' => 'bla']));
    $event->setEventType('testType');
//    UUID must have 32bits
    $event->setEventId(hex2bin(md5(\Rhumsaa\Uuid\Uuid::uuid4())));
    $event->setDataContentType(1);
    $event->setMetadataContentType(2);

    $writeEvents = new \EventStore\Client\Messages\WriteEvents();
    $writeEvents->setEventStreamId($eventStreamId);
    $writeEvents->setExpectedVersion(-2);
//    If you don't have master-slave nodes
    $writeEvents->setRequireMaster(false);
    $writeEvents->appendEvents($event);

    $eventStore->sendMessage(
        new \Madkom\EventStore\Client\Domain\Socket\Message\SocketMessage(
            new \Madkom\EventStore\Client\Domain\Socket\Message\MessageType(\Madkom\EventStore\Client\Domain\Socket\Message\MessageType::WRITE_EVENTS),
            null,
            $writeEvents)
    );

    $readStreamEvent = new \Madkom\EventStore\Client\Domain\Socket\oldData\ReadStreamEvents();
    $readStreamEvent->setEventStreamId($eventStreamId);
    $readStreamEvent->setResolveLinkTos(false);
    $readStreamEvent->setRequireMaster(false);
    $readStreamEvent->setMaxCount(100);
    $readStreamEvent->setFromEventNumber(0);

    $eventStore->sendMessage(new \Madkom\EventStore\Client\Domain\Socket\Message\SocketMessage(
        new \Madkom\EventStore\Client\Domain\Socket\Message\MessageType(\Madkom\EventStore\Client\Domain\Socket\Message\MessageType::READ_STREAM_EVENTS_FORWARD),
        null,
        $readStreamEvent,
        new \Madkom\EventStore\Client\Domain\Socket\Message\Credentials('admin', 'changeit')
    ));
});

$loop->run();
