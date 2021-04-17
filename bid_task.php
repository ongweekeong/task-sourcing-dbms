<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="styles.css">
  <title>Bid Task</title>
</head>
<body>
  <h1> TASK SOLVER </h1>
  <h2> Bidding of Task </h2>
  <ul> 
    <form name ="display" action="bid_task.php" method="POST">
      <br>
      <font size=4.5>Bid Amount:</font> 
      <input type = "text" name = "bid_amt"> 
      <input type= "submit" name="submit"><br>
    </form> 
  </ul>
  <?php
    // Connect to the database. Please change the password in the following line accordingly
  include_once("constants.php");
  $db     = pg_connect(FULL_DATABASE_INFO);
  $query = "SELECT * FROM public.task_submission tasks INNER JOIN public.user_info users ON tasks.email = users.email 
  WHERE tasks.email = '$_SESSION[tasker_email]' AND tasks.submission_datetime = '$_SESSION[submission_datetime]'";

  $result = pg_query($db, $query);
  $row = pg_fetch_array($result);

  echo "<div align='right'>$_SESSION[user_email] </div>";
  echo "<div align='right'><a href='main_page.php' class='button main'>Back to Main</a></div><br>";

  echo "<table>";
  echo "<tr>";
  echo "<th align='center' width='200'>" . "Task Name" . "</th>";
  echo "<th align='center' width='200'>" . "Description" . "</th>";
  echo "<th align='center' width='200'>" . "Location" . "</th>";
  echo "<th align='center' width='200'>" . "End Location" . "</th>";
  echo "<th align='center' width='150'>" . "Datetime" . "</th>";
  echo "<th align='center' width='150'>" . "End Datetime" . "</th>";
  echo "<th align='center' width='100'>" . "Maximum Amount" . "</th>";
  echo "<th align='center' width='200'>" . "Tasker Display Name" . "</th>";
  echo "<th align='center' width='200'>" . "Time remaining" . "</th>";
  echo "</tr>";

  echo "<tr>";
  echo "<td align='center' width='200'>" . $row['name'] . "</td>";
  echo "<td align='center' width='200'>" . $row['description'] . "</td>";
  echo "<td align='center' width='200'>" . $row['location'] . "</td>";
  echo "<td align='center' width='200'>" . $row['end_location'] . "</td>";
  echo "<td align='center' width='200'>" . $row['task_datetime'] . "</td>";
  echo "<td align='center' width='200'>" . $row['end_datetime'] . "</td>";
  echo "<td align='center' width='200'>" . $row['max_amt'] . "</td>";
  echo "<td align='center' width='200'>" . $row['display_name'] . "</td>";
  $timeLeft = strtotime($row['deadline']) - time();
  $days = ($timeLeft / 60 / 60 / 24) ;
  $hours = ($days - intval($days)) * 24;
  $min = ($hours - intval($hours)) * 60;
  echo "<td align='center' width='200'>" . intval($days)." days ". intval($hours)." hours ". intval($min)." min "  . "</td>";
  echo "</tr>";  
  echo "</table>";

  if (isset($_POST['submit'])) {
  //$date = date('m/d/Y h:i:s a', time());
    $t = time();
    $datetime = date("Y-m-d H:i:s", substr($t, 0, 10));
    $query = "INSERT INTO public.bid_task VALUES ('$_SESSION[tasker_email]', '$_SESSION[submission_datetime]', '$_SESSION[user_email]', '$_POST[bid_amt]', '$datetime', 'in progress')";
    $result = pg_query($db,$query); 

    if ($result) {
      echo '<script>alert("Bid submitted successfully.");</script>';
      header(DIRECTORY."main_page.php");
    }
    else {
      echo '<script>alert("Error occured.");</script>';
      header(DIRECTORY."main_page.php");      
    }
  }

  ?>
</body>
</html>