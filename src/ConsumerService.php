<?php

namespace Mouf\AmqpClient;

use Mouf\AmqpClient\Objects\Queue;

/**
 * Function to consume RabbitMq queue.
 * 
 * @author Marc Teyssier
 */
class ConsumerService
{
    /**
     * @param Client $client
     */
    private $client;

    /**
     * @param Queue[] $queues
     */
    private $queues;

    /**
     * @param Queue[] $queues List of queue to listen
     */
    public function __construct(Client $client, $queues)
    {
        $this->client = $client;
        $this->queues = $queues;
    }

    /**
     * Call this function in your script to consume the RabbitMq queue.
     */
    public function run($nonBlocking = false)
    {
        foreach ($this->queues as $queue) {
            /* @var Queue $queue */
            $queue->consume();
        }

        $channel = $this->client->getChannel();
        if ($nonBlocking) {
            if (count($channel->callbacks)) {
                $channel->wait(null, true);
            }
        } else {
            while (count($channel->callbacks)) {
                $channel->wait();
            }
        }

        foreach ($this->queues as $queue) {
            /* @var Queue $queue */
            $queue->cancelConsume();
        }

    }
}
