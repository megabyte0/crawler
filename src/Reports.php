<?php


namespace Crawler;


class Reports {
    public static function save($fileName, $data) {
        $fp = fopen($fileName,'wt');
        fwrite($fp,self::htmlWrap($data));
        fclose($fp);
    }

    public static function getUniqueKeys($data) {
        $keyMap=[];
        foreach ($data as $item) {
            foreach ($item as $key => $value) {
                $keyMap[$key]=NULL;
            }
        }
        $keys=[];
        foreach ($keyMap as $key => $null){
            $keys[]=$key;
        }
        return $keys;
    }

    public static function htmlWrap($data) {
        $keys = self::getUniqueKeys($data);
        $thead = '<tr><th>'.implode('</th><th>',$keys).'</th></tr>';
        $tbody = implode("\n",array_map(function ($item) use ($keys) {
            return self::trTd($item,$keys);
        },$data));
        return "<html><head>
<title>Report</title>
</head>
<body>
<table>
<thead>
{$thead}
</thead>
<tbody>
{$tbody}
</tbody>
</table>
</body>
</html>";
    }

    public static function trTd($item,$keys,$td='td') {
        return "<tr><{$td}>".
            implode("</{$td}><{$td}>",array_map(
                function ($key) use ($item) {
                    return array_key_exists($key,$item)?$item[$key]:'';
                },$keys
            ))
            ."</{$td}></tr>";
    }
}