<?php


namespace Crawler;


class Data {
    protected array $data;
    //const SORT_ASC = 1;
    //const SORT_DESC = -1;
    private array $sortingDirection = [
        'ASC' => 1,
        1 => 1,
        'asc' => 1,
        'desc' => -1,
        -1 => -1,
        'DESC' => -1,
    ];

    //https://www.php.net/manual/en/language.oop5.decon.php
    function __construct($data = []) {
        //parent::__construct();
        $this->data = $data;
    }

    function sorted($key, $direction = 1) {
        $direction = $this->sortingDirection[$direction];
        $keys = [];
        foreach ($this->data as $k => $value) {
            $keys[$k] = $value[$key];
        }
        if ($direction === 1) {
            asort($keys);
        } else {
            arsort($keys);
        }
        $res = [];
        foreach ($keys as $index => $keyValue) {
            $res[] = $this->data[$index];
        }
        return $res;
    }
}