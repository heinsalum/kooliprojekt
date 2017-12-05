<?php
	
	require ("functions.php");

	//kui pole sisseloginud, siis sisselogimise lehele
	if (!isset($_SESSION["userId"])){
		header("Location: login.php");
		exit();
	}
	
	//kui logib välja
	if (isset($_GET["logout"])){
		//lõpetame sessiooni
		session_destroy();
		header("Location: login.php");
	}

	$testGal = testGal();

	require("header.php");
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Pildigalerii</title>
</head>
<body>

	<h1>Lisatud pildid</h1
	<link rel="stylesheet" type="text/css" href="styles/general.css">
	<div class="container text-center">
		<?php foreach($testGal as $galItem){ echo $galItem; } ?>
	</div>
	
	
	


<?php

require("footer.php");

?>

