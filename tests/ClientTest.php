<?php

namespace Mouf\AmqpClient\Objects;

use Mouf\AmqpClient\Client;
use Mouf\AmqpClient\Consumer;
use Mouf\AmqpClient\ConsumerService;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    private $client;
    private $exchange;
    private $queue;

    protected function setUp()
    {
        global $rabbitmq_host;
        global $rabbitmq_port;
        global $rabbitmq_user;
        global $rabbitmq_password;

        $this->client = new Client($rabbitmq_host, $rabbitmq_port, $rabbitmq_user, $rabbitmq_password);
        $this->client->setPrefetchCount(1);

        $this->exchange = new Exchange($this->client, 'test_exchange', 'fanout');
        $this->queue = new Queue($this->client, 'test_queue', [
            new Consumer(function($msg) {
                var_dump($msg);
            })
        ]);
        $this->queue->setDurable(true);

        $binding = new Binding($this->exchange, $this->queue);
        $this->client->register($binding);
    }



    public function testExchange()
    {
        $this->exchange->publish(new Message('my message'), 'key');
    }

    /**
     * @depends testExchange
     */
    public function testQueue()
    {
        $consumerService = new ConsumerService($this->client, [
            $this->queue
        ]);

        $consumerService->run();
    }
}
