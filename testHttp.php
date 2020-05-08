<?php

require __DIR__ . '/vendor/autoload.php';

$http = new \Crawler\HttpClient();
$content = $http->get('http://web.archive.org/web/20170614135730/http://www.zombiferma.ru/');
$length = strlen($content);
$contentTruncated = substr($content, 0, 200);
echo("Length: {$length}\nContent: {$contentTruncated}...\n");
