<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="styles.css">
	<title>Sign Up Page</title>
</head>
<body>
	<h1> TASK SOLVER </h1>

	<h2> Sign up page </h2>
	<ul> 
		<form name ="display" action="sign_up.php" method="POST">
      Full Name: <br>
      <input type ="text" name = "full_name" value="<?php if (isset($_POST[full_name])) {echo "$_POST[full_name]"; }?>"> <br>
      <br>
      Display Name: <br>
      <input type = "text" name = "display_name" value="<?php if (isset($_POST[display_name])) {echo "$_POST[display_name]"; }?>"> <br>
      <br>
      Phone Number: <br> 
      <input type = "text" name = "phone_number" value="<?php if (isset($_POST[phone_number])) {echo "$_POST[phone_number]"; }?>"> <br>
      <br>
      Email: <br> 
      <input type = "text" name = "email" value="<?php if (isset($_POST[email])) {echo "$_POST[email]"; }?>"> <br>
      <br>
      Password: <br>
      <input type = "password" name = "password"> <br> 
      <br>
      Re-Enter Password: <br>
      <input type = "password" name = "confirmpassword"> <br> 
      <br>
      <input type= "submit" name="submit">
    </form> 
  </ul>
  <?php
  	// Connect to the database. Please change the password in the following line accordingly
  include_once("constants.php");	
  $db     = pg_connect(FULL_DATABASE_INFO);

  if (isset($_POST['submit'])) {
    if ($_POST['password'] != $_POST['confirmpassword']) {
     echo "Passwords are not identical. Please try again.\n";
   } else {
     $query = "INSERT INTO public.User_Info VALUES ('$_POST[full_name]', '$_POST[display_name]', '$_POST[phone_number]', '$_POST[email]', '$_POST[password]')";
     $result = pg_query($db,$query); 

     if ($result) {
      echo '<script>alert("Log in successful");</script>';
      $_SESSION['user_email'] = $_POST['email'];
      header(DIRECTORY."main_page.php");
    }
    else {
      echo '<script>alert("Log in failed!");</script>';
    }
  }
}

?>
</body>
</html>