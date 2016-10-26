<?php

namespace Mouf\AmqpClient\Commands;

use Mouf\AmqpClient\ConsumerService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ConsumeCommand extends Command
{
    /**
     * @var ConsumerService
     */
    private $consumerService;

    /**
     * ConsumeCommand constructor.
     */
    public function __construct(ConsumerService $consumerService, $commandName = 'amqp:consume', $description = null)
    {
        parent::__construct($commandName);
        $this->setDescription($description ?: 'Listen to messages from the AMQP bus (RabbitMQ)');
        $this->consumerService = $consumerService;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->addOption('onlyone', 'o', InputOption::VALUE_NONE, 'Receives only one message and stop listening');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->consumerService->run($input->getOption('onlyone'));
    }
}
