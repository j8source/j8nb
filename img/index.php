<?php
include 'onedrive.php';

$file = str_replace('uploads','',trim($_GET['path']));
if(!empty($file)){
	$realurl = $data[$file];
	header("Location:$realurl");
}

?>
