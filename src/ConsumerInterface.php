<?php

namespace Mouf\AmqpClient;

interface ConsumerInterface
{
    /**
     * Callback for the consume service call if a mesage is receive.
     */
    public function callback($message);

    /**
     * Consumer tag to listen message
     * By default you can set it to ''.
     */
    public function getConsumerTag();

    /**
     * No local to listen message
     * By default you can set it to false.
     */
    public function isNoLocal();

    /**
     * No ack to listen message. RabbitMq remove the message only if an ack is send
     * By default you can set it to false.
     */
    public function isNoAck();

    /**
     * Exclusive to listen message
     * By default you can set it to false.
     */
    public function isExclusive();

    /**
     * No wait to listen message
     * By default you can set it to false.
     */
    public function isNoWait();

    /**
     * Argument to listen message
     * By default you can set it to [].
     */
    public function getArguments();

    /**
     * Ticket to listen message.
     */
    public function getTicket();
}
