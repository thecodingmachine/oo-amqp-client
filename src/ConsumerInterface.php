<?php

namespace Mouf\AmqpClient;

interface ConsumerInterface
{
    public function onMessage();
}
