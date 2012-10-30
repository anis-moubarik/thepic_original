<?php
	require 'dbinit.php';
	require 'extfunctions.php';
	
	
	$kayttaja = pg_escape_string($_POST['username']);
	$salasana = pg_escape_string($_POST['password']);
	$saltedpw = generate_encrypted_password($salasana);
	
	$yhteys->SetAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$kysely = $yhteys->prepare("SELECT * FROM admins WHERE username=? and password=?");
	$kysely->execute(array($kayttaja, $saltedpw));
	$count = $kysely->rowCount();
	
	if($count == 1){
		session_register("username");
		session_register("password");
		setcookie("username", $kayttaja, time()+1200);
		header("location:admin.php");
	}else{
		echo "Wrong Password or Username";
	}

?>