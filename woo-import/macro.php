<?php

date_default_timezone_set("Asia/Karachi");

require 'vendor/autoload.php';


define('ALLOW_INLINE_LINE_BREAKS', true);

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Processor\PsrLogMessageProcessor;

$formatter = new LineFormatter(null, null, ALLOW_INLINE_LINE_BREAKS);

$handler = new StreamHandler('log.txt');
$handler->setFormatter($formatter);

$logger = new Logger('test');

$logger->pushHandler($handler);
$logger->pushProcessor(new PsrLogMessageProcessor);

$logger->info('Foo');
$logger->error('Bar');

gadget();

function gadget(){
	global $logger;
	$logger->error('Barter');
}


?>