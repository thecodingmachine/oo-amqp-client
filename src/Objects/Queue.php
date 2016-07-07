<?php
namespace Mouf\AmqpClient\Objects;

use Mouf\AmqpClient\RabbitMqObjectInterface;
use PhpAmqpLib\Channel\AMQPChannel;

/**
 * @author Marc
 *
 */
class Queue implements RabbitMqObjectInterface{

	/**
	 * 
	 * @var Binding
	 */
	private $source;
	
	/**
	 * Queue
	 * @var string
	 */
	private $queue = '';
	
	/**
	 * Passive
	 * @var bool
	 */
	private $passive = false;
	
	/**
	 * Durable
	 * @var bool
	 */
	private $durable = false;
	
	/**
	 * Exclusive
	 * @var bool
	 */
	private $exclusive = false;
	
	/**
	 * Auto delete
	 * @var bool
	 */
	private $auto_delete = false;
	
	/**
	 * No wait
	 * @var bool
	 */
	private $nowait = false;
	
	/**
	 * Arguments
	 * @var null|array
	 */
	private $arguments = null;
	
	/**
	 * Ticket
	 * @var int
	 */
	private $ticket = null;
	
	/**
	 *
	 * @var Queue
	 */
	private $deadLetterQueue;
	
	/**
	 * Parameter to initialize object only one time
	 * @var bool
	 */
	private $init = false;
	
	/**
	 * Set the source (Binding)
	 * @param Binding $source
	 */
	public function __contruct(Binding $source) {
		$this->source = $source;
	}
	
	/**
	 * Set the dead letter queue
	 * @param Queue $queue
	 */
	public function setDeadLetterQueue(Queue $queue) {
		$this->deadLetterQueue = $queue;
	}

	/**
	 * Get passive
	 * @return bool
	 */
	public function getPassive() {
		return $this->passive;
	}
	
	/**
	 *
	 * @param bool $passive
	 * @return Queue
	 */
	public function setPassive($passive) {
		$this->passive = $passive;
		return $this;
	}
	
	/**
	 * Get durable
	 * @return bool
	 */
	public function getDurable() {
		return $this->durable;
	}
	
	/**
	 * Set durable
	 * @param bool $durable
	 * @return Queue
	 */
	public function setDurable($durable) {
		$this->durable = $durable;
		return $this;
	}
	
	/**
	 * Get exclusive
	 * @return bool
	 */
	public function getExclusive() {
		return $this->exclusive;
	}
	
	/**
	 * Set exclusive
	 * @param bool $exclusive
	 * @return Queue
	 */
	public function setExclusive($exclusive) {
		$this->exclusive = $exclusive;
		return $this;
	}
	
	/**
	 * Get auto_delete
	 * @return bool
	 */
	public function getAutoDelete() {
		return $this->auto_delete;
	}
	
	/**
	 * Set auto_delete
	 * @param bool $auto_delete
	 * @return Queue
	 */
	public function setAutoDelete($auto_delete) {
		$this->auto_delete = $auto_delete;
		return $this;
	}
	
	/**
	 * Get nowait
	 * @return bool
	 */
	public function getNowait() {
		return $this->nowait;
	}
	
	/**
	 * Set nowait
	 * @param bool $nowait
	 * @return Queue
	 */
	public function setNowait($nowait) {
		$this->nowait = $nowait;
		return $this;
	}
	
	/**
	 * Get arguments
	 * @return array|null
	 */
	public function getArguments() {
		return $this->arguments;
	}
	
	/**
	 * Set arguments
	 * @param array $arguments
	 * @return Queue
	 */
	public function setArguments($arguments) {
		$this->arguments = $arguments;
		return $this;
	}
	
	/**
	 * Get ticket
	 * @return int
	 */
	public function getTicket() {
		return $this->ticket;
	}
	
	/**
	 * Set ticket
	 * @param int $ticket
	 * @return Queue
	 */
	public function setTicket($ticket) {
		$this->ticket = $ticket;
		return $this;
	}
	
	
	
	public function init(AMQPChannel $amqpChannel) {
		if(!$this->init) {
			$this->source->init($amqpChannel);
			$this->deadLetterQueue->init($amqpChannel);
			//TODO wip
			//$amqpChannel->queue_declare($this->name, false, true, false, false, false, array('x-dead-letter-exchange' => array('S', $this->exchanger), 'x-max-priority' => ['I', $this->exchanger]));
			
			$this->init = true;
		}
	}


	public function comsume() {
	
	}
	
}