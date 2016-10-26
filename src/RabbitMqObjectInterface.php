<?php

namespace Mouf\AmqpClient;

use PhpAmqpLib\Channel\AMQPChannel;

interface RabbitMqObjectInterface
{
    /**
     * Function call to initialize the objecct in RabbitMq.
     *
     * It is the responsibility of the object implementing init to ignore several init calls (only the first one must be accounted for)
     */
    public function init(AMQPChannel $amqpChannel);
}
