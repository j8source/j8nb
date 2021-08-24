<?php
session_start();
require 'data/source.php';
$vid = intval(trim($_REQUEST['vid']));
$val = intval(trim($_REQUEST['val']));
$act = trim($_REQUEST['act']);
if($act=='tv'){ $sourceArr = $tvArr;}else{$sourceArr = $source;}
//referer
$referer =  $_SERVER['HTTP_REFERER']; 
$pos = strpos($referer,"/v/");
if ($pos === false) {	
	header("Location:/404.html");
	exit;	
}

//SESSION 
if(!empty($vid)){
	if($val=='0'){
		$url  = $sourceArr[$vid]['url'];	
		$defaultArr = array_values($url);
		$default_url = $defaultArr[0];
		//header("Location: $default_url");
		echo $default_url;
		exit;
	}else{
		echo $sourceArr[$vid]['url'][$val];
		exit;
	}	
}
else{
	header("Location:404.html");
	exit;
}
