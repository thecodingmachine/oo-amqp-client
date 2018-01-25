<?php

namespace Mouf\AmqpClient;

use Psr\Log\LoggerInterface;

class Consumer extends AbstractConsumer
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * Consumer constructor.
     *
     * @param callable $callback
     * @param LoggerInterface $logger
     */
    public function __construct(callable $callback, LoggerInterface $logger = null)
    {
        parent::__construct($logger);
        $this->callback = $callback;
    }

    /**
     * @param callable $callback
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
    }

    public function onMessageReceived($msg)
    {
        $callback = $this->callback;

        $callback($msg);
    }
}
