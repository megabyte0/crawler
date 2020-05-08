<?php


namespace Crawler;


class Storage extends Data {
    public function add($value){
        $this->data[] = $value;
        return $this;//methods chaining
    }

    public function getData(): Data { //maybe this thing is not needed at all
        return $this;
    }
}