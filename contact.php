<?php

if(!empty($_POST)){
	$err = "";
	
	if(!checkLen("name")){
		$err .= "Please state your name. ";
	}
	if(!checkLen("email")){
		$err .= "Please state your email. ";	
	}else if(!checkEmail($_POST['email'])){
		$err .= "Please give a valid email. ";	
	}
	if(!checkLen("message")){
		$err .= "Say Something!";	
	}
	
	$emailAddress = 'anis.moubarik@gmail.com';
	require("scripts/phpmailer/class.phpmailer.php");

	$mail = new PHPMailer();
	
	$msg=
	'Name:	'.$_POST['name'].'<br />
	Email:	'.$_POST['email'].'<br />
	IP:	'.$_SERVER['REMOTE_ADDR'].'<br /><br />
	
	Message:<br /><br />
	
	'.nl2br($_POST['message']).'
	
	';
	
	if(empty($err)){
		$mail = new PHPMailer();
		$mail->IsMail();
		
		$mail->AddReplyTo($_POST['email'], $_POST['name']);
		$mail->AddAddress($emailAddress);
		$mail->SetFrom($_POST['email'], $_POST['name']);
		$mail->Subject = "A new ".mb_strtolower($_POST['subject'])." from ".$_POST['name']." | contact form feedback";
		
		$mail->MsgHTML($msg);
		
		$mail->Send();
		echo "<h3 class=\"sent\">sent!</h3>";
	}else{
		echo "<h3 class=\"err\">".$err."</h3>";	
	}
}

function checkLen($str,$len=1)
{
	return isset($_POST[$str]) && mb_strlen(strip_tags($_POST[$str]),"utf-8") > $len;
}

function checkEmail($str)
{
	return preg_match("/^[\.A-z0-9_\-\+]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z]{1,4}$/", $str);
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
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/themes/start/jquery-ui.css" rel="stylesheet" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/jquery-ui.min.js" type="text/javascript"></script>
<meta name="Project Hosted Image" http-equiv="Content-Type" content="xhtml/text; charset=utf-8" />
<title>thepic.net - Contact the developer</title>
<meta name="Keywords" content="thepic, contact form, contact" />
<meta name="Description" content="Thepic.net contact form, you can contact the developer from here" />
<link href="assets/styles.css" rel="stylesheet" type="text/css" />
<link href="assets/form.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="assets/favicon.ico"/>

</head>
<body>
<script type="text/javascript" src="http://thepic.net/clickheat/js/clickheat.js"></script><noscript><p><a href="http://www.labsmedia.com/clickheat/index.html">Landing page optimization</a></p></noscript><script type="text/javascript"><!--
clickHeatSite = '';clickHeatGroup = encodeURIComponent(window.location.pathname+window.location.search);clickHeatServer = 'http://thepic.net/clickheat/click.php';initClickHeat(); //-->
</script>
<script type="text/javascript" src="scripts/jquery.js"></script>
<script type="text/javascript" src="scripts/form.js"></script>
<div id="wrapper">
<div id="upload">
<div id="header">
<a href="http://thepic.net"><img src="assets/logo_1.png" alt="header" /></a>
</div>
  <div id="contact_form">
	<h1>Contact Me!</h1>
    <p>Feel free to send your questions, bug reports, comments or suggestions to me through this form, you can also try to hit me at admin[a]thepic.net</p><br />
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="contact_form">
    	<h2>Name: <span class="error" id="name_error">Please state your name</span></h2>
        <input name="name" id="name" type="text" />
        <h2>Subject</h2>
        <select name="subject" required>
        <option value="Question">Question</option>
        <option value="Partnership">Partnership</option>
        <option value="Bug">Bug Report</option>
        <option value="Other">Other</option>
        </select>
        <h2>Email: <span class="error" id="email_error">Please give a valid e-mail</span></h2>
        <input type="email" id="email" name="email" required/>
        <h2>Message: <span class="error" id="message_error">Say something!</span></h2>
        <textarea id="message" rows="10" cols="20" style="width: 75%" id="message" name="message" class="required" required></textarea><br />
        <input class="button-medium" type="submit" value="Submit" />        
    </form>
  </div>
  
<?php
include 'bottom.php';
?>