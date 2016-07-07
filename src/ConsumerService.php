<?php

namespace Mouf\AmqpClient;

use Mouf\AmqpClient\Objects\Queue;

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
    }

    public function run()
    {
        foreach ($this->queues as $queue) {
            /* @var Queue $queue */
            $queue->consume();
        }

        $channel = $this->client->getChannel();

        while (count($channel->callbacks)) {
            $channel->wait();
        }
    }
}
