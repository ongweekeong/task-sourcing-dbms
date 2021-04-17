<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="styles.css">
	<title>Admin Sign In Page</title>
</head>
<body>
	<h1> TASK SOLVER </h1>

	<h2> Admin Sign In page </h2>
	<ul> 
		<form name ="display" action="admin_sign_in.php" method="POST">
		Email: <br>
		<input type ="text" name = "email"> <br>
		<br>
		Password: <br>
		<input type = "password" name = "password"> <br>
		<br>
		<input type= "submit" name="submit">
		</form> 
		</ul>


	<?php
  	// Connect to the database. Please change the password in the following line accordingly
  	include_once("constants.php");
	$db     = pg_connect(FULL_DATABASE_INFO);

	if (isset($_POST['submit'])) {
		$query = "INSERT INTO public.sign_in VALUES ('$_POST[email]', '$_POST[password]')";
  		$result = pg_query($db,$query); 

  		if ($result) {
  			$adminquery = "SELECT isadmin FROM public.user_info WHERE user_info.email = '$_POST[email]' AND user_info.password = '$_POST[password]'";
  			$adminresult = pg_query($db,$adminquery);

  			/*if ($adminresult) {
  				echo "$adminresult";
  			}
*/
  			while ($row = pg_fetch_assoc($adminresult)) {
  				//echo "$row[isadmin]";

  				if ($row[isadmin] == "t") {
  					$_SESSION[user_email] = $_POST[email];
					header(DIRECTORY."admin_main_page.php");
					
  				}
  				else {
  					echo "No Administrator rights for this user\n";
  				}
  			}
		}
		else {
			echo "Try Again\n";
		}
	}
    
	?>
</body>
</html>