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
	
	require("header.php");
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Pildigalerii</title>
</head>
<body>

	<h1>Lisatud pildid</h1>
	
	<img src="http://placehold.it/300x200" alt="#">
	<img src="http://placehold.it/300x200" alt="#">
	<img src="http://placehold.it/300x200" alt="#">
	<img src="http://placehold.it/300x200" alt="#">
	
</body>
</html>

<?php

require("footer.php");

?>

