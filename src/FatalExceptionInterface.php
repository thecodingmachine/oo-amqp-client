<?php
/**
 * Created by PhpStorm.
 * User: grp
 * Date: 24/01/2018
 * Time: 19:45
 */

namespace Mouf\AmqpClient;


interface FatalExceptionInterface
{
    /**
     * Exception is propagated if isFatal returns true.
     * @return bool
     */
    public function isFatal();
}