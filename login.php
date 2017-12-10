<?php
	require ("functions.php");
	if (isset($_SESSION["userId"])){
		header("Location: index.php");
		exit();
	}	
	
	$signupFirstName = "";
	$signupFamilyName = "";
	$gender = "";
	$signupEmail = "";
	$signupBirthDay = null;
	$signupBirthMonth = null;
	$signupBirthYear = null;
	$signupBirthDate = "";
	
	$loginEmail = "";
	$notice = "";
	
	$signupFirstNameError = "";
	$signupFamilyNameError = "";
	$signupBirthDayError = "";
	$signupGenderError = "";
	$signupEmailError = "";
	$signupPasswordError = "";
	
	$loginEmailError = "";
		
	//kas logitakse sisse
	if (isset($_POST["loginButton"])){
	
	
	//kas on kasutajanimi sisestatud
	if (isset ($_POST["loginEmail"])){
		if (empty ($_POST["loginEmail"])){
			$loginEmailError ="NB! Sisselogimiseks on vajalik kasutajatunnus (e-posti aadress)!";
		} else {
			$loginEmail = $_POST["loginEmail"];
		}
	}
		if(!empty($loginEmail) and !empty($_POST["loginPassword"])){
			//kutsun sisselogimise funktsiooni
			$notice = signIn($loginEmail, $_POST["loginPassword"]);
			
		}	
	}
	
	//kõiki kasutaja loomise sisestusi kontrollitakse vaid, kui on vastavat nuppu klikitud
	if(isset($_POST["signUpButton"]) and $_POST["signUpButton"] == "Loo kasutaja"){
	
	//kontrollime, kas kirjutati eesnimi
	if (isset ($_POST["signupFirstName"])){
		if (empty($_POST["signupFirstName"])){
			$signupFirstNameError ="NB! Väli on kohustuslik!";
		} else {
			$signupFirstName = test_input($_POST["signupFirstName"]);
		}
	}
	
	//kontrollime, kas kirjutati perekonnanimi
	if (isset ($_POST["signupFamilyName"])){
		if (empty($_POST["signupFamilyName"])){
			$signupFamilyNameError ="NB! Väli on kohustuslik!";
		} else {
			$signupFamilyName = test_input($_POST["signupFamilyName"]);
		}
	}
	
	
	//kas sünnikuupäev on sisestatud
	if (isset($_POST["signupBirthDay"])){
		$signupBirthDay = $_POST["signupBirthDay"];
		//echo $signupBirthDay;
	} else {
		$signupBirthDayError = "Kuupäeva pole sisestatud!";
	}
	
	//kas sünnikuu on sisestatud
	if ( isset($_POST["signupBirthMonth"]) ){
		$signupBirthMonth = intval($_POST["signupBirthMonth"]);
	} else {
		$signupBirthDayError .= " Kuu pole sisestatud!";
	}
	
	//kas sünniaasta on sisestatud
	if (isset($_POST["signupBirthYear"])){
		$signupBirthYear = $_POST["signupBirthYear"];
		//echo $signupBirthYear;
	} else {
		$signupBirthDayError .= "Aasta pole sisestatud!";
	}
	
	//kui sünnikuupäev on sisestatud, siis kontrollima, kas valiidne
	if (isset($_POST["signupBirthDay"]) and isset($_POST["signupBirthMonth"]) and isset($_POST["signupBirthYear"])){
		if (checkdate(intval($_POST["signupBirthMonth"]), intval($_POST["signupBirthDay"]), intval($_POST["signupBirthYear"]))){
			$birthDate = date_create($_POST["signupBirthMonth"] ."/" .$_POST["signupBirthDay"] ."/" .$_POST["signupBirthYear"]);
			$signupBirthDate = date_format($birthDate, "Y-m-d");
		} else {
			$signupBirthDayError .= " Pole korrektne kuupäev!";
		}
	} 
	
	//kontrollime, kas kirjutati kasutajanimeks email
	if (isset ($_POST["signupEmail"])){
		if (empty ($_POST["signupEmail"])){
			$signupEmailError ="NB! Väli on kohustuslik!";
		} else {	
			//kutsun välja sisestuse kontrolli funktsiooni
			$signupEmail = test_input($_POST["signupEmail"]);
			$signupEmail = filter_var($signupEmail, FILTER_SANITIZE_EMAIL); //eemaldame mittesobilikud märgid
			if(!filter_var($signupEmail, FILTER_VALIDATE_EMAIL)){
				$signupEmailError ="NB! e-postiaadress pole nõutud kujul!";
			}	
		}
	}
	
	if (isset ($_POST["signupPassword"])){
		if (empty ($_POST["signupPassword"])){
			$signupPasswordError = "NB! Väli on kohustuslik!";
		} else {
			//polnud tühi
			if (strlen($_POST["signupPassword"]) < 8){
				$signupPasswordError = "NB! Liiga lühike salasõna, vaja vähemalt 8 tähemärki!";
			}
		}
	}
	
	if (isset($_POST["gender"]) && !empty($_POST["gender"])){ //kui on määratud ja pole tühi
			$gender = intval($_POST["gender"]);
		} else {
			$signupGenderError = " (Palun vali sobiv!) Määramata!";
	}
	
	//UUE KASUTAJA ANDMEBAASI KIRJUTAMINE, kui kõik on olemas	
	if (empty($signupFirstNameError) and empty($signupFamilyNameError) and empty($signupBirthDayError) and empty($signupGenderError) and empty($signupEmailError) and empty($signupPasswordError)){
		echo "Hakkan salvestama!";
		//krüpteerin parooli
		$signupPassword = hash("sha512", $_POST["signupPassword"]);
		//kutsuma välja kasutaja salvestamise funktsiooni
		signUp($signupFirstName, $signupFamilyName, $signupBirthDate, $gender, $signupEmail, $signupPassword);
		
	}
	
	}//if kui oli vajutatud nuppu "Loo kasutaja"
	
	//Tekitame kuupäeva valiku
	$signupDaySelectHTML = "";
	$signupDaySelectHTML .= '<select name="signupBirthDay">' ."\n";
	$signupDaySelectHTML .= '<option value="" selected disabled>päev</option>' ."\n";
	for ($i = 1; $i < 32; $i ++){
		if($i == $signupBirthDay){
			$signupDaySelectHTML .= '<option value="' .$i .'" selected>' .$i .'</option>' ."\n";
		} else {
			$signupDaySelectHTML .= '<option value="' .$i .'">' .$i .'</option>' ." \n";
		}
		
	}
	$signupDaySelectHTML.= "</select> \n";
	
	//Tekitame sünnikuu valiku
	$signupMonthSelectHTML = "";
	$monthNamesEt = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
	$signupMonthSelectHTML .= '<select name="signupBirthMonth">' ."\n";
	$signupMonthSelectHTML .= '<option value="" selected disabled>kuu</option>' ."\n";
	foreach ($monthNamesEt as $key=>$month){
		if ($key + 1 === $signupBirthMonth){
			$signupMonthSelectHTML .= '<option value="' .($key + 1) .'" selected>' .$month .'</option>' ."\n";
		} else {
		$signupMonthSelectHTML .= '<option value="' .($key + 1) .'">' .$month .'</option>' ."\n";
		}
	}
	$signupMonthSelectHTML .= "</select> \n";
	
	//Tekitame aasta valiku
	$signupYearSelectHTML = "";
	$signupYearSelectHTML .= '<select name="signupBirthYear">' ."\n";
	$signupYearSelectHTML .= '<option value="" selected disabled>aasta</option>' ."\n";
	$yearNow = date("Y");
	for ($i = $yearNow; $i > 1900; $i --){
		if($i == $signupBirthYear){
			$signupYearSelectHTML .= '<option value="' .$i .'" selected>' .$i .'</option>' ."\n";
		} else {
			$signupYearSelectHTML .= '<option value="' .$i .'">' .$i .'</option>' ."\n";
		}
		
	}
	$signupYearSelectHTML.= "</select> \n";
