<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="styles.css">
	<title>Delete Account</title>
</head>
<body>
	<h1> TASK SOLVER </h1>

	<h2> Delete Account </h2>
	<ul> 
		<form name ="display" action="delete_account.php" method="POST">
      Email: <br>
      <input type ="text" name = "email"> <br>
      <br>
      Password: <br>
      <input type = "text" name = "password"> <br>
      <br>
      <input type= "submit" name="submit">
    </form> 
  </ul>


  <?php
  	// Connect to the database. Please change the password in the following line accordingly
  include_once("constants.php");
  $db     = pg_connect(FULL_DATABASE_INFO);

  if (isset($_POST['submit'])) {
    $query = "DELETE FROM public.user_info WHERE user_info.email = '$_POST[email]' AND user_info.password = '$_POST[password]'";
    $result = pg_query($db,$query); 

    if ($result) {
     echo '<script>alert("Successful!");</script>';
     header(DIRECTORY."admin_main_page.php");
   }
   else {
     echo '<script>alert("Failed!");</script>';
   }
 }
 
 ?>
</body>
</html>