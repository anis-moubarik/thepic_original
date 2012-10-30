<?php
session_start();
if(session_is_registered("username")){
require 'dbinit.php';
header("location:admin.php");

$yhteys->SetAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$kysely = $yhteys->prepare("SELECT path FROM image");
$kysely->execute();
$count = $kysely->rowCount();

for($i = 0; $i < $count; $i++){
	$path = $kysely->fetchColumn();
	unlink($path);
}

$query = $yhteys->prepare("TRUNCATE image CASCADE");
$query->execute();
}else{
	header("location:../imgupload/");
}




?>
