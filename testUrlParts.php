<?php

require __DIR__ . '/vendor/autoload.php';

$parent = 'http://web.archive.org/web/20170614135730/http://www.zombiferma.ru/';
$urls = [
    '//web.archive.org/web/20170614135730/http://www.zombiferma.ru/',
    '/web/20170614135730/http://www.zombiferma.ru/',
    '/web/20170614135730/http://www.zombiferma.ru/siteroulettre',
    '../www.zombiferma1.ru/',
    '../www.zombiferma1.ru/siteroulettre',
];
//$test = \Crawler\UrlResolver::startsWithAny($url,\Crawler\UrlResolver::PROTOCOLS);
//echo("Test: {$test}\n");
var_dump(\Crawler\UrlResolver::getUrlParts($parent));
var_dump(array_map(function ($url) use ($parent) {
    return \Crawler\UrlResolver::getFullUrl($url, $parent);
}, $urls));
