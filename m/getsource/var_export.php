<?php
//导出数组 
//for($i=1;$i<11;$i++){
	$file = "data/data_porn.php";
	$arrfile = "data/array_porn.php";
	$list = read($file);
	//var_dump($list);
	$content = '<?php $data='.var_export($list,true)."\n?>";
	file_put_contents($arrfile,$content);	

//}

//读取文件
///include "data/array_ks_1.php";
function read($file){
    $list = [];
    $handle = fopen($file, 'r');
    while (($line = fgets($handle)) !== false) {
            array_push($list, trim($line));
       }
    fclose($handle);
    return $list;
}


