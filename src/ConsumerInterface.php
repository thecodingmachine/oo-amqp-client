<?php

namespace Mouf\AmqpClient;

interface ConsumerInterface
{
    /**
     * Consumer tag to listen message
     * By default you can set it to ''.
     */
    public function getConsumerTag();

    /**
     * No local to listen message
     * By default you can set it to false.
     */
    public function getNoLocal();

    /**
     * No ack to listen message. RabbitMq remove the message only if an ack is send
     * By default you can set it to false.
     */
    public function getNoAck();

    /**
     * Exclusive to listen message
     * By default you can set it to false.
     */
    public function getExclusive();

    /**
     * No wait to listen message
     * By default you can set it to false.
     */
    public function getNoWait();

    /**
     * Callback for the consume service call if a mesage is receive.
     */
    public function callback();

    /**
     * Argument to listen message
     * By default you can set it to [].
     */
    public function getArguments();

    /**
     * Tiket to listen message
     * By default you can set it to [].
     */
    public function getTicket();
}
