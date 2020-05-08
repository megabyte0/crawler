<?php


namespace Crawler;


class Timer {
    private array $time;

    function __construct() {
        $this->time = [];
    }

    public function set($key) {
        $this->time[] = ['what' => $key, 'when' => microtime(true)];
    }

    public function start() {
        $this->set('start');
        return $this;//methods chaining
    }

    public function times(): array {
        $times = [];
        for ($i = count($this->time) - 1; $i > 0; --$i) {
            $times[] = [
                'what' => $this->time[$i]['what'],
                'length' => $this->time[$i]['when'] - $this->time[$i - 1]['when'],
            ];
        }
        $res = [];
        for ($i = count($times) - 1; $i >= 0; --$i) {
            $res[$times[$i]['what']] = $times[$i]['length'];
        }
        return $res;
    }
}