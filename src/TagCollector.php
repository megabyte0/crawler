<?php


namespace Crawler;


class TagCollector {
//    protected DOMDocument $html;
//    function __construct($html) {
//        $this->html = $html;
//    }

    public static function dumpStructure($html,$depth=0) {
        print str_repeat(' ',$depth*4).($html->nodeName)."\n";
        if ($html && $html->hasChildNodes()){
            foreach ($html->childNodes as $node) {
                self::dumpStructure($node,$depth+1);
            }
        }
    }
}