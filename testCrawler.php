<?php

require __DIR__ . '/vendor/autoload.php';

$url = 'http://www.dolekemp96.org/main.htm';
(new \Crawler\Engine)->run($url,'www.dolekemp96.org');
//$test = \Crawler\Crawler::test();
//echo("Hello world, test: {$test}\n");
