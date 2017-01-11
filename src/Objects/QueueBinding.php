<?php

namespace Mouf\AmqpClient\Objects;

use Mouf\AmqpClient\RabbitMqObjectInterface;
use PhpAmqpLib\Channel\AMQPChannel;

class QueueBinding extends Binding
{
    /**
     * @var string
     */
    private $source;

    /**
     * @var Queue
     */
    private $to;

    public function __construct($source, Queue $to)
    {
        $this->source = $source;
        $this->to = $to;
    }

    public function init(AMQPChannel $amqpChannel)
    {
        if (!$this->init) {
            $this->to->init($amqpChannel);

            $amqpChannel->queue_bind($this->to->getName(),
                                        $source,
                                        $this->getRoutingKey(),
                                        $this->getNoWait(),
                                        $this->getArguments(),
                                        $this->getTicket());

            $this->init = true;
        }
    }
}
