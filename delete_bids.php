<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="styles.css">
  <title>Delete Submitted Bids</title>
</head>
<body>
  <h1> TASK SOLVER</h1>

  <h2> Delete Bid Details </h2>
  <ul> 
    <form name ="display" method="POST">
      <input type= "submit" name="delete">
    </form> 
  </ul>

  <?php
    // Connect to the database. Please change the password in the following line accordingly
  include_once("constants.php");
  $db     = pg_connect(FULL_DATABASE_INFO);


  if (isset($_POST['delete'])) {
    $query = "DELETE FROM public.bid_task WHERE tasker_email = '$_SESSION[tasker_email]' AND submission_datetime = '$_SESSION[submission_datetime]' AND bidder_email = '$_SESSION[bidder_email]' AND bid_status = 'in progress'";
    $result = pg_query($db,$query); 

    if ($result) {
      echo '<script>alert("Bid deleted!");</script>';
      header(DIRECTORY."main_page.php");
    }
    else {
      echo '<script>alert("Bid delete failed!");</script>';
    }
  }
  
  ?>
  <input type="button" onclick="location.href='main_page.php';" value="Go to Main" />
</body>
</html>