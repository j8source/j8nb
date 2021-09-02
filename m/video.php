<?php
//随机调用视频
$frand = mt_rand(1,10);	
$file = "data/array_ks_".$frand.".php";
include $file;

$count = count($data);
$rand =  mt_rand(0,$count);
$url = $data[$rand];
unset($data,$rand);
header("Location: {$url}");

//var_dump($url);
function read($file){
    $list = [];
    $handle = fopen($file, 'r');
    while (($line = fgets($handle)) !== false) {
            array_push($list, trim($line));
       }
    fclose($handle);
    return $list;
}