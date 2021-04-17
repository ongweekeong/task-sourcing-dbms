<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="styles.css">		
	<title>Initial Page</title>
</head>
<body>
	<h1>Welcome To Task Solver</h1>

	<h2> Initial Page </h2>
	<ul> 
		<form name ="display" method="POST">
		<input type = "submit" name = "sign_up" value = "Sign Up"> <br>
		<br>
		<input type= "submit" name="sign_in" value = "Sign In"> <br>
		<br>
		<input type= "submit" name="admin_sign_in" value = "Admin"> <br>
		<br>
		</form> 
		</ul>
<?php
	include_once("constants.php");
	if (isset($_POST['sign_up'])) { 
  		header(DIRECTORY."sign_up.php");
  	}
	else if (isset($_POST['sign_in'])) {
		header(DIRECTORY."sign_in.php");
	}
	else if (isset($_POST['admin_sign_in'])) {
		header(DIRECTORY."admin_sign_in.php");
	}
?>
</body>
</html>