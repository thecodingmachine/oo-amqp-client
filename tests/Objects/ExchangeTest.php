<?php

namespace Mouf\AmqpClient\Objects;

use Mouf\AmqpClient\Client;

class ExchangeTest extends \PHPUnit_Framework_TestCase
{
    public function testExchange()
    {
        global $rabbitmq_host;
        global $rabbitmq_port;
        global $rabbitmq_user;
        global $rabbitmq_password;

        $client = new Client($rabbitmq_host, $rabbitmq_port, $rabbitmq_user, $rabbitmq_password);
        $client->setPrefetchCount(1);

        $exchange = new Exchange($client, 'test_exchange', 'fanout');
        $exchange->publish(new Message('my message'), 'key');
    }
}
