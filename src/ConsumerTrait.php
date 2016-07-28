<?php

namespace Mouf\AmqpClient;

trait ConsumerTrait
{
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