?>

<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Sisselogimine või uue kasutaja loomine</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="styles/general.css">
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<h2>Logi sisse: </h2>
		<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
			<label>Kasutajanimi (E-post): </label>
			<input name="loginEmail" type="email" value="<?php echo $loginEmail; ?>"><span class="errornotice"><?php echo $loginEmailError; ?></span>
			<br>
			<label>Parool: </label>
			<input name="loginPassword" placeholder="Salasõna" type="password"><span></span>
			<input name="loginButton" type="submit" value="Logi sisse"><span class="errornotice"><?php echo $notice; ?></span>
		</form>
	</nav>
	<div class="container">
	<br>
	<br>
	<br>
	<br>
	<br>
	<h1>Loo uus kasutaja</h1>
	<br>
	<p class="font-weight-bold">Kui kasutajat pole veel loodud, siis tuleb seda kõigepealt teha!</p>
	<br>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
	<br>
		<label>Sisestage oma eesnimi </label>
		<input name="signupFirstName" type="text" value="<?php echo $signupFirstName; ?>">
		<span class="errornotice"><?php echo $signupFirstNameError; ?></span>
		<br>
		<label>Sisestage oma perekonnanimi </label>
		<input name="signupFamilyName" type="text" value="<?php echo $signupFamilyName; ?>">
		<span class="errornotice"><?php echo $signupFamilyNameError; ?></span>
		<br>
		<label>Sisesta oma sünnikuupäev</label>
		<?php
			echo $signupDaySelectHTML .$signupMonthSelectHTML .$signupYearSelectHTML;
		?>
		<span class="errornotice"><?php echo $signupBirthDayError; ?></span>
		
		<br><br>
		<label>Palun valige oma sugu</label>
		<br>
		<input type="radio" name="gender" value="1" <?php if ($gender == "1") {echo 'checked';} ?>><label>Mees</label> <!-- Kõik läbi POST'i on string!!! -->
		<br>
		<input type="radio" name="gender" value="2" <?php if ($gender == "2") {echo 'checked';} ?>><label>Naine</label><span class="errornotice"><?php echo $signupGenderError; ?></span>
		<br><br>
		<label>Kasutajanimi (E-post)</label>
		<input name="signupEmail" type="email" "value="<?php echo $signupEmail; ?>">
		<span class="errornotice"><?php echo $signupEmailError; ?></span>
		<br><br>
		<label>Parool </label>
		<input name="signupPassword" type="password">
		<span class="errornotice"><?php echo $signupPasswordError; ?></span>
		<br><br>

		
		<input name="signUpButton" type="submit" value="Loo kasutaja"> 
	</form>
	</div>
<?php
require("footer.php");
?>