<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: ClientMessageDtos.proto

namespace EventStore\Client\Messages;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>EventStore.Client.Messages.PersistentSubscriptionStreamEventAppeared</code>
 */
class PersistentSubscriptionStreamEventAppeared extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>.EventStore.Client.Messages.ResolvedIndexedEvent event = 1;</code>
     */
    private $event = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \EventStore\Client\Messages\ResolvedIndexedEvent $event
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\ClientMessageDtos::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>.EventStore.Client.Messages.ResolvedIndexedEvent event = 1;</code>
     * @return \EventStore\Client\Messages\ResolvedIndexedEvent
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Generated from protobuf field <code>.EventStore.Client.Messages.ResolvedIndexedEvent event = 1;</code>
     * @param \EventStore\Client\Messages\ResolvedIndexedEvent $var
     * @return $this
     */
    public function setEvent($var)
    {
        GPBUtil::checkMessage($var, \EventStore\Client\Messages\ResolvedIndexedEvent::class);
        $this->event = $var;

        return $this;
    }

}

