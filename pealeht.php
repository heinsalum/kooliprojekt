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

<p><a href="photoupload.php">Lisa pilte</a><p>
<img src="http://placehold.it/300x200" alt="#">
<img src="http://placehold.it/300x200" alt="#">
<img src="http://placehold.it/300x200" alt="#">
<img src="http://placehold.it/300x200" alt="#">

<?php

require("footer.php");

?>

