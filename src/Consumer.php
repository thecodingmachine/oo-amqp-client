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
    private $consumerTag;

    /**
     * @var bool
     */
    private $noLocal;

    /**
     * @var bool
     */
    private $noAck;

    /**
     * @var bool
     */
    private $exclusive;

    /**
     * @var bool
     */
    private $noWait;

    /**
     * @var array
     */
    private $arguments;

    /**
     * @var int
     */
    private $ticket;

    /**
     * Consumer constructor.
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
        return $callback($msg);
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
     * @return boolean
     */
    public function isNoLocal()
    {
        return $this->noLocal;
    }

    /**
     * @param boolean $noLocal
     */
    public function setNoLocal($noLocal)
    {
        $this->noLocal = $noLocal;
    }

    /**
     * @return boolean
     */
    public function isNoAck()
    {
        return $this->noAck;
    }

    /**
     * @param boolean $noAck
     */
    public function setNoAck($noAck)
    {
        $this->noAck = $noAck;
    }

    /**
     * @return boolean
     */
    public function isExclusive()
    {
        return $this->exclusive;
    }

    /**
     * @param boolean $exclusive
     */
    public function setExclusive($exclusive)
    {
        $this->exclusive = $exclusive;
    }

    /**
     * @return boolean
     */
    public function isNoWait()
    {
        return $this->noWait;
    }

    /**
     * @param boolean $noWait
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