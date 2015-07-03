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

use pxn\xLemp\Commands;

use pxn\phpUtils\ComposerTools;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputArgument;
//use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class router {

	private static $instance = NULL;

	protected $console;



	public static function get() {
		if(self::$instance == NULL)
			self::$instance = new static();
		return self::$instance;
	}
	public function __construct() {
		$this->console = new Application(
				'xLemp',
				ComposerTools::get(__DIR__.'/../')
				->getVersion()
		);

		// compile
		$this->console->register('compile')
				->setDefinition([
						new inputArgument('package', InputArgument::REQUIRED, 'Package name'),
				])
				->setDescription('Starts the build process for a service or program')
				->setCode(function(InputInterface $input, OutputInterface $output) {
					Commands\Compile::get()
							->doCommand(
									$output,
									$input->getArgument('package')
							);
					});

		// build configs
		$this->console->register('build-config')
				->setDefinition([
						new inputArgument('package', InputArgument::REQUIRED, 'Package name'),
				])
				->setDescription('Rebuilds config files for a service or program')
				->setCode(function(InputInterface $input, OutputInterface $output) {
					Commands\RebuildConfig::get()
							->doCommand(
									$output,
									$input->getArgument('package')
							);
					});

	}
	public function run() {
		$this->console->run();
	}



}
