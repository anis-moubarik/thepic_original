<?php
require 'extfunctions.php';
require 'dbinit.php';
session_start();
header("Cache-Control: private, max-age=10800, pre-check=10800");
header("Pragma: private");
header("Expires: " . date(DATE_RFC822,strtotime(" 2 day")));
$yhteys->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$picid;
$reqUrl = $_SERVER['REQUEST_URI'];
if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])){
	header('Last-Modified: '.$_SERVER['HTTP_IF_MODIFIED_SINCE'],true,304);
	exit;
}else{
	if(!empty($_GET)){
		$picid = $_GET['picid'];
	}
	$bits = explode('/', $reqUrl);
	$id = end($bits);
	$picid = $id;	
	//Memcache for visits
	$memcache = new Memcache;
	$memcache->connect('localhost', 11211) or die ("Could not connect");
	
	$q = $yhteys->prepare("SELECT visits FROM image WHERE id=?");
	$q->execute(array($picid));
			
	$file = "ip/ip_".$id.".txt";
	$fp = fopen($file, "a+");
	$ip_list = file($file);
	$visitors = $q->fetchColumn();
			
if (in_array($_SERVER['REMOTE_ADDR']."\n", $ip_list)){
				
}else{
	fwrite($fp, $_SERVER['REMOTE_ADDR']."\n");
	fclose($fp);
	$visitors++;			
	$q = $yhteys->prepare("UPDATE image SET visits=? WHERE id=?");
	$q->execute(array($visitors, $picid));
}	

	
	if($gotten = $memcache->get($picid)){
		$debug =  $picid . "retrieved from memcache <br />";
		//Memcached
		$path = explode("/", $gotten->str_path);
		$file = end($path);
		$visits = $gotten->int_visits;
		$fullurl = "i.thepic.net/".$file;
		$url = "thepic.net/".$gotten->int_id;
		$htmlurl = "<img src=\"http://".$fullurl."\" alt=\"\" title=\"Hosted by thepic.net\" />";
		$bburl = "[IMG]http://".$fullurl."[/IMG]";
		$sizes = getimagesize($gotten->str_path);
		$size = $sizes[0];
		$datediff = $gotten->int_datediff;
	}else{
		$kysely = $yhteys->prepare("SELECT * FROM image WHERE id=?");
		$kysely->execute(array($picid));
		$row = $kysely->fetch();
		
		if($kysely->rowCount() == 0){
			header("location:http://thepic.net");	
		}
		
		$path = explode("/", $row['path']);
		$file = end($path);
		$visits = $row['visits'];
		$fullurl = "i.thepic.net/".$file;
		$url = "thepic.net/".$row['id'];
		$htmlurl = "<img src=\"http://".$fullurl."\" alt=\"\" title=\"Hoster by thepic.net\" />";
		$bburl = "[IMG]http://".$fullurl."[/IMG]";
		$sizes = getimagesize($row['path']);
		$size  = $sizes[0];
		$datediff = $row['datediff'];
		$tmp_object = new stdClass;
		$tmp_object->int_visits = $row['visits'];
		$tmp_object->str_path = $row['path'];
		$tmp_object->int_id = $row['id'];
		$tmp_object->int_datediff = $row['datediff'];
		$memcache->set($picid, $tmp_object, MEMCACHE_COMPRESSED, 28800);
	}
	
	
	
	$diffs = time() - $datediff;
	$submitted = "";
	$diffm = $diffs/60;
	$diffh = $diffm/60;
	$diffd = $diffh/24;
	$diffw = $diffd/7;
	$diffmo = $diffw/4;
	
	if($diffs < 60){
		$submitted	.= "Submitted ".round($diffs)." seconds ago.";
	}else if($diffm < 60){
		if(round($diffm) == 1){
			$submitted .= "Submitted ".round($diffm)." minute ago.";
		}else{
			$submitted .= "Submitted ".round($diffm)." minutes ago.";
		}
	}else if($diffh < 24){
		if(round($diffh) == 1){
			$submitted .= "Submitted ".round($diffh)." hour ago.";
		}else{
			$submitted .= "Submitted ".round($diffh)." hours ago.";
		}
	}else if($diffd < 7){
		if(round($diffd) == 1){
			$submitted .= "Submitted ".round($diffd)." day ago.";
		}else{
			$submitted .= "Submitted ".round($diffd)." days ago.";
		}
	}else if($diffw < 4){
		if(round($diffw) == 1){
			$submitted .= "Submitted ".round($diffw)." week ago.";
		}else{
			$submitted .= "Submitted ".round($diffw)." weeks ago.";
		}
	}else{
		if(round($diffmo) == 1){
			$submitted .= "Submitted ".round($diffmo)." month ago.";
		}else{
			$submitted .= "Submitted ".round($diffmo)." months ago.";
		}
	}
	
	$bw = round(($size /1024)* $visits, 2);
	$bwmega = $bw / 1024;
	$bwgiga = $bwmega / 1024;
	$bws = "";
	
	if($bw < 1024){
		$bws .= $bw." KB Bandwidth";	
	}else if($bwmega < 1024){
		$bws .= round($bwmega, 2). " MB Bandwidth";
	}else if($bwgiga < 1024){
		$bws .= round($bwgiga, 2)." GB Bandwidth";	
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-24039466-2']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<meta name="Project Hosted Image" http-equiv="Content-Type" content="xhtml/text; charset=utf-8" />
<title>Image <?php echo $row['id'];?>: thepic.net</title>
<link href="/assets/up-styles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
</head>
<body>
<script type="text/javascript" src="http://thepic.net/clickheat/js/clickheat.js"></script><noscript><p><a href="http://www.labsmedia.com/clickheat/index.html">Landing page optimization</a></p></noscript><script type="text/javascript"><!--
clickHeatSite = '';clickHeatGroup = encodeURIComponent(window.location.pathname+window.location.search);clickHeatServer = 'http://thepic.net/clickheat/click.php';initClickHeat(); //-->
</script>
<div id="wrapper">
<div id="upload">
<div id="header">
<a href="http://thepic.net"><img src="/assets/logo_1.png"  /></a>
</div>
<div id="colWrapper">
<div id="leftCol">
<div id="share">
<div id="social">
<!-- AddThis Button BEGIN -->
<ul><li title="Tweet"><span class="twitter_custom" onclick="window.open('https://twitter.com/share?url=https://thepic.net/<?php echo $id ?>&via=thepic.net', 'mywindow', 'width=550, height=425')">&nbsp;</span></li>
<li title="Share on Facebook"><span class="fb_custom" onclick="window.open('https://www.facebook.com/sharer.php?u=https://thepic.net/<?php echo $id ?>', 'mywindow', 'width=755, height=425')">&nbsp;</span></li>
<li title="Share on Reddit"><span class="reddit_custom" onclick="window.open('https://reddit.com/submit?url=http://thepic.net/<?php echo $id ?>', 'mywindow', 'width=900, height=720')">&nbsp;</span></li>
<li title="Email"><a href="javascript:void(0)" class="addthis_button_email st_email_custom"><span>&nbsp;</span></a></li>
</ul>
<br />
<script type="text/javascript" src="https://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4e0f2ffd13cae34e"></script>
<!-- AddThis Button END -->
</div>
<form id="links" name="links">
<label for="link">Link</label><br />
<input type="text" name="link" id="link" class="copy" value="<?php echo "http://".$url ?>" />
<br />
<label for="directlink">Direct Link</label>
<input type="text" name="directlink" id="directlink" class="copy" value="<?php echo "http://".$fullurl ?>" />
<br />
<label for="htmlimg">HTML Image</label>
<input type="text" name="htmlimg" id="htmlimg" class="copy" value='<?php echo $htmlurl ?>' />
<br />
<label for="linkedimg">Linked Image BBCode</label>
<input type="text" name="linkedimg" id="htmlimg" class="copy" value="<?php echo $bburl ?>" />

</form>
<g:plusone style="padding-left:5px;" size="medium"></g:plusone>
</div>
</div>
<div id="rightCol">
<a href="<?php echo "http://".$fullurl?>" target="_blank"><img style="width:<?php if($size > 500){echo 500;}else{echo $size;} ?>px; height: auto; border: #191919 3px solid; " src="<?php echo "http://".$fullurl?>" /></a>
<p id="info"><?php echo "<br />".$submitted; ?>
<?php echo "<br />".number_format($visits, 0, ',', ' ')." Views | ".$bws; ?>

</p>
</div>
</div>
<script type="text/javascript">
				$(document).ready(function() {
					$('input[class=copy]').mouseover(function(){
						$(this).select();
					});
					
				});
</script>
<?php

	
include 'bottom.php';
?>