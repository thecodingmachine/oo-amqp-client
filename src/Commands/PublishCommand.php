<?php

namespace Mouf\AmqpClient\Commands;

use Mouf\AmqpClient\Client;
use Mouf\AmqpClient\Objects\Message;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PublishCommand extends Command
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('amqp:publish')
            ->setDescription('Send a message on a AMQP (RabbitMQ) bus.')
            ->setHelp('Reads a message from STDIN and sends it on the bus to the given exchange.')
            ->addArgument('exchange', InputArgument::REQUIRED, 'the target exchange')
            ->addArgument('filename', InputArgument::OPTIONAL, 'the file to send on the bus (if not passed, data is read from STDIN)')
            ->addOption('routing_key', 'k', InputOption::VALUE_REQUIRED, 'the routing key of the message', 'key')
            ->addOption('mandatory', 'm', InputOption::VALUE_NONE, 'set the mandatory bit on the AMQP message')
            ->addOption('immediate', 'i', InputOption::VALUE_NONE, 'set the immediate bit on the AMQP message')
            ->addOption('ticket', 't', InputOption::VALUE_REQUIRED, 'set the ticket number');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $channel = $this->client->getChannel();

        $filename = $input->getArgument('filename');
        if ($filename = $input->getArgument('filename')) {
            $contents = file_get_contents($filename);
        } elseif (0 === ftell(STDIN)) {
            $contents = '';
            while (!feof(STDIN)) {
                $contents .= fread(STDIN, 1024);
            }
        } else {
            throw new \RuntimeException('Please provide a filename or pipe message to STDIN.');
        }

        $routingKey = $input->getOption('routing_key');
        $mandatory = $input->getOption('mandatory');
        $immediate = $input->getOption('immediate');
        $ticket = $input->getOption('ticket');

        $channel->basic_publish((new Message($contents))->toAMQPMessage(), $input->getArgument('exchange'), $routingKey, $mandatory, $immediate, $ticket);
    }
}
