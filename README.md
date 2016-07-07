[![Latest Stable Version](https://poser.pugx.org/mouf/oo-amqp-client/v/stable)](https://packagist.org/packages/mouf/oo-amqp-client)
[![Total Downloads](https://poser.pugx.org/mouf/oo-amqp-client/downloads)](https://packagist.org/packages/mouf/oo-amqp-client)
[![Latest Unstable Version](https://poser.pugx.org/mouf/oo-amqp-client/v/unstable)](https://packagist.org/packages/mouf/oo-amqp-client)
[![License](https://poser.pugx.org/mouf/oo-amqp-client/license)](https://packagist.org/packages/mouf/oo-amqp-client)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/thecodingmachine/oo-amqp-client/badges/quality-score.png?b=1.0)](https://scrutinizer-ci.com/g/thecodingmachine/oo-amqp-client/?branch=1.0)
[![Build Status](https://travis-ci.org/thecodingmachine/oo-amqp-client.svg?branch=1.0)](https://travis-ci.org/thecodingmachine/oo-amqp-client)
[![Coverage Status](https://coveralls.io/repos/thecodingmachine/oo-amqp-client/badge.svg?branch=1.0&service=github)](https://coveralls.io/github/thecodingmachine/oo-amqp-client?branch=1.0)

About Object Oriented AMQP Client
=================================

This package contains an object oriented wrapper on top of php-amqplib helping work with RabbitMQ in a more object oriented way.

Using this package, *exchanges*, *bindings* and *queues* are represented as objects.
This is useful, especially if you want to inject those objects in your dependency injection container.

Installation
============

```
composer require mouf/oo-amqp-lib
```

Usage
=====

TODO


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
