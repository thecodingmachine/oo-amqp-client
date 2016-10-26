<?php

namespace Mouf\AmqpClient;

/**
 * When an exception implementing this interface is throwed, the message will be left in the message queue.
 */
interface RetryableExceptionInterface
{
}
