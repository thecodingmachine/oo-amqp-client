<?php

namespace Mouf\AmqpClient\Objects;

use PhpAmqpLib\Message\AMQPMessage;

/**
 * A message sent to RabbitMQ.
 */
class Message
{
    private $body;

    /**
     * @var string
     */
    private $content_type;

    /**
     * @var string
     */
    private $content_encoding;

    /**
     * @var array
     */
    private $application_headers;

    /**
     * @var int
     */
    private $delivery_mode;

    /**
     * @var int
     */
    private $priority;

    /**
     * @var string
     */
    private $correlation_id;

    /**
     * @var string
     */
    private $reply_to;

    /**
     * @var string
     */
    private $expiration;

    /**
     * @var string
     */
    private $message_id;

    /**
     * @var \DateTimeInterface
     */
    private $timestamp;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $user_id;

    /**
     * @var string
     */
    private $app_id;

    /**
     * @var string
     */
    private $cluster_id;

    /**
     * Message constructor.
     *
     * @param string $body
     */
    public function __construct($body)
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->content_type;
    }

    /**
     * @param string $content_type
     */
    public function setContentType($content_type)
    {
        $this->content_type = $content_type;
    }

    /**
     * @return string
     */
    public function getContentEncoding()
    {
        return $this->content_encoding;
    }

    /**
     * @param string $content_encoding
     */
    public function setContentEncoding($content_encoding)
    {
        $this->content_encoding = $content_encoding;
    }

    /**
     * @return array
     */
    public function getApplicationHeaders()
    {
        return $this->application_headers;
    }

    /**
     * @param array $application_headers
     */
    public function setApplicationHeaders($application_headers)
    {
        $this->application_headers = $application_headers;
    }

    /**
     * @return int
     */
    public function getDeliveryMode()
    {
        return $this->delivery_mode;
    }

    /**
     * @param int $delivery_mode
     */
    public function setDeliveryMode($delivery_mode)
    {
        $this->delivery_mode = $delivery_mode;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return string
     */
    public function getCorrelationId()
    {
        return $this->correlation_id;
    }

    /**
     * @param string $correlation_id
     */
    public function setCorrelationId($correlation_id)
    {
        $this->correlation_id = $correlation_id;
    }

    /**
     * @return string
     */
    public function getReplyTo()
    {
        return $this->reply_to;
    }

    /**
     * @param string $reply_to
     */
    public function setReplyTo($reply_to)
    {
        $this->reply_to = $reply_to;
    }

    /**
     * @return string
     */
    public function getExpiration()
    {
        return $this->expiration;
    }

    /**
     * @param string $expiration
     */
    public function setExpiration($expiration)
    {
        $this->expiration = $expiration;
    }

    /**
     * @return string
     */
    public function getMessageId()
    {
        return $this->message_id;
    }

    /**
     * @param string $message_id
     */
    public function setMessageId($message_id)
    {
        $this->message_id = $message_id;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param \DateTimeInterface $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param string $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return string
     */
    public function getAppId()
    {
        return $this->app_id;
    }

    /**
     * @param string $app_id
     */
    public function setAppId($app_id)
    {
        $this->app_id = $app_id;
    }

    /**
     * @return string
     */
    public function getClusterId()
    {
        return $this->cluster_id;
    }

    /**
     * @param string $cluster_id
     */
    public function setClusterId($cluster_id)
    {
        $this->cluster_id = $cluster_id;
    }

    /**
     * @return AMQPMessage
     */
    public function toAMQPMessage()
    {
    }
}
