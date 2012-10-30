<?php
	try{
		mail("anis.moubarik@gmail.com", "Testi", "Testi 1\nTesti 2");
	}catch (Exception $e){
		echo $e;	
	}
?>