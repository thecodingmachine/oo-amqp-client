<?php

namespace Mouf\AmqpClient;

class Consumer implements ConsumerInterface
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * @var string
     */
    private $consumerTag = '';

    /**
     * @var bool
     */
    private $noLocal = false;

    /**
     * @var bool
     */
    private $noAck = false;

    /**
     * @var bool
     */
    private $exclusive = false;

    /**
     * @var bool
     */
    private $noWait = false;

    /**
     * @var array
     */
    private $arguments = [];

    /**
     * @var int
     */
    private $ticket = null;

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
     * @return callable
     */
    public function callback($msg)
    {
        $callback = $this->callback;

        try {
            $callback($msg);

            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        } catch (RetryableExceptionInterface $e) {
            $msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag'], true, true);
        } catch (\Exception $e) {
            $msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag'], true, false);
        }
    }

    /**
     * @param callable $callback
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
    }

    /**
     * @return string
     */
    public function getConsumerTag()
    {
        return $this->consumerTag;
    }

    /**
     * @param string $consumerTag
     */
    public function setConsumerTag($consumerTag)
    {
        $this->consumerTag = $consumerTag;
    }

    /**
     * @return bool
     */
    public function isNoLocal()
    {
        return $this->noLocal;
    }

    /**
     * @param bool $noLocal
     */
    public function setNoLocal($noLocal)
    {
        $this->noLocal = $noLocal;
    }

    /**
     * @return bool
     */
    public function isNoAck()
    {
        return $this->noAck;
    }

    /**
     * @param bool $noAck
     */
    public function setNoAck($noAck)
    {
        $this->noAck = $noAck;
    }

    /**
     * @return bool
     */
    public function isExclusive()
    {
        return $this->exclusive;
    }

    /**
     * @param bool $exclusive
     */
    public function setExclusive($exclusive)
    {
        $this->exclusive = $exclusive;
    }

    /**
     * @return bool
     */
    public function isNoWait()
    {
        return $this->noWait;
    }

    /**
     * @param bool $noWait
     */
    public function setNoWait($noWait)
    {
        $this->noWait = $noWait;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param array $arguments
     */
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * @return int
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * @param int $ticket
     */
    public function setTicket($ticket)
    {
        $this->ticket = $ticket;
    }
}
