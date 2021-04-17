<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="styles.css">
	<title>Admin Main Page</title>
</head>
<body>
	<h1> TASK SOLVER </h1>

	<h2> </h2>
	<ul> 
		<form name ="display" method="POST">
      <input type = "submit" name = "admin_view_task" value="View/Update/Delete Task"> 
      <br>
      <br>
      <input type="submit" name="admin_view_bid" value="View/Update/Delete Bid"> 
      <br>
      <br>
      <input type="submit" name="delete_account" value ="View/Update/Delete Accounts"> <br>
      <br>
    </form> 
  </ul>

  <?php
  	// Connect to the database. Please change the password in the following line accordingly
  include_once("constants.php");
  echo "<form name ='display' method='POST' align='right'><a href='admin_sign_in.php' class='button logout'>Logout</a></form>";
  if (isset($_POST['admin_view_task'])) { 
    header(DIRECTORY."admin_view_task.php");
  }
  else if (isset($_POST['admin_view_bid'])) {
    header(DIRECTORY."admin_view_bid.php");
  }
  else if (isset($_POST['delete_account'])) {
  	header(DIRECTORY."admin_view_account.php");
  }
    ?>
  </body>
  </html>