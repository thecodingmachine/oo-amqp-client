<?php

namespace Mouf\AmqpClient;

use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Connection\AMQPSocketConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Channel\AMQPChannel;
use Mouf\AmqpClient\Exception\ConnectionException;
use PhpAmqpLib\Exception\AMQPIOException;

class Client
{
    /**
     * RabbitMq host.
     *
     * @var string
     */
    private $host;

    /**
     * RabbitMq port.
     *
     * @var string
     */
    private $port;

    /**
     * RabbitMq user.
     *
     * @var string
     */
    private $user;

    /**
     * RabbitMq password.
     *
     * @var string
     */
    private $password;

    /**
     * It's for QOS prefetch-size http://www.rabbitmq.com/amqp-0-9-1-reference.html#basic.qos.
     *
     * @var int
     */
    private $prefetchSize = null;

    /**
     * It's for QOS prefetch-count http://www.rabbitmq.com/amqp-0-9-1-reference.html#basic.qos.
     *
     * @var int
     */
    private $prefetchCount = null;

    /**
     * It's for QOS global http://www.rabbitmq.com/amqp-0-9-1-reference.html#basic.qos.
     *
     * @var int
     */
    private $aGlobal = null;

    /**
     * RabbitMq connection.
     *
     * @var AbstractConnection
     */
    private $connection = null;

    /**
     * RabbitMq channel.
     *
     * @var \AMQPChannel
     */
    private $channel = null;

    /**
     * List of RabbitMq object.
     *
     * @var RabbitMqObjectInterface[]
     */
    private $rabbitMqObjects = [];

    /**
     * RabbitMq virtual host
     *
     * @var string
     */
    private $vhost;

    /**
     * RabbitMq insist
     *
     * @var bool
     */
    private $insist;

    /**
     * RabbitMq login method
     *
     * @var string
     */
    private $login_method;

    /**
     * RabbitMq login response
     *
     * @var null
     */
    private $login_response;

    /**
     * RabbitMq locale
     *
     * @var string
     */
    private $locale;

    /**
     * RabbitMq connection timeout
     *
     * @var float
     */
    private $connection_timeout;

    /**
     * RabbitMq read write timeout
     *
     * @var float
     */
    private $read_write_timeout;

    /**
     * RabbitMq context
     *
     * @var null
     */
    private $context;

    /**
     * RabbitMq keep a live
     *
     * @var bool
     */
    private $keepalive;

    /**
     * RabbitMq heartbeat
     *
     * @var int
     */
    private $heartbeat;

    /**
     * Client constructor.
     * @param $host
     * @param $port
     * @param $user
     * @param $password
     * @param string $vhost
     * @param bool $insist
     * @param string $login_method
     * @param null $login_response
     * @param string $locale
     * @param float $connection_timeout
     * @param float $read_write_timeout
     * @param null $context
     * @param bool $keepalive
     * @param int $heartbeat
     */
    public function __construct(
        $host,
        $port,
        $user,
        $password,
        $vhost = '/',
        $insist = false,
        $login_method = 'AMQPLAIN',
        $login_response = null,
        $locale = 'en_US',
        $connection_timeout = 3.0,
        $read_write_timeout = 3.0,
        $context = null,
        $keepalive = false,
        $heartbeat = 0)
    {
        $this->host = $host;
        $this->port = ($port !== null) ? $port : 5672;
        $this->user = $user;
        $this->password = $password;
        $this->vhost = $vhost;
        $this->insist = $insist;
        $this->login_method = $login_method;
        $this->login_response = $login_response;
        $this->locale = $locale;
        $this->connection_timeout = $connection_timeout;
        $this->read_write_timeout = $read_write_timeout;
        $this->context = $context;
        $this->keepalive = $keepalive;
        $this->heartbeat = $heartbeat;
    }

    /**
     * Get prefetch size for QOS.
     */
    public function getPrefetchSize()
    {
        return $this->prefetchSize;
    }

    /**
     * Set prefetch size
     * It's for QOS prefetch-size http://www.rabbitmq.com/amqp-0-9-1-reference.html#basic.qos.
     *
     * @param int $prefetchSize
     * @return Client
     */
    public function setPrefetchSize($prefetchSize)
    {
        $this->prefetchSize = $prefetchSize;

        return $this;
    }

    /**
     * Get prefetch count for QOS.
     */
    public function getPrefetchCount()
    {
        return $this->prefetchCount;
    }

    /**
     * Set prefetch size
     * It's for QOS prefetch-size http://www.rabbitmq.com/amqp-0-9-1-reference.html#basic.qos.
     *
     * @param int $prefetchCount
     * @return Client
     */
    public function setPrefetchCount($prefetchCount)
    {
        $this->prefetchCount = $prefetchCount;

        return $this;
    }

    /**
     * Get a global for QOS.
     */
    public function getAGlobal()
    {
        return $this->aGlobal;
    }

    /**
     * Set global
     * It's for QOS prefetch-size http://www.rabbitmq.com/amqp-0-9-1-reference.html#basic.qos.
     *
     * @param int $aGlobal
     * @return Client
     */
    public function setAGlobal($aGlobal)
    {
        $this->aGlobal = $aGlobal;

        return $this;
    }

    /**
     * Set RabbitMq object.
     *
     * @param RabbitMqObjectInterface[] $rabbitMqObjects
     */
    public function setRabbitMqObjects(array $rabbitMqObjects)
    {
        $this->rabbitMqObjects = $rabbitMqObjects;
    }

    public function register(RabbitMqObjectInterface $object)
    {
        if (!in_array($object, $this->rabbitMqObjects, true)) {
            $this->rabbitMqObjects[] = $object;
        }
    }

    /**
     * Connection to the RabbitMq service with AMQPStreamConnection.
     *
     * @return AMQPChannel
     * @throws ConnectionException
     * @throws \ErrorException
     */
    public function getChannel()
    {
        if (!$this->connection) {
            try {
                if (function_exists('socket_create')) {
                    $this->connection = new AMQPStreamConnection(
                        $this->host,
                        $this->port,
                        $this->user,
                        $this->password,
                        $this->vhost,
                        $this->insist,
                        $this->login_method,
                        $this->login_response,
                        $this->locale,
                        $this->connection_timeout,
                        $this->read_write_timeout,
                        $this->context,
                        $this->keepalive ,
                        $this->heartbeat
                    );
                } else {
                    $this->connection = new AMQPStreamConnection(
                        $this->host,
                        $this->port,
                        $this->user,
                        $this->password,
                        $this->vhost,
                        $this->insist,
                        $this->login_method,
                        $this->login_response,
                        $this->locale,
                        $this->connection_timeout,
                        $this->read_write_timeout,
                        $this->context,
                        $this->keepalive ,
                        $this->heartbeat
                    );
                }
            } catch (\ErrorException $e) {
                /* We are trying to catch the exception when the connection if refused */
                if (preg_match("/.*unable to connect.*Connection refused.*/", $e->__toString())) {
                    throw new ConnectionException("Cannot create the connection", 404, $e);
                }
                throw $e;
            } catch (AMQPIOException $e) {
                throw new ConnectionException("Cannot create the connection", 404, $e);
            }
            $this->channel = $this->connection->channel();

            if ($this->prefetchSize !== null || $this->prefetchCount !== null || $this->aGlobal !== null) {
                $this->channel->basic_qos($this->prefetchSize, $this->prefetchCount, $this->aGlobal);
            }

            foreach ($this->rabbitMqObjects as $rabbitMqObject) {
                $rabbitMqObject->init($this->channel);
            }
        }

        return $this->channel;
    }

    /**
     * Returns the list of registered queues.
     *
     * @return QueueInterface[]
     */
    public function getQueues()
    {
        return array_filter($this->rabbitMqObjects, function (RabbitMqObjectInterface $object) {
            return $object instanceof QueueInterface;
        });
    }
}
