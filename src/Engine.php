<?php


namespace Crawler;


use DateTime;

class Engine {
    public function run($url, $restrictDomain = NULL, $restrictPath = NULL) {
        if (!$restrictDomain) {
            $restrictDomain = UrlResolver::getUrlParts($url)['domain'];
        }
        $storage = new Storage();
        (new Crawler($restrictDomain, $restrictPath))
            ->run($url, function ($currentURL, $html, $timer) use ($storage) {
                $count = 0;
                foreach ((new DOMNodeRecursiveIterator($html->childNodes))->getRecursiveIterator()
                         as $node) {
                    if (strtolower($node->nodeName) === 'img') {
                        ++$count;
                    }
                }
                $timer->set('count');
                return
                    $storage->add(array_merge([
                        'url' => $currentURL,
                        'img' => $count
                    ], $timer->times()));
            }, function ($urlParts, $retrievedCount, $queueSize) {
                print implode('/', array_merge($urlParts['path'],
                        [$urlParts['target']])) . ' ' . ($retrievedCount) . '/' .
                    ($retrievedCount + $queueSize) . "\n";
            });
        Reports::save('report_' . ((new
            DateTime())->format('d.m.Y')) . '.html',
            $storage->getData()->sorted('img', 'desc'));
    }
}