<?php
	session_start();
	require 'dbinit.php';
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Admin page</title>
<link href="assets/adminstyles.css" rel="stylesheet" type="text/css" />
</head>

<body><?php

if(!session_is_registered(username)){
	//Some simple/ugly forms for login.
	echo "<form name=\"login\" action=\"adm_login.php\" method=\"post\">";
	echo "<label>Username:</label>";
	echo "<input type=\"text\" name=\"username\" />";
	echo "<label>Password:</label>";
	echo "<input type=\"password\" name=\"password\" />";
	echo "<input type=\"submit\" name=\"submit\" />";
	echo "</form>";
}else{
	//Delete all and logout buttons
	echo "<form id='delall' name='delall' method='post' action='delallsure.php'>";
	echo "<input type='submit' name='submit' id='submit' value='Delete All' />";
	echo "</form>";
	echo "<form id='logout' name='logout' method='post' action='logout.php'>";
	echo "<input type='submit' name='logout1' id='logout1' value='Logout' />";
	echo "</form>";
	echo "<form id='delselected' name='delselected' method='post' action='delselected.php'>";
	echo "<input type='submit' name='submit' value='Delete selected' />";
	echo "</form>";
	
	//Initial query to see how many images we have, downwards the code is in need of 
	//severe optimization, now "it just works"
	$yhteys->SetAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$kysely = $yhteys->prepare("SELECT path FROM image ORDER BY datediff DESC");
	$kysely->execute();
	$count = $kysely->rowCount();
	$kysely->closeCursor();
	  
	$totalRows = $count;
	$perPage = 8;
	$pages = ceil($totalRows / $perPage);
	$allPages = range(1, $pages);
	
	//The cool pagination code, we start with $page: at what page we are now,
	//$start: where did we leave at with the previous page, IE. 1st page  0 * 12 = 0,
	//which we start from in the page, all until to $perPage, that's 8, so from images 0 to 8 will be shown in 1st page.
	$page = min($pages, max(1, $_GET['page']));
	$start = ($page - 1) * $perPage;
	$kysely2 = $yhteys->prepare("SELECT * FROM image ORDER BY datediff DESC LIMIT ? OFFSET ?");
	$kysely2->execute(array($perPage, $start));
	$rows = $kysely2->fetchAll();
	
	//Link the pages
	foreach($allPages as $aPage){
		if($aPage == $page){
			echo $aPage.' ';	
		}else{
			echo '<a href="?page='.$aPage.'">'.$aPage.'</a> ';	
		}
		
	}
	
	echo "<br />";
	
	//Show the contents: the image, link for deletion (should be done with $_GET instead, its in todo), and some basic information of the image.
	foreach($rows as $row){
		echo "<div id=\"wrapper\">";
			echo "<br /><a href=\"".$row['path']."\" target=\"_blank\"><img style=\"width:200px; height:auto; border: #191919 3px solid;\" src=\"".$row['path']."\" /></a><br />";
			echo "<form id=\"deleteform\" name=\"deleteform\" method=\"post\" action=\"del.php\">";
			echo "<input type=\"text\" name=\"path\" id=\"path\" value=\"".$row['path']."\" />";
			echo "<input type=\"submit\" name=\"submit\" id=\"submit\" value=\"Delete\" /><br />";
			echo "<input type='checkbox' name='selected' value='Valitse' />";
			echo "</form>";
			echo "</div>";

			echo "Filename: ".$row['filename'];
			echo "<br />Date: ".$row['date'];
			echo "<br />IP: ".$row['ip'];
			echo "<br />Views: ".$row['visits'];
		
	}
	

}
?>
</body>
</html>