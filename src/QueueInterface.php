<?php


namespace Mouf\AmqpClient;

/**
 * AMQP Queues
 */
interface QueueInterface extends RabbitMqObjectInterface
{
    /**
     * Sends to RabbitMQ the order to subscribe to the consumers.
     */
    public function consume();

    /**
     * Unsubscribes consumers
     */
    public function cancelConsume();
}
