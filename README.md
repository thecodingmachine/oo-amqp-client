[![Latest Stable Version](https://poser.pugx.org/mouf/oo-amqp-client/v/stable)](https://packagist.org/packages/mouf/oo-amqp-client)
[![Total Downloads](https://poser.pugx.org/mouf/oo-amqp-client/downloads)](https://packagist.org/packages/mouf/oo-amqp-client)
[![Latest Unstable Version](https://poser.pugx.org/mouf/oo-amqp-client/v/unstable)](https://packagist.org/packages/mouf/oo-amqp-client)
[![License](https://poser.pugx.org/mouf/oo-amqp-client/license)](https://packagist.org/packages/mouf/oo-amqp-client)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/thecodingmachine/oo-amqp-client/badges/quality-score.png?b=1.1)](https://scrutinizer-ci.com/g/thecodingmachine/oo-amqp-client/?branch=1.1)
[![Build Status](https://travis-ci.org/thecodingmachine/oo-amqp-client.svg?branch=1.1)](https://travis-ci.org/thecodingmachine/oo-amqp-client)
[![Coverage Status](https://coveralls.io/repos/thecodingmachine/oo-amqp-client/badge.svg?branch=1.1&service=github)](https://coveralls.io/github/thecodingmachine/oo-amqp-client?branch=1.1)

About Object Oriented AMQP Client
=================================

This package contains an object oriented wrapper on top of php-amqplib helping work with RabbitMQ in a more object oriented way.

Using this package, *exchanges*, *bindings* and *queues* are represented as objects.
This is useful, especially if you want to inject those objects in your dependency injection container.

Installation
============

```
composer require mouf/oo-amqp-client
```

Usage
=====

Before using this library, you should be accustomed to the AMQP concepts. If you are not, we strongly advise you to start reading the ["AMQP 0-9-1 Model Explained" document from the RabbitMQ documentation](https://www.rabbitmq.com/tutorials/amqp-concepts.html).

Done? Let's get started.

Creating a client
-----------------

The first thing you want to create is a `Client` object. A `Client` represents a connection to RabbitMQ (for those of you used to php-amqplib, it is both a connection AND a channel).

```php
use Mouf\AmqpClient\Client;

$client = new Client(
    $rabbitmq_host,
    $rabbitmq_port,
    $rabbitmq_user,
    $rabbitmq_password,
    $rabbitmq_vhost = '/',
    $rabbitmq_insist = false,
    $rabbitmq_login_method = 'AMQPLAIN',
    $rabbitmq_login_response = null,
    $rabbitmq_locale = 'en_US',
    $rabbitmq_connection_timeout = 3.0,
    $rabbitmq_read_write_timeout = 3.0,
    $rabbitmq_context = null,
    $rabbitmq_keepalive = false,
    $rabbitmq_heartbeat = 0
);
```

Note: the `Client` class exposes a number of useful configuration methods (you do not need to use those if you don't know what they do):

```php
public function setPrefetchSize($prefetchSize);
public function setPrefetchCount($prefetchCount);
public function setAGlobal($aGlobal);
```

Creating an exchange
--------------------

In AMQP, *exchanges* are the objects that receive messages and are in charge of forwarding those messages to queues.
You must therefore define an `Exchange` objects to send messages.

```php
use Mouf\AmqpClient\Objects\Exchange;

$exchange = new Exchange($client, 'exchange_name', 'fanout');
```

When creating an exchange, you pass to the constructor the `Client` object, the exchange name, and the exchange type.

Note: the exchange will *self-register* in the client.

You can apply advanced configuration using configuration methods:

```php
public function setPassive($passive);
public function setDurable($durable);
public function setAutoDelete($autoDelete);
public function setInternal($internal);
public function setNowait($nowait);
public function setArguments($arguments);
public function setTicket($ticket);
```

Creating a queue and a binding
------------------------------

Messages arriving to an exchange are forwarded to a *queue* through a *binding*.

We will now create a queue to store our messages.

```php
use Mouf\AmqpClient\Objects\Queue;

$queue = new Queue($client, 'queue_name', [
    new Consumer(function(AMQPMessage $msg) {
        // Do some stuff with the received message
    })
]);
```

When creating a client, you pass to the constructor the `Client` object, the client name, and an array of `Consumer` objects (actually an array of objects implementing the `ConsumerInterface`).

A `Consumer` object is an object that contains code that will be called each time a message is received.

Note: the queue will *self-register* in the client.

You can apply advanced configuration to your queue using those configuration methods:

```php
public function setPassive($passive);
public function setDurable($durable);
public function setExclusive($exclusive);
public function setAutoDelete($autoDelete);
public function setNoWait($noWait);
public function setArguments($arguments);
public function setTicket($ticket);
public function setDeadLetterExchange(Exchange $exchange);
public function setConfirm($confirm);
public function setConsumerCancelNotify(Queue $consumerCancelNotify);
public function setAlternateExchange(Queue $alternateExchange);
public function setTtl($ttl);
public function setMaxLength($maxLength);
public function setMaxPriority($maxPriority);
```

You will certainly want to use the `setDurable` method if you want your queue to store messages in case of outage of the receiver.

At this point, we have an *exchange*, we have a *queue*, but both are not linked together. We need to **bind** those, using a `Binding` object.

```php
use Mouf\AmqpClient\Objects\Binding;

$binding = new Binding($exchange, $queue);
$client->register($binding);
```

A `Binding` links an exchange to a queue.

**Important**: unlike the `Exchange` and the `Queue`, a `Binding` does not self-register in the client. You have to declare it in the client yourself, using the `Client::register` method.

Done? Let's send and receive messages!

Sending a message
-----------------

In order to send a message, you simply use the `Exchange::publish` method:

```php
$exchange->publish(new Message('your message body'), 'message_key');
// ... and that's it!
```

You may still want to configure a bit more the sending of your message. The `Exchange::publish` method accepts a number of optional arguments:

```php
public function publish(Message $message,
                        string $routingKey,
                        bool $mandatory = false,
                        bool $immediate = false,
                        $ticket = null);
```

Also, the `Message` class can be tweaked with one of those methods:

```php
public function setContentType(string $content_type);
public function setContentEncoding(string $content_encoding);
public function setApplicationHeaders(array $application_headers);
public function setDeliveryMode(int $delivery_mode);
public function setPriority(int $priority);
public function setCorrelationId(string $correlation_id);
public function setReplyTo(string $reply_to);
public function setExpiration(string $expiration);
public function setMessageId(string $message_id);
public function setTimestamp(\DateTimeInterface $timestamp);
public function setType(string $type);
public function setUserId(string $user_id);
public function setAppId(string $app_id);
public function setClusterId(string $cluster_id);
```

Receiving messages
------------------

As we already saw, the first step to receiving message is creating a queue and adding `Consumer` objects to that queue.

We still need to tell PHP to start listening, otherwise, the callbacks in the `Consumer` will never be called.

This can be done using the `ConsumerService` class.

```php
$consumerService = new ConsumerService($client, [
    $queue
]);

$consumerService->run();
```

The `ConsumerService` constructor takes the client in parameter, and the array of queues that must be listened to.

The `ConsumerService::run` method will start listening on arriving messages, in an infinite loop.

Notice that you can use `$consumerService->run(true);` if you want to listen to one message only and return afterward.

Acknowledgements and error handling
-----------------------------------

When you receive a message, an acknowledgement will not be sent before the `Consumer` has finished consuming the message.

If an exception is triggered in the `Consumer`, a `nack` will be sent instead to RabbitMQ.

Note: if your consumer callback throws an exception implementing the `RetryableExceptionInterface` interface, the `nack` message will be sent with the "requeue" flag. The message will be requeued.

Note: if your consumer callback throws an exception implementing the `FatalExceptionInterface` interface, the exception will be propagated by the consumer (hence leading to the crash of the consumer script). Otherwise, consumer will continue processing messages.

Exceptions are logged by default using the error_log function. You can override this behaviour by passing a PSR-3 compliant logger to the `AbstractConsumer` constructor.


Writing your consumer as a class
--------------------------------

So far, to create a consumer, we used the `Consumer` class that takes a callback as first constructor parameter.

As an alternative, you can extend the `AbstractConsumer` class and implement the `onMessageReceived` method:

```php
class MyConsumer extends AbstractConsumer
{
    public function onMessageReceived($msg)
    {
        // Do some stuff.
    }
}
```



Sending a message to a given queue
----------------------------------

If you want to target a special queue and send a message to it directly, you have 2 options.

**Option 1**: create a `DefaultExchange` object and pass the queue name as the key of the message.

```php
use Mouf\AmqpClient\Objects\DefaultExchange;

$exchange = new DefaultExchange($client);
// Simply pass the queue name as the second parameter of "publish".
// Note: you do not need to bind the queue to the exchange. RabbitMQ does this automatically.
$exchange->publish(new Message('your message body'), 'name_of_the_target_queue');
// ... and that's it!
```

**Option 2**: use the `publish` method of the `Queue` object:

```php
use Mouf\AmqpClient\Objects\Queue;

$queue = new Queue($client, 'queue_name', [
    new Consumer(function(AMQPMessage $msg) {
        // Do some stuff with the received message
    })
]);

// Shazam! We are directly sending a message to the queue. No exchange needed!
$queue->publish(new Message('your message body'));
```

Note: these are RabbitMQ specific features and might not work with other AMQP buses.


Symfony console integration
---------------------------

This package comes with 2 Symfony commands that you can use to send and receive messages.

- `Mouf\AmqpClient\Commands\PublishCommand` (`amqp:publish`) allows you to send an arbitrary message on an exchange (read from a file or from STDIN)
- `Mouf\AmqpClient\Commands\ConsumeCommand` (`amqp:consume`) listen to all configured queues


Running the unit tests
======================

This package uses PHPUnit for unit tests.

To run the tests:

```
vendor/bin/phpunit
```

Obviously, you need a running RabbitMQ server to test this package. If you use Docker, you can start one using:

```sh
docker run -p 5672:5672 -p 15672:15672 rabbitmq:management
```
