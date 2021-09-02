<?php
//先把快手地址导入数据库表中ok_source 
//读取表中的url获取视频并保存到本地
set_time_limit(0);
//取得当前所在目录
define('SITE_ROOT', str_replace('index.php', '', str_replace('\\', '/', __FILE__)));
//链接数据库
$conn = new mysqli('localhost', 'root', '123456', 'j8nb');
$conn->query("SET NAMES utf8");	
if ($conn->connect_errno) {
    printf("Connect failed: %s\n", $conn->connect_error);
    exit();
}

for($i=1;$i<10439;$i++){
	$ip = mt_rand(11, 191).".".mt_rand(0, 240).".".mt_rand(1, 240).".".mt_rand(1, 240);   //随机ip
	$header = array(
				'CLIENT-IP:'.$ip,
				'X-FORWARDED-FOR:'.$ip,
			); 
	$query = "SELECT * FROM ok_source WHERE fid=$i ";
	$result = $conn->query($query); 
	$row = $result->fetch_array();
	$fid = $row['fid'];
	$filename = $row['filename'];
	$realurl = $row['url'];
	$m = substr($fid,-1,1);
	if(empty($filename) && !empty($realurl)){
		$file_name = urlShort($fid).".mp4";
		$filename = SITE_ROOT . "/videos/2021/".$m."/".$file_name;
		
		$sv = saveVideo($realurl,$filename,$header);
		if($sv){			
			$videopath   = "/videos/2021/".$m."/".$file_name;
			$sql = "UPDATE `ok_source` SET `filename`='$videopath' WHERE fid=$i";
			$conn->query("SET NAMES utf8");	
			$conn->query($sql);
		}
		
	}
}



//保存视频
function saveVideo($url, $filename="") {

	if($url == "") { return false; }	
	//目录不存在 创建	
	if (!file_exists(dirname($filename))) 
	{
		if (!@mkdir(dirname($filename), 0777)) {
			die($dir_Name."目录创建失败！");
		}
	}
	//file exists
	if(!file_exists($filename)){
		
		$content = curl_https($url,$cookie_file,$header);
		//以流的形式保存图片	
		$write_fd = @fopen($filename,"a");
		@fwrite($write_fd, $content);  //将采集来的远程数据写入本地文件
		@fclose($write_fd);
		return($filename);  //返回文件名
	}

}

function curl_https($url,$header=array()){
    $cookie_file = "";
    $ch = curl_init();
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.57 Safari/536.11");
		
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_HEADER, false);
	//curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: m.22k.im"));
	
   // curl_setopt($ch, CURLOPT_POST, false);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    //curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	//curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file); 
   //curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file); 
	
	 //设置连接超时时间
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,30);
    curl_setopt($ch, CURLOPT_TIMEOUT, 180);
    
	//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
    $response = curl_exec($ch);
    /*
	if($error=curl_error($ch)){
        die($error);
    }
	*/
    curl_close($ch);	
    return $response;
}

//重命名文件名
function urlShort($url){	
    $url= crc32($url);	
    $result= sprintf("%u", $url);
    $sUrl= '';
    while($result>0){
        $s= $result%62;
        if($s>35){
            $s= chr($s+61);
        } elseif($s>9 && $s<=35){
            $s= chr($s+ 55);
        }
        $sUrl.= $s;
        $result= floor($result/62);
    }
	//取前四位
    return substr($sUrl,0,4);
}
