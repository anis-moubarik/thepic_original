<?php

		//dbinit.php for git, change your info into the respective fields
		try{
			$yhteys = new PDO("pgsql:host=?;dbname=?;", "?", "?");
		}catch(PDOException $e){
			die("Virhe: " . $e->getMessage());
		}
		?>