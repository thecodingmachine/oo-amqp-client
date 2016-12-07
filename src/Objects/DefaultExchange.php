<?php


namespace Mouf\AmqpClient\Objects;


use Mouf\AmqpClient\Client;
use PhpAmqpLib\Channel\AMQPChannel;

/**
 * The RabbitMQ default exchange will automatically send messages to the queue whose key is the key of the message.
 * This is RabbitMQ specific.
 */
class DefaultExchange extends Exchange
{
    public function __construct(Client $client)
    {
        parent::__construct($client, '', 'direct');
    }

    public function init(AMQPChannel $amqpChannel)
    {
        // Let's do nothing. The default exchange already exists and cannot be overwritten.
    }
}