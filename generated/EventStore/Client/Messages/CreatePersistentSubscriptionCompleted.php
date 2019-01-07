<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: ClientMessageDtos.proto

namespace EventStore\Client\Messages;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>EventStore.Client.Messages.CreatePersistentSubscriptionCompleted</code>
 */
class CreatePersistentSubscriptionCompleted extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>.EventStore.Client.Messages.CreatePersistentSubscriptionCompleted.CreatePersistentSubscriptionResult result = 1;</code>
     */
    private $result = 0;
    /**
     * Generated from protobuf field <code>string reason = 2;</code>
     */
    private $reason = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type int $result
     *     @type string $reason
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\ClientMessageDtos::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>.EventStore.Client.Messages.CreatePersistentSubscriptionCompleted.CreatePersistentSubscriptionResult result = 1;</code>
     * @return int
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Generated from protobuf field <code>.EventStore.Client.Messages.CreatePersistentSubscriptionCompleted.CreatePersistentSubscriptionResult result = 1;</code>
     * @param int $var
     * @return $this
     */
    public function setResult($var)
    {
        GPBUtil::checkEnum($var, \EventStore\Client\Messages\CreatePersistentSubscriptionCompleted_CreatePersistentSubscriptionResult::class);
        $this->result = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string reason = 2;</code>
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Generated from protobuf field <code>string reason = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setReason($var)
    {
        GPBUtil::checkString($var, True);
        $this->reason = $var;

        return $this;
    }

}

