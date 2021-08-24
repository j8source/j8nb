<?php 
header("Content-type:text/html;charset=utf-8");
require 'data/source.php';

//缓存文件的生命期，单位秒，86400秒是一天 20天
define('SITE_ROOT', str_replace('index.php', '', str_replace('\\', '/', __FILE__)));
define('CACHE_LIFE', 86400);
$file = SITE_ROOT."data/movie_list.php";
if(file_exists($file) && time()-filemtime($file)<CACHE_LIFE){
	include $file;	
}else{
	//randon 4
	$listid = array_rand($source,3);		
	for($i=0;$i<count($listid);$i++){
		$vid = $listid[$i];		
		$url  = $source[$vid]['url'];
		$name = $source[$vid]['name'];
		$img  = $source[$vid]['img'];
		$desc = $source[$vid]['desc'];
		$listArr[] = array('vid'=>$vid,'name'=>$name,'img'=>$img,'desc'=>$desc,'url'=>$url);
	}	
	$content = '<?php $listArr='.var_export($listArr,true)."\n?>";
	file_put_contents($file,$content);	
}
//
$vid = trim($_GET['vid']);
$act = trim($_GET['act']);

$desctitle = $act=='tv'?$desctitle="节目简介":$desctitle="剧情简介";
if($act=='mv' && !empty($vid)){	
		$vid  =  intval($vid);
		$url  = $source[$vid]['url'];
		$name = $source[$vid]['name'];
		$img  = $source[$vid]['img'];
		$desc = $source[$vid]['desc'];
		//默认
		$defaultArr = array_values($url);
		$source = $defaultArr[0];
		//seo
		$title       = "《".$name."》"."免费高清在线_宅男电影院_j8nb.com";
		$keywords    = $name."免费观看，".$name."高清在线观看";
		$description = "《".$name."》"."剧情简介:".$desc;	
}
elseif($act=='tv' && !empty($vid)){
	
		$vid  =  intval($vid);
		$url =  $tvArr[$vid]['url'];
		$name = $tvArr[$vid]['name'];
		$img  = $tvArr[$vid]['img'];
		$desc = $tvArr[$vid]['desc'];
		//默认
		$defaultArr = array_values($url);
		$source = $defaultArr[0];
		//seo
		$title       = "《".$name."》"."免费高清在线_宅男电影院_j8nb.com";
		$keywords    = $name."免费观看，".$name."高清在线观看";
		$description = "《".$name."》"."节目简介:".$desc;				
}else{	
	//默认
    $vid =	$listArr[0]['vid'];
	$name = $listArr[0]['name'];
	$img  = $listArr[0]['img'];
	$url  = $listArr[0]['url'];
	$desc = $listArr[0]['desc'];
	$defaultArr = array_values($url);
	$source = $defaultArr[0];
	//seo
	$title       = "宅男电影院-宅男福利视频_午夜剧场_j8nb.com";
	$keywords    = "宅男福利视频，宅男电影院，午夜剧场";
	$description = "宅男电影院是一家提供午夜电影，宅男福利视频的网站，打造安全的宅男天堂。";
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8;" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="copyright" content="j8nb.com" />
    <meta name="viewport" content="width=device-width" />
    <title><?php echo $title;?></title>
    <meta name="keywords" content="<?php echo $keywords;?>" />
    <meta name="description" content="<?php echo $description;?>" />	
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/j8source/nb/css/comm.css" type="text/css" media="screen" />	
	 <link href="https://cdn.jsdelivr.net/npm/video.js@7.5.1/dist/video-js.min.css" rel="stylesheet" />
	 <script src="https://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/video.js@7.5.1/dist/video.min.js"></script>
	 <script>
	
	 function getAddress(){		
		var val = $("#playURL").val();		
		$.ajax({
			type:"POST",
			url:"getSource.php",
			data:"act=<?php echo $act;?>&vid=<?php echo $vid;?>&val="+val,
			success:function(msg){               			
				newAddress(msg);
			}			
		});
	}
	function newAddress(playURL){		
	    videojs("my-video").src(playURL);	
        videojs('my-video').ready(function(){
                var myvideo= this;
                myvideo.play();
            });
	}	
	
	</script>
<style>
body{background: #252525;}
.search{border-bottom: 1px solid #353535;color:#ccc;}
.ascbg{background:#151515;}

.list-box .hat, .news-box .hat{background: #151515;border: 1px solid #353535;}
.list-box .item li {background:#151515;border: 1px solid #353535;}
.list-box .item li a{color:#ddd;}
.list-box .item li a:hover {
	text-decoration:underline;
	background:#353535;
}
.list-box .item {
	border-left:1px solid #353535;
}
.list-box .item li {
	position:relative;
	float:left;
	width:16.66666667%;
	
	box-sizing:border-box;
}
</style>	
</head>  
  <body>
    <div id="top-box">
      <div class="header wrap clearfix">
        <div class="logo">
          <a href="/" title="宅男福利导航">
            <img src="https://cdn.jsdelivr.net/gh/j8source/nb/images/logo.png" /></a>
        </div>
        <div class="header_rt">
          <li>
            <a href="/">首页</a></li>          
          <li>
            <a href="/acg/">动漫</a></li>
          <li>
            <a href="/v/" class="cur">影视</a></li>
		<li>
            <a href="/it/">技术</a></li>	
        </div>
      </div>
    </div>	
    <div class="search wrap"> <a href="#" id="top"></a>正在上映&nbsp;:&nbsp;&nbsp;<b><?php echo $name;?></b></div>
     <div class="container">
		<div class="movie">
		 <div class="movie-intro"><span><?php echo $desctitle." : ".$desc;?></span></div>
		 <div id="video-box">
		 <video id="my-video" class="video-js vjs-big-play-centered vjs-fluid"  controls preload="auto"  poster="<?php echo $img;?>"  data-setup="{}">
			<source src="<?php echo $source;?>" type="application/x-mpegURL" />			
			<p class="vjs-no-js">  </p>
		  </video>
		  </div>
		  <div class="select">
			<select onchange="getAddress();" id="playURL">
			<?php
				$i = 1;
				foreach($url as $key=>$value){
					echo "<option value=\"$key\">线路$i</option>\n\r";
					$i++;
				}
			?>	
			</select>
			</div>
		</div>
		<div class="movie_list">
			<header><h3>今日排片</h3></header>
			<div class="top-show wrap">
			<div class="rtp"><a href="/redirect/?url=https://www.uwuxiu.com/H/7/WN46.html" target="_blank"> <img src="https://ae01.alicdn.com/kf/Ue0377046cfa445f598eb88cb92530f10R.jpg" alt="3AGirL AAA女郎 第28集 网袜蕾丝：盈盈" /></a><div class="bg"></div><p>3AGirL AAA女郎 第28集 网袜蕾丝：盈盈</p></div>
			<?php
			foreach($listArr as $value){				
				echo "<div class=\"rtp\"><a href=\"?act=mv&vid=".$value['vid']."\"> <img src=\"".$value['img']."\" alt=\"".$value['name']."\" /></a>
				<div class=\"bg\"></div><p>".$value['name']."</p></div>";
			}
			?>
			</div>	
		</div>
		</div><!----end container -->
	<!---电视直播--->
	<!-----
	<div class="index-content wrap">      
	
      <div class="list-box list-index">
        <div class="hat">
          <h3 class="ti ascbg">
            <i class="fa fa-bars"></i>
            电视直播</h3>
          <ul></ul>
       
        </div>
        <div class="item">
          <ul class="clearfix">
		  <li><a  href="?act=tv&vid=100">Music Channel</a></li>
		  <li><a  href="?act=tv&vid=101">News Channel</a></li>             
			 <li>
              <a  href="?act=tv&vid=104">周星驰</a></li>
            <li>
              <a  href="?act=tv&vid=105">周润发</a></li>
            <li>
              <a  href="?act=tv&vid=103">刘德华</a></li>
            <li>
              <a  href="?act=tv&vid=106">顶级科幻</a></li>
			
          </ul>
        </div>
      </div>
	</div> --->
	
    <div id="footer">
      <div class="foot-wrap wrap">
       <div style="color:#ff9900;margin-top:10px;">温馨提示：不要轻信视频上字幕广告，以免上当受骗</div>	  
       <p>
          <a href="/about.html#a1">关于我们</a>&nbsp;|&nbsp;<a href="/about.html#a2">免责声明</a>&nbsp;|&nbsp; <a href="/about.html#a3">联系我们</a>&nbsp;|&nbsp; <a href="/about.html#a4">源码下载</a>
          <br />&copy;2018&nbsp;<a href="https://www.j8nb.com" title="Powered by j8nb.com">j8nb.com</a>&nbsp;All rights reserved.
		</p>
      </div>
    </div>
	<div style="display:none;">
	 <script src="/js/stat.js"></script>
		
		</div>
  </body>
</html>
