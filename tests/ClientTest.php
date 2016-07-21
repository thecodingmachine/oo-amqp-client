<?php

namespace Mouf\AmqpClient\Objects;

use Mouf\AmqpClient\Client;
use Mouf\AmqpClient\Consumer;
use Mouf\AmqpClient\ConsumerService;
use PhpAmqpLib\Message\AMQPMessage;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    private $client;
    private $exchange;
    /**
     * @var Queue
     */
    private $queue;
    /**
     * @var Exchange
     */
    private $deadLetterExchange;
    /**
     * @var Queue
     */
    private $deadLetterQueue;
    private $msgReceived;
    private $deadLetterMsgReceived;
    private $triggerException = false;

    protected function init()
    {
        global $rabbitmq_host;
        global $rabbitmq_port;
        global $rabbitmq_user;
        global $rabbitmq_password;

        $this->client = new Client($rabbitmq_host, $rabbitmq_port, $rabbitmq_user, $rabbitmq_password);
        $this->client->setPrefetchCount(1);

        $this->exchange = new Exchange($this->client, 'test_exchange', 'fanout');
        $this->queue = new Queue($this->client, 'test_queue', [
            new Consumer(function(AMQPMessage $msg) {
                $this->msgReceived = $msg;
                if ($this->triggerException) {
                    throw new \Exception('boom!');
                }
            })
        ]);
        $this->queue->setDurable(true);

        $binding = new Binding($this->exchange, $this->queue);
        $this->client->register($binding);


        $this->deadLetterExchange = new Exchange($this->client, 'test_dead_letter_exchange', 'fanout');

        $this->deadLetterQueue = new Queue($this->client, 'test_dead_letter_queue', [
            new Consumer(function(AMQPMessage $msg) {
                $this->deadLetterMsgReceived = $msg;
            })
        ]);
        $this->deadLetterQueue->setDurable(true);

        $this->queue->setDeadLetterExchange($this->deadLetterExchange);

        $binding = new Binding($this->deadLetterExchange, $this->deadLetterQueue);
        $this->client->register($binding);
    }

    public function testExchange()
    {
        $this->init();
        $this->exchange->publish(new Message('my message'), 'key');
    }

    /**
     * @depends testExchange
     */
    public function testQueue()
    {
        $this->init();

        $consumerService = new ConsumerService($this->client, [
            $this->queue
        ]);

        $consumerService->run(true);

        $this->assertEquals('my message', $this->msgReceived->getBody());
    }


    /**
     * @depends testExchange
     */
    public function testDeadLetterQueue()
    {
        $this->init();

        $this->exchange->publish(new Message('my other message'), 'key');

        $this->triggerException = true;

        $consumerService = new ConsumerService($this->client, [
            $this->queue,
            $this->deadLetterQueue
        ]);

        $consumerService->run(true);
        $consumerService->run(true);

        $this->assertEquals('my other message', $this->msgReceived->getBody());
        $this->assertEquals('my other message', $this->deadLetterMsgReceived->getBody());
    }

}
