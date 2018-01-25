<?php

namespace Mouf\AmqpClient;

use Mouf\Utils\Log\Psr\ErrorLogLogger;
use Psr\Log\LoggerInterface;

abstract class AbstractConsumer implements ConsumerInterface
{
    use ConsumerTrait;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * AbstractConsumer constructor.
     * @param LoggerInterface|null $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger ?: new ErrorLogLogger();
    }

    public function callback($msg)
    {
        try {
            $this->onMessageReceived($msg);
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        } catch (RetryableExceptionInterface $e) {
            $this->logger->error("Exception caught while consuming message.", [
                'exception' => $e
            ]);
            $msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag'], true, true);
            if ($e instanceof FatalExceptionInterface) {
                throw $e;
            }
        } catch (\Exception $e) {
            $this->logger->error("Exception caught while consuming message.", [
                'exception' => $e
            ]);
            $msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag'], true, false);
            if ($e instanceof FatalExceptionInterface) {
                throw $e;
            }
        }
    }

    abstract public function onMessageReceived($msg);
}
