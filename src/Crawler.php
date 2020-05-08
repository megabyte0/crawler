<?php


namespace Crawler;


use DOMDocument;
use SplQueue;

class Crawler {
    protected array $restrict = [];
    protected array $content = [];
    protected SplQueue $queue;
    protected HttpClient $http;

    function __construct(
        $restrictDomain = NULL,
        $restrictPath = NULL,
        $headers = []
    ) {
        if ($restrictDomain) {
            $this->restrict['domain'] = $restrictDomain;
        }
        if ($restrictPath) {
            $path = explode('/', $restrictPath);
            $this->restrict['path'] = $path[0] === '' ? $path : array_merge(['', $path]);
        }
        $this->queue = new SplQueue();
        if ($headers && count($headers)) {
            $this->http = new HttpClient($headers);
        } else {
            $this->http = new HttpClient();
        }
    }

    public function run($url, $callback,$statistics) {
        $this->queue->enqueue(['url' => $url, 'referrer' => NULL]);
        while (!($this->queue->isEmpty())) {
            $elem = $this->queue->dequeue();
            if ($this->hasRetrieved($elem['url'])) {
                continue;
            }
            $timer = (new Timer())->start();
            $content = $this->retrieve(...array_values($elem));
            $timer->set('retrieve');
            if (!$content) {
                continue;
            }
            $html = $this->process($elem['url'], $content);
            $timer->set('process');
            $callback($elem['url'], $html, $timer);
            $statistics(
                UrlResolver::getUrlParts($elem['url']),
                count($this->content),
                $this->queue->count()
            );
        }
    }

    public function process($url, $content) {
        $html = new DOMDocument();
        libxml_use_internal_errors(true);
        $html->loadHTML($content);
        $newUrls = [];
        foreach ((new DOMNodeRecursiveIterator($html->childNodes))->getRecursiveIterator()
                 as $node) {
            //print $node->nodeName."\n";
            if (strtolower($node->nodeName) !== 'a') {
                continue;
            }
            $href = $node->attributes->getNamedItem('href');
            //var_dump($href);
            if (!$href) {
                continue;
            }
            $href = explode('#', $href->value)[0];
            if ($href === '') {
                continue;
            }
            if (UrlResolver::startsWith($href,'javascript:')) {
                continue;
            }
            $newUrl = UrlResolver::getFullUrl($href, $url);
            $urlParts = UrlResolver::getUrlParts($newUrl);
            if ((array_key_exists('domain', $this->restrict) &&
                    $urlParts['domain'] !== $this->restrict['domain']) ||
                (array_key_exists('path', $this->restrict) &&
                    array_slice($urlParts['path'], 0, count($this->restrict['path']))
                    !== $this->restrict['path'])) {
                //var_dump([$urlParts['domain'],$this->restrict['domain'],
                //    $urlParts['path'],$this->restrict['path']]);
                continue;
            }
            $newUrls[] = $newUrl;
        }
        foreach (array_unique($newUrls) as $newUrl) {
            if (!$this->hasRetrieved($newUrl)) {
                $this->queue->enqueue(['url' => $newUrl, 'referrer' => $url]);
            }
        }
        return $html;
    }

    public function retrieve($url, $referrer = NULL) {
        $content = $this->http->get($url, $referrer);
        $this->content[$url] = $content;
        return $content;
    }

    protected function hasRetrieved($url) {
        return array_key_exists($url, $this->content);
    }

    public static function test() {
        return "Passed";
    }
}