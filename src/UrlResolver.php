<?php


namespace Crawler;


class UrlResolver {
    const FULL_URL = 0;
    const RELATIVE_PROTOCOL = 1;
    const RELATIVE_DOMAIN = 2;
    const RELATIVE = 3;
    const RELATIVE_DIRECTORY = 5;
    const PROTOCOLS = [
        'http://',
        'file://',
        'https://',
    ];
    public static function getFullUrl($url, $parent = NULL) {
        if (!$parent) {
            return $url;
        }
        $parentParts = self::getUrlParts($parent);
        $urlParts = self::getUrlParts($url);
        $path = $urlParts['path'];
        if ($path[0]!==''){//relative path
            $urlPath = $urlParts['path'];
            $resPath = $parentParts['path'];
            foreach ($urlPath as $urlDirectory){
                if ($urlDirectory==='.') {
                    continue;
                }
                if ($urlDirectory==='..') {
                    $parentPathCount = count($resPath);
                    if ($parentPathCount) {
                        $resPath=array_slice($resPath,0,$parentPathCount-1);
                    }
                    continue;
                }
                $resPath[]=$urlDirectory;
            }
        } else {
            $resPath = $path;
        }
        return (
            ($urlParts['protocol']?:$parentParts['protocol']).
            ($urlParts['domain']?:$parentParts['domain']).
            (implode('/',$resPath)).
            '/'.($urlParts['target'])
        );
    }

    public static function getUrlParts($url, $urlType = self::FULL_URL) {
        $res=[];
        $res['protocol']=self::startsWithAny($url,self::PROTOCOLS)?:NULL;
        $hasDomain = true;
        if ($res['protocol']) {
            $url=substr($url,strlen($res['protocol']));
        } elseif (self::startsWith($url,'//')) {
            $url=substr($url,strlen('//'));
        } else {
            $hasDomain = false;
        }
        $parts = explode('/',$url);
        if ($hasDomain) {
            $res['domain'] = $parts[0];
            $parts[0] = '';
        } else {
            $res['domain'] = NULL;
        }
        $res['path'] = array_slice($parts,0,count($parts)-1);
        $res['target'] = $parts[count($parts)-1];
        return $res;
    }

    public static function getUrlType($url) {
        switch (true) {
            case (self::startsWithAny($url,self::PROTOCOLS)):
                return self::FULL_URL;
            case (self::startsWith($url,'//')):
                return self::RELATIVE_PROTOCOL;
            case (self::startsWith($url,'/')):
                return self::RELATIVE_DOMAIN;
            case (self::startsWith($url,'.')):
                return self::RELATIVE;
            default:
                return self::RELATIVE_DIRECTORY;
        }
    }

    public static function startsWith($str, $start) {
        return substr($str,0,strlen($start)) === $start;
    }

    public static function startsWithAny($str, $starts) {
        return array_reduce($starts, function ($res, $start) use ($str) {
            return $res ?: (self::startsWith($str, $start) ? $start : $res);
        }, false);
    }
}