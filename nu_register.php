<?php
require 'dbinit.php';
require 'extfunctions.php';
include 'top.php';

if (!empty($_POST)){
	$yhteys->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$users = $yhteys->prepare("SELECT username FROM users WHERE username=?");
	$inputuser = strtolower(pg_escape_string($_POST['usrname']));
	$users->execute(array($inputuser));
	
	if($users->rowCount() > 0){
		echo "<p class=\"register\">Username Already taken, <a href=\"register.php\">Go back.</a></p>";
	}
	else if($_POST['passwd'] != $_POST['passwd2']){
		echo "<p class=\"register\">Passwords didn't match, <a href=\"register.php\">Go back.</a></p>";
	}else if(strlen($_POST['passwd']) < 5){
		echo "<p class=\"register\">Password is too short, <a href=\"register.php\">Go back.</a></p>";	
	}else if($_POST['usrname'] == $_POST['passwd']){
		echo "<p class=\"register\">Don't use username as your password, <a href=\"register.php\">Go back.</a></p>";
	}else{
		$hashedpw = generate_encrypted_password(pg_escape_string($_POST['passwd']));
		
		$kysely = $yhteys->prepare("INSERT INTO users (username, password) VALUES (:usrname, :pw)");
		$kysely->bindParam(':usrname', $inputuser);
		$kysely->bindParam(':pw', $hashedpw);
		$kysely->execute();
		echo "<p class=\"register\">Registeration successful, <a href=\"index.php\">go to index</a></p>";
	}
}else{

?>
<div id="rekist">
<form action="<?php $_SERVER['PHP_SELF'];?>" method="post" name="regform">
<p>Password has to be 5 characters or longer.</p>
<label for="usrname">Username</label>
<input id="usrname" name="usrname" type="text" /><br />
<label for="passwd">Password</label>
<input id="passwd" name="passwd" type="password" /><br />
<label for="passwd2">Password again</label>
<input id="passwd2" name="passwd2" type="password" /><br />
<input id="register" name="register" type="submit" value="Register" />
</form>
</div>
<?php
}
include 'bottom.php';
?>