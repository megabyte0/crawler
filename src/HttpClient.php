<?php


namespace Crawler;


class HttpClient {
    protected array $headers;

    function __construct($headers = [
        'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:77.0) Gecko/20100101 Firefox/77.0',
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
        'Accept-Language' => 'en-GB,en;q=0.5',
        'Accept-Encoding' => 'gzip, deflate',
        'Upgrade-Insecure-Requests' => '1',
        'Cache-Control' => 'max-age=0',
    ]) {
        $this->headers = $headers;
    }

    function get($url, $referrer = NULL) {
        $headers = array_merge(array(), $this->headers);//https://stackoverflow.com/a/11523748
        if ($referrer) {
            $headers['Referer'] = $referrer;
        }
        $header = [];
        foreach ($headers as $key => $value) {
            $header[] = $key . ': ' . $value;
        }
        //https://stackoverflow.com/a/3032658
        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => implode("\r\n", $header),
            )
        );
        $context = stream_context_create($opts);
        return file_get_contents($url, false, $context);
    }
}