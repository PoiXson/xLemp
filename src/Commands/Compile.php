<?php
/**
 * xLemp - Web Server Management Scripts
 *
 * @copyright 2015
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link http://poixson.com/
 */
namespace pxn\xLemp\Commands;

use Symfony\Component\Console\Output\OutputInterface;

class Compile implements Command {



	public static function get() {
		return new static();
	}
	public function __construct() {
		echo 'WORKS!!!!!!!!!!!!!!';
	}



	public function doCommand(OutputInterface $output, $package) {
		echo "\n\n\nDo Compile!!!....\n\n\n";
		echo $package."\n\n\n\n\n\n\n\n\n\n\n\n";
	}



}
