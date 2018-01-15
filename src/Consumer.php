<?php

namespace Mouf\AmqpClient;

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
     */
    public function __construct(callable $callback)
    {
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
