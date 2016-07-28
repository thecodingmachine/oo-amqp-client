<?php

namespace Mouf\AmqpClient;

class Consumer implements ConsumerInterface
{
    use ConsumerTrait;

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
}
