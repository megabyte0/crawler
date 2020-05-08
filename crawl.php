<?php

require __DIR__ . '/vendor/autoload.php';

$url = $argv[1];//?:'http://www.dolekemp96.org/main.htm';
(new \Crawler\Engine)->run($url);
