<?php

namespace Mouf\AmqpClient;

abstract class AbstractConsumer implements ConsumerInterface
{
    use ConsumerTrait;

    public function callback($msg)
    {
        try {
            $this->onMessageReceived($msg);

            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        } catch (RetryableExceptionInterface $e) {
            $msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag'], true, true);
        } catch (\Exception $e) {
            $msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag'], true, false);
        }
    }

    abstract public function onMessageReceived($msg);
}
