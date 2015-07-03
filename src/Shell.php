<?php
/**
 * xLemp - Web Server Management Scripts
 *
 * @copyright 2015
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link http://poixson.com/
 */
namespace pxn\xLemp;

use Christiaan\StreamProcess\StreamProcess;
use React\EventLoop\Factory;

class Shell extends StreamProcess {

	protected $loop = NULL;
	protected $timeout = NULL;

	protected $callback;



	public function __construct(
			$command, callable $callback=NULL,
			$workingDir=NULL, array $env=NULL) {
		parent::__construct(
				$command,
				$workingDir,
				$env
		);
		if(empty($command))
			throw new \Exception('Command argument is required');
		$this->callback = $callback;
	}



	public function setCallback(callable $callback) {
		$this->callback = $callback;
		return $this;
	}
	public function setTimeout($sec) {
		$this->timeout = $sec;
	}



	public function run() {
		if($this->loop != NULL)
			throw new \Exception('Shell instance already running');
		if($this->callback == NULL)
			throw new \Exception('Callback argument is required');
		$this->loop = Factory::create();
		$this->loop->addReadStream($this->getReadStream(), function($stream) {
			$data = \fgets($stream);
			// EOF
			if($data === FALSE) {
				$this->loop->stop();
				return;
			}
			if(!empty($data)) {
				\call_user_func(
						$this->callback,
						$data
				);
			}
		});
		\pcntl_signal(\SIGTERM, function() {
			echo "\n\n\nTERM\n\n\n";
			$this->loop->stop();
		});
		// timeout
		if($this->timeout !== NULL) {
			$loop->addPeriodicTimer($this->timeout, function() {
				$this->loop->stop();
			});
		}
		$this->loop->run();
		$this->loop = NULL;
	}



}
