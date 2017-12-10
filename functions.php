<?php
	require ("config.php");
	$database = "if17_rootkris";
	
	session_start();
	
	//sisselogimise funktsioon
	function signIn($email, $password){
		$notice = "";
		//ühendus serveriga
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id, email, password FROM users WHERE email = ?");
		$stmt->bind_param("s", $email);
		$stmt->bind_result($id, $emailFromDb, $passwordFromDb);
		$stmt->execute();

    //kontrollime vastavust
		if ($stmt->fetch()){
			$hash = hash("sha512", $password);
			if ($hash == $passwordFromDb){
				$notice = "Logisite sisse!";
				
				//Määran sessiooni muutujad
				$_SESSION["userId"] = $id;
				$_SESSION["userEmail"] = $emailFromDb;
				
				//liigume edasi pealehele (index.php)
				header("Location: index.php");
				exit();
			} else {
				$notice = "Vale salasõna!";
			}
		} else {
			$notice = 'Sellise kasutajatunnusega "' .$email .'" pole registreeritud!';
		}
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	
	//kasutaja salvestamise funktsioon
	function signUp($signupFirstName, $signupFamilyName, $signupBirthDate, $gender, $signupEmail, $signupPassword){
		//loome andmebaasiühenduse
		
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		//valmistame ette käsu andmebaasiserverile
		$stmt = $mysqli->prepare("INSERT INTO users (firstname, lastname, birthday, gender, email, password) VALUES (?, ?, ?, ?, ?, ?)");
		echo $mysqli->error;
		//s - string
		//i - integer
		//d - decimal
		$stmt->bind_param("sssiss", $signupFirstName, $signupFamilyName, $signupBirthDate, $gender, $signupEmail, $signupPassword);
		//$stmt->execute();
		if ($stmt->execute()){
			echo "\n Õnnestus!";
		} else {
			echo "\n Tekkis viga : " .$stmt->error;
		}
		$stmt->close();
		$mysqli->close();
	}
	
	//sisestuse kontrollimise funktsioon
	function test_input($data){
		$data = trim($data);//ebavajalikud tühiku jms eemaldada
		$data = stripslashes($data);//kaldkriipsud jms eemaldada
		$data = htmlspecialchars($data);//keelatud sümbolid
		return $data;
	}
	
	function testGal(){
		$counter = 0;
		$dir = "pictures/";
		$galleryItems = [];
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id, name, rating, votecount, uploader, filename FROM galleries ORDER BY rating DESC");
		$stmt -> bind_result($id, $name, $rating, $votecount, $uploader, $filename);
		$stmt -> execute();
		while ($stmt->fetch()) {
			$username = findUsername($uploader);
			if($counter == 3){
				$counter=-1;
				array_push($galleryItems, '<div class="col-3"><img class="galleryItem img-fluid" src="' . $dir. $filename.'" alt="asi galeriis">
				<div class="row"><p>'. $name.'</p></div>
				<div class="row"><p>'. $username.'</p></div>
				<div class="row"><p>'. $rating.' ('. $votecount. ' hindajat)</p></div>
				<div class="row btnRow"><div class="col-2"></div><form method="POST" action="'. $_SERVER["PHP_SELF"].'"><div class="col-2 buttonCont"><input type="submit" name="downvote" class="btn button-downvote"><input type="hidden" value="'.$id.'" name="idstash"></form></div><div class="col-4"></div><div class="col-2 buttonCont"><form method="POST" action="'. $_SERVER["PHP_SELF"].'"><input type="submit" name="upvote" class="btn button-upvote"><input type="hidden" value="'.$id.'" name="idstash"></form></div><div class="col-2"></div></div></div></div> ');
			} elseif ($counter == 0){
				array_push($galleryItems, '<div class="row">
				<div class="col-3"><img class="galleryItem img-fluid" src="' . $dir. $filename.'" alt="asi galeriis">
				<div class="row text-center"><p>'. $name.'</p></div>		
				<div class="row"><p>'. $username.'</p></div>
				<div class="row"><p>'. $rating.' ('. $votecount. ' hindajat)</p></div>
				<div class="row btnRow"><div class="col-2"></div><form method="POST" action="'. $_SERVER["PHP_SELF"].'"><div class="col-2 buttonCont"><input type="submit" name="downvote" class="btn button-downvote"><input type="hidden" value="'.$id.'" name="idstash"></form></div><div class="col-4"></div><div class="col-2 buttonCont"><form method="POST" action="'. $_SERVER["PHP_SELF"].'"><input type="submit" name="upvote" class="btn button-upvote"><input type="hidden" value="'.$id.'" name="idstash"></form></div><div class="col-2"></div></div>
				</div>');
			} else {
				 array_push($galleryItems, '<div class="col-3"><img class="galleryItem img-fluid" src="' . $dir. $filename.'" alt="asi galeriis">
				<div class="row"><p>'. $name.'</p></div>
				<div class="row"><p>'. $username.'</p></div>
				<div class="row"><p>'. $rating.' ('. $votecount. ' hindajat)</p></div>
				<div class="row btnRow"><div class="col-2"></div>
				<form method="POST" action="'. $_SERVER["PHP_SELF"]. '"><div class="col-2 buttonCont"><input type="submit" name="downvote" class="btn button-downvote"><input type="hidden" value="'.$id.'" name="idstash"></form></div><div class="col-4"></div><div class="col-2 buttonCont"><form method="POST" action="'. $_SERVER["PHP_SELF"].'"><input type="submit" name="upvote" class="btn button-upvote"><input type="hidden" value="'.$id.'" name="idstash"></div></form><div class="col-2"></div></div></div> ');
			}
			$counter = $counter + 1;
		}
		array_push($galleryItems, '</div>');
		return $galleryItems;
		$stmt -> close();
		$mysqli -> close();
	}
	function findUsername($uploaderId){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT firstname, lastname FROM users WHERE id = ?");
		$stmt -> bind_param("i", $uploaderId);
		$stmt -> bind_result($firstname, $lastname);
		$stmt -> execute();
		$stmt -> fetch();
		$username = $firstname. " ". $lastname;
		return $username;
		$stmt -> close();
		$mysqli -> close();
	}
	function upvote($id){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT rating FROM galleries WHERE id = ?");
		$stmt -> bind_param("i", $id);
		$stmt -> bind_result($ratingPre);
		$stmt -> execute();
		$stmt -> fetch();
		$ratingPost = $ratingPre + 1;
		$stmt -> close();
		$mysqli -> close();
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("UPDATE galleries SET rating = $ratingPost WHERE id = $id");
		$stmt -> execute();
		$stmt -> close();
		$mysqli -> close();	
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("UPDATE galleries SET votecount = votecount + 1 WHERE id=$id");
		$stmt -> execute();
		$stmt -> close();
		$mysqli -> close();		
	}
	function downvote($id){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT rating FROM galleries WHERE id = ?");
		$stmt -> bind_param("i", $id);
		$stmt -> bind_result($ratingPre);
		$stmt -> execute();
		$stmt -> fetch();
		$ratingPost = $ratingPre - 1;
		$stmt -> close();
		$mysqli -> close();
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("UPDATE galleries SET rating = $ratingPost WHERE id = ?");
		$stmt -> bind_param("i", $id);
		$stmt -> execute();
		$stmt -> close();
		$mysqli -> close();	
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("UPDATE galleries SET votecount = votecount + 1 WHERE id = ?");
		$stmt -> bind_param("i", $id);
		$stmt -> execute();
		$stmt -> close();
		$mysqli -> close();		
	}
	function addToDb($filename, $name, $userid){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO galleries(name, filename, uploader) VALUES(?, ?, ?)");
		$stmt -> bind_param("ssi", $name, $filename, $userid);
		$stmt -> execute();
		$stmt -> close();
		$mysqli -> close();
	}
?>