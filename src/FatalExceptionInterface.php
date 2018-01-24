<?php

namespace Mouf\AmqpClient;

/**
 * When an exception implementing this interface is thrown, the consumer will propagate the exception (so the script
 * will stop). Useful to halt a consumer script if the database connection has timed out.
 */
interface FatalExceptionInterface
{
    /**
     * Exception is propagated if isFatal returns true.
     * @return bool
     */
    public function isFatal();
}
