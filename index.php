  <?php
	  include 'top.php';
	  require 'extfunctions.php';
	  require 'dbinit.php';
	
		
	  if(!empty($_POST)){
		  $type =  $_FILES["file"]["type"];
		  $size = $_FILES["file"]["size"];
		  $name = getRandomString();
		  $error = "";
		  $success = "";
		  $debug = "";
		  $display_message = "";
		  $full_url="http://localhost/imgupload/";
		  
	  		$types = array("image/gif", "image/jpeg", "image/png", "image/jpg", "image/pjpeg");
			  if( in_array($type, $types) && $size < 2097152)
			  {
			  
			  if ($_FILES["file"]["error"] > 0)
			  {
				  $error = "Error: " . $_FILES["file"]["error"] . "<br />";
			  }
			  else
			  {
				  $debug .= "Upload: " . $_FILES["file"]["name"] . "<br />";
				  $debug .= "Type: " . $type . "<br />";
				  $debug .= "Size: " . ($size / 1024) . " Kb<br />";
				  $debug .= "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
				  
				  
				  if($type == "image/gif"){
					  
					  if(file_exists("upload/" . $name . ".gif"))
					  {
						  $name = getRandomString();	
					  }
					  
					  move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $name . ".gif");
					  $debug .= "Stored in: " . "upload/" . $name . ".gif";
					  $path = "upload/" . $name . ".gif";
					  
				  }else if($type == "image/jpeg"){
					  
					  if(file_exists("upload/" . $name . ".jpg"))
					  {
						  $debug = getRandomString();	
					  }
					  
					  move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $name . ".jpg");
					  $debug .= "Stored in: " . "upload/" . $name . ".jpg";
					  $path = "upload/" . $name . ".jpg";
					  newImage($path);
					  
				  }else if($type == "image/png"){
					  
					  if(file_exists("upload/" . $name . ".png"))
					  {
						  $name = getRandomString();	
					  }
					  
					  move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $name . ".png");
					  $debug .= "Stored in: " . "upload/" . $name . ".png";
					  $path = "upload/" . $name . ".png";
					  
				  }
				  
				  $yhteys->SetAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				  
				  $kysely = $yhteys->prepare("INSERT INTO image (ip, id, filename, imgtype, path, date, visits, datediff)
																  VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
				  $kysely->execute(array($_SERVER['REMOTE_ADDR'],$name, pg_escape_string($_FILES["file"]["name"]), $type, $path, date(DATE_RFC822), 0, time()));
				  
			  }
			  }
			  else
			  {
				  $error = "Invalid file";
				  $error .= " ".$type;
				  $errCode = 1;
			  }
			  if($errCode != 1){
				  header("location:/".$name);
				  echo "<HTML></HTML>";
				  //echo $debug;
			  }else{
				  //echo $error;
				  header("location:invalid.php");	
			  }
		  }
  ?>
	<script type="text/javascript">
		$(document).ready(function() {
            $("#loader").hide();
			$("#submit").hide();
			$("#dropzone").hide();
			$("#submit2").hide();
			$("#submit").click(function(){
				$("#loader").fadeIn(1000);
			});
			$('.file_button_container input').MultiFile({
				list: '#file_button_container-list',
				afterFileSelect: function(){$("#file_button_container-list").fadeIn(500)},
				afterFileRemove: function(){$('#file_button_container-list').fadeOut(500)},
				afterFileSelect: function(){$("#submit").fadeIn(500)},
				afterFileRemove: function(){$("#submit").fadeOut(500)},
				max: 1
			});
			
			var up = document.getElementById("upload");
			up.ondragover = up.ondragenter = function(e){
				$('#dropzone').fadeIn(1000);
			}
			
			up.ondragend = function(){ $('#dropzone').hide(500);}
			
			up.ondrop = function(e){
				this.className = '';
				$('#dropzone').hide(500);
				e.preventDefault();
			}
			
				$('#dropzone input').MultiFile({
				list: '#file_button_container-list',
				afterFileSelect: function(){$("#file_button_container-list").fadeIn(500)},
				afterFileRemove: function(){$('#file_button_container-list').fadeOut(500)},
				afterFileSelect: function(){$("#submit2").fadeIn(500); $('#dropzone').animate({opacity: 0.25, left: '+=50', height: 'toggle'}, 500); },
				afterFileRemove: function(){$("#submit2").animate({opacity: 0.25, left: '+=50', height: 'toggle'}, 500)},
				max: 1
			});	
			
			$('.file_button_container').qtip({
				content: 'Select images to upload',
				show: 'mouseover',
				hide: 'mouseout',
				style: {
					name: 'dark',
					tip: 'bottomMiddle'	
				},
				position: {
					corner: {
						target: 'topMiddle',
						tooltip: 'bottomMiddle'	
					}
				}
			})
			
			$('#blog').qtip({
				content: 'Redirects to my WP blog',
				show: 'mouseover',
				hide: 'mouseout',
				style: {
					name: 'dark',
					tip: 'bottomMiddle'	
				},
				position: {
					corner: {
						target: 'topMiddle',
						tooltip: 'bottomMiddle'	
					}
				}
			})
			
			$("#twitter").qtip({
				content: 'Follow me at twitter. @anismou',
				show: 'mouseover',
				hide: 'mouseout',
				style: {
					name: 'dark',
					tip: 'bottomMiddle'
				},
				position: {
					corner: {
						target: 'topMiddle',
						tooltip: 'bottomMiddle'	
					}
				}
			})
			
			$('#dragp').hide();
			var browser = BrowserDetect.browser;
			console.log(browser);
			if(browser == "Chrome" || browser == "Safari"){
				$('#dragp').show();	
			}
        });
		
		
	</script>
    <h1>Upload Images</h1>
    <div id="uploadForm">
    <form id="form1" action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
    <div class="file_button_container"><input id="file" name="file" type="file" accept="image/gif, image/jpg, image/jpeg, image/png, image/pjpg"/></div><br />
  <input id="submit" class="button-medium" type="submit" value="Upload" name="submit" />
  </form>
  <p id="dragp">You can also drag and drop files here</p>
  </div>

	<div id="loader">
		<!--<progress max="1" value="0" id="progress"></progress>
        <p id="progress-txt"></p>-->
  		<img src="assets/loader.gif" alt="loader" />
		<p>Loading, this may take a moment</p>
	</div>
    <form id="form2" action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
    	<div id="dropzone">
        <h2>Drop your file here</h2>
    <input type="file" accept="image/gif, image/jpg, image/jpeg, image/png, image/pjpg" id="dropinput" name="file" style="top: 0; left:0; height: 200px; width: 100%; opacity: 0;" />
    </div>
    <input id="submit2" class="button-medium" type="submit" value="Upload" name="submit" />
    </form>
  <div id="file_button_container-list">
  </div>

  <?php
  include 'bottom.php';
  ?>