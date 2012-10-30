<?php
session_start();
require 'dbinit.php';

if(!session_is_registered(username)){
	header("location:http://thepic.net");	
}else{
	$name = $_POST["path"];
	$yhteys->SetAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$kysely = $yhteys->prepare("SELECT * FROM image WHERE path=?");
	$kysely->execute(array($name));
	$count = $kysely->rowCount();
	$path = $kysely->fetchColumn();
	if($count < 1){
		echo "Not found, trying to delete it from the db.";	
	}else{
		unlink("../imgupload/".$path);
	}
	$query = $yhteys->prepare("DELETE FROM image WHERE path=?");
	$query->execute(array($name));
	echo "Delete succesful.";
	echo "<br /><a href=\"admin.php\">Return</a>";
}

?>