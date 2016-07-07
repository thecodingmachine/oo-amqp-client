<?php

namespace Mouf\AmqpClient\Objects;

use Mouf\AmqpClient\RabbitMqObjectInterface;
use PhpAmqpLib\Channel\AMQPChannel;

class Binding implements RabbitMqObjectInterface
{
    /**
     * @var Exchange
     */
    private $source;

    /**
     * Routing key.
     *
     * @var string
     */
    private $routing_key = '';

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
    private $ticket = 0;

    /**
     * @var Queue
     */
    private $to;

    /**
     * Parameter to initialize object only one time.
     *
     * @var bool
     */
    private $init = false;

    /**
     * Set the source (Exchange) to the destination (Queue).
     *
     * @param Exchange $source
     * @param Queue    $to
     */
    public function __contruct(Exchange $source, Queue $to)
    {
        $this->source = $source;
        $this->to = $to;
    }

    public function init(AMQPChannel $amqpChannel)
    {
        if (!$this->init) {
            $this->source->init();
            $this->to->init();

            $amqpChannel->queue_bind($this->to->getName(),
                                        $this->source->getName(),
                                        $this->routing_key,
                                        $this->nowait,
                                        $this->arguments,
                                        $this->ticket);

            $this->init = true;
        }
    }

    /**
     * Get Routing key.
     *
     * @return string
     */
    public function getRoutingKey()
    {
        return $this->routing_key;
    }

    /**
     * Set routing key.
     *
     * @param string $routing_key
     *
     * @return Binding
     */
    public function setRoutingKey($routing_key)
    {
        $this->routing_key = $routing_key;

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
     * @return Binding
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
     * @param bool $arguments
     *
     * @return Binding
     */
    public function setArguments(array $arguments)
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
     * @return Binding
     */
    public function setTicket($ticket)
    {
        $this->ticket = $ticket;

        return $this;
    }
}
