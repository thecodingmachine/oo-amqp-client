<?php

namespace Mouf\AmqpClient\Objects;

use Mouf\AmqpClient\Client;
use Mouf\AmqpClient\RabbitMqObjectInterface;
use PhpAmqpLib\Channel\AMQPChannel;

class Exchange implements RabbitMqObjectInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * Name.
     *
     * @var string
     */
    private $name;

    /**
     * Type direct, topic, headers or fanout.
     *
     * @var string
     */
    private $type;

    /**
     * Passive.
     *
     * @var bool
     */
    private $passive = false;

    /**
     * Durable.
     *
     * @var bool
     */
    private $durable = false;

    /**
     * Auto delete.
     *
     * @var bool
     */
    private $autoDelete = false;

    /**
     * Internal.
     *
     * @var bool
     */
    private $internal = false;

    /**
     * No wait.
     *
     * @var bool
     */
    private $nowait = false;

    /**
     * Arguments.
     *
     * @var array
     */
    private $arguments = null;

    /**
     * Ticket.
     *
     * @var int
     */
    private $ticket = null;

    /**
     * Parameter to initialize object only one time.
     *
     * @var bool
     */
    private $init = false;

    /**
     * Set the to (Binding).
     *
     * @param Binding $to
     * @param string  $name
     * @param string  $type direct, topic, headers or fanout
     */
    public function __construct(Client $client, $name, $type)
    {
        $this->client = $client;
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * Get the exchange name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get passive.
     *
     * @return bool
     */
    public function getPassive()
    {
        return $this->passive;
    }

    /**
     * Set passive.
     *
     * @param bool $passive
     *
     * @return Exchange
     */
    public function setPassive($passive)
    {
        $this->passive = $passive;

        return $this;
    }

    /**
     * Get durable.
     *
     * @return bool
     */
    public function getDurable()
    {
        return $this->durable;
    }

    /**
     * Set durable.
     *
     * @param bool $durable
     *
     * @return Exchange
     */
    public function setDurable($durable)
    {
        $this->durable = $durable;

        return $this;
    }

    /**
     * Get autoDelete.
     *
     * @return bool
     */
    public function getAutoDelete()
    {
        return $this->autoDelete;
    }

    /**
     * Set autoDelete.
     *
     * @param bool $autoDelete
     *
     * @return Exchange
     */
    public function setAutoDelete($autoDelete)
    {
        $this->autoDelete = $autoDelete;

        return $this;
    }

    /**
     * Get internal.
     *
     * @return bool
     */
    public function getInternal()
    {
        return $this->internal;
    }

    /**
     * Set internal.
     *
     * @param bool $internal
     *
     * @return Exchange
     */
    public function setInternal($internal)
    {
        $this->internal = $internal;

        return $this;
    }

    /**
     * Get nowait.
     *
     * @return bool
     */
    public function getNowait()
    {
        return $this->nowait;
    }

    /**
     * Set nowait.
     *
     * @param bool $nowait
     *
     * @return Exchange
     */
    public function setNowait($nowait)
    {
        $this->nowait = $nowait;

        return $this;
    }

    /**
     * Get arguments.
     *
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Set arguments.
     *
     * @param array $arguments
     *
     * @return Exchange
     */
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * Get ticket.
     *
     * @return int
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * Set ticket.
     *
     * @param int $ticket
     *
     * @return Exchange
     */
    public function setTicket($ticket)
    {
        $this->ticket = $ticket;

        return $this;
    }

    public function init(AMQPChannel $amqpChannel)
    {
        if (!$this->init) {
            $amqpChannel->exchange_declare($this->name,
                                            $this->type,
                                            $this->passive,
                                            $this->durable,
                                            $this->auto_delete,
                                            $this->internal,
                                            $this->nowait,
                                            $this->arguments,
                                            $this->ticket);
            $this->init = true;
        }
    }

    public function publish(Message $message, $routingKey, $mandatory = false,
                            $immediate = false,
                            $ticket = null)
    {
        $channel = $this->client->getChannel();

        $channel->basic_publish($message->toAMQPMessage(), $this->name, $routingKey, $mandatory, $immediate, $ticket);
    }
}
