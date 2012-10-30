<?php
require 'dbinit.php';
require 'extfunctions.php';
include 'top.php';

if(!empty($_POST)){
	$user = strtolower(pg_escape_string($_POST['usrname']));
	$hashedpw = generate_encrypted_password(pg_escape_string($_POST['passwd']));
	$yhteys->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$kysely = $yhteys->prepare("SELECT * FROM users WHERE username=? and password=?");
	$kysely->execute(array($user, $hashedpw));
	$count = $kysely->rowCount();
	
	if($count == 1){
		session_register("user");
		session_register("hashedpw");
		setcookie("username", $user, time()+1200);
		echo "<p>Logged in</p>";
	}else{
		echo "<p>Wrong username or password</p>";	
	}
}else{

?>
<div id="rekist">
<form action="<?php echo $PHP_SELF;?>" method="post" name="loginform">
<label for="usrname">Username</label>
<input id="usrname" name="usrname" type="text" /><br />
<label for="passwd">Password</label>
<input id="passwd" name="passwd" type="password" /><br />
<input id="register" name="register" type="submit" value="Login" />
</form>
</div>
<?php
}
include 'bottom.php';
?>