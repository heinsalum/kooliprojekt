<?php
	require("functions.php");
	$notice = "";
	
	//kui pole sisseloginud, siis sisselogimise lehele
	if(!isset($_SESSION["userId"])){
		header("Location: login.php");
		exit();
	}
	
	//kui logib välja
	if (isset($_GET["logout"])){
		//lõpetame sessiooni
		session_destroy();
		header("Location: login.php");
	}
	
	//Liidan klassi
	require("classes/Photoupload.class.php");
	//Loome objekti
	
	//Algab foto laadimise osa
	$target_dir = "pictures/";
	$target_file;
	$uploadOk = 1;
	$imageFileType;
	$maxWidth = 600;
	$maxHeight = 400;
	$marginBottom = 10;
	$marginRight = 10;
	
	//Kas on pildi failitüüp
	if(isset($_POST["submit"])) {
		
		//kas mingi fail valiti
		if(!empty($_FILES["fileToUpload"]["name"])){
			
			$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]))["extension"]);
			$target_file = "kooliprojekt" .(microtime(1) * 10000) ."." .$imageFileType;
			//$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
			
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) {
				$notice .= "Järgmise nimega pilt on edukalt üles laetud - " . $check["mime"] . ". ";
				$uploadOk = 1;
			} else {
				$notice .= "See pole pildifail. ";
				$uploadOk = 0;
			}
			
			//Kas selline pilt on juba üles laetud
			if (file_exists($target_file)) {
				$notice .= "Kahjuks on selle nimega pilt juba olemas. ";
				$uploadOk = 0;
			}
			
			
			//Piirame failitüüpe
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
				$notice .= "Vabandust, vaid jpg, jpeg, png ja gif failid on lubatud! ";
				$uploadOk = 0;
			}
			

			if ($uploadOk == 0) {
				$notice .= "Vabandust, pilti ei laetud üles! ";
			//Kui saab üles laadida
			} else {
				
				
				
				//kasutan klassi
				$myPhoto = new Photoupload($_FILES["fileToUpload"]["tmp_name"], $imageFileType);
				$myPhoto->readExif();
				$myPhoto->resizeImage($maxWidth, $maxHeight);
				$myPhoto->savePhoto($target_dir, $target_file);
				$myPhoto->clearImages();
				unset($myPhoto);
				
				
			}
		
		} else {
			$notice = "Palun valige kõigepealt pildifail!";
		} //kas üldse mõni fail valiti, lõppeb
	}
	require("header.php");
?>
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="styles/general.css">
	<h2>Fotode üleslaadimine</h2>
	<br>
	<form action="photoupload.php" method="post" enctype="multipart/form-data">
		<label>Valige pildifail:</label>
		<br>
		<input type="file" name="fileToUpload" id="fileToUpload">
		<input type="submit" value="Lae üles" name="submit" id="submitPhoto"><span id="fileSizeError"></span>
	</form>
	
	<span><?php echo $notice; ?></span>
	<br>
	<br>
	<br>
	<h3> Siin on pildifaili lisamise meelespead: <h3>
	<p> --> Lubatud on järgmised pildifaili tüübid: .jpg .jpeg .png .gif!</p>
	<p> --> Kui fail ei ole pildifail, siis seda üles laadida ei saa!</p>
	<p> --> Server automaatselt vähendab pildi originaalresolutsiooni 600x400 resolutsioonile!</p>
	<p> --> Pildifaili maksimaalne suurus on 2 MB(megabait)!</p>
	<p> --> Lisatud pildid kuvatakse pealehel!</p>
	<p> --> Palun mitte laadida liiga isiklikke pilte ega kellegi teise mainet rikkuvaid pilte! </p>
	
<?php
	require("footer.php");
	echo '<script type="text/javascript" src="javascript/checkFileSize.js"></script>';
?>