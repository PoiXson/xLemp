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

require_once(__DIR__.'/../vendor/autoload.php');


// check compatible os
$os = \PHP_OS;
if($os != 'Linux') {
	throw new \Exception('Sorry, only Linux is currently supported. Contact '.
			'the developer if you\'d like to help add support for another OS.');
}


router::get()
	->run();
