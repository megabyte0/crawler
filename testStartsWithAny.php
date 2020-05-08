<?php

require __DIR__ . '/vendor/autoload.php';

$url = 'http://web.archive.org/web/20170614135730/http://www.zombiferma.ru/';
$test = \Crawler\UrlResolver::startsWithAny($url,\Crawler\UrlResolver::PROTOCOLS);
echo("Test: {$test}\n");
