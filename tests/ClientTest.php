<?php

namespace Mouf\AmqpClient\Objects;

use Mouf\AmqpClient\Client;
use Mouf\AmqpClient\Consumer;
use Mouf\AmqpClient\ConsumerService;
use PhpAmqpLib\Message\AMQPMessage;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class ClientTest extends TestCase
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

    private function makeClient($port = null)
    {
        global $rabbitmq_host;
        global $rabbitmq_port;
        global $rabbitmq_user;
        global $rabbitmq_password;

        if (!$port) {
            $port = $rabbitmq_port;
        }

        $client = new Client($rabbitmq_host, $port, $rabbitmq_user, $rabbitmq_password);
        $client->setPrefetchCount(1);
        return $client;
    }

    protected function init($port = null)
    {
        $this->client = $this->makeClient($port);
        $this->exchange = new Exchange($this->client, 'test_exchange', 'fanout');
        $this->queue = new Queue($this->client, 'test_queue', [
            new Consumer(function(AMQPMessage $msg) {
                $this->msgReceived = $msg;
                if ($this->triggerException) {
                    throw new \Exception('boom!');
                }
            }, new NullLogger())
        ]);
        $this->queue->setDurable(true);

        $binding = new Binding($this->exchange, $this->queue);
        $this->client->register($binding);


        $this->deadLetterExchange = new Exchange($this->client, 'test_dead_letter_exchange', 'fanout');

        $this->deadLetterQueue = new Queue($this->client, 'test_dead_letter_queue', [
            new Consumer(function(AMQPMessage $msg) {
                $this->deadLetterMsgReceived = $msg;
            }, new NullLogger())
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
        $this->assertTrue(true);
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

    public function testExceptionOnExchangeEmptyName()
    {
        $client = $this->makeClient();
        $this->expectException(\InvalidArgumentException::class);
        new Exchange($client, '', 'direct');
    }

    public function testDefaultExchange()
    {
        $client = $this->makeClient();
        // Let's send a message on RabbitMQ default exchange (named "")
        $exchange = new DefaultExchange($client);
        $queue = new Queue($client, 'test_direct_queue', [
            new Consumer(function(AMQPMessage $msg) {
                $this->msgReceived = $msg;
            }, new NullLogger())
        ]);

        // The key is the name of the queue.
        $exchange->publish(new Message('hello'), 'test_direct_queue');

        $consumerService = new ConsumerService($client, [
            $queue
        ]);

        $consumerService->run(true);

        $this->assertEquals('hello', $this->msgReceived->getBody());
    }

    public function testPublishToQueue()
    {
        $client = $this->makeClient();
        $queue = new Queue($client, 'test_direct_queue', [
            new Consumer(function (AMQPMessage $msg) {
                $this->msgReceived = $msg;
            }, new NullLogger())
        ]);

        // The key is the name of the queue.
        $queue->publish(new Message('hello'));

        $consumerService = new ConsumerService($client, [
            $queue
        ]);

        $consumerService->run(true);

        $this->assertEquals('hello', $this->msgReceived->getBody());
    }

    /**
     * @expectedException \Mouf\AmqpClient\Exception\ConnectionException
     */
    public function testConnectionException()
    {
        // A bug in PHPUnit prevents us for disabling warning to exceptions conversion when processIsolation is set.
        // Sockets are throwing warning before the exception. Hence, the test is failing.
        // Let's skip the test if sockets are enabled.
        if (function_exists('socket_create')) {
            $this->markTestSkipped('Skipping test because of a bug in PHPUnit regarding warning handling');
            return;
        }

        $this->init(1242000042);
        $this->client->getChannel();
    }
}
