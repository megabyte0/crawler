<?php

require __DIR__ . '/vendor/autoload.php';

$storage = new \Crawler\Storage();
$storage
    ->add(['url'=>'url3','count'=>1])
    ->add(['url'=>'url1','count'=>2])
    ->add(['url'=>'url2','count'=>2])
;
//echo("Hello world, test: {$test}\n");
var_dump($storage->sorted('count','desc'));
