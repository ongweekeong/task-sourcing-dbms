<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="styles.css">
  <title>List Tasks</title>

</head>
<body>
 <h1> TASK SOLVER </h1>

 <h2> Available Tasks </h2>
 <form name = "display" action = "list_task.php" method = "POST" font>
  <font size=4.5>Search for Tasks:</font>
  <input type = "text" name = "searchstring"> <input type = "submit" name = "submit"> 
  <input type = "submit" name = "viewall" value = "View all Tasks">

  <?php
  unset($_SESSION[search_string]);
  include_once("constants.php");
  echo "<div name ='display' method='POST' align='right'><a href='main_page.php' class='button main'>Back to Main</a></div>";
  $db     = pg_connect(FULL_DATABASE_INFO);
  if (isset($_POST['submit'])) {
    $_SESSION['search_string'] = $_POST['searchstring'];
  }
  if (isset($_SESSION['search_string'])) {
    $result = pg_query($db, "SELECT * FROM public.task_submission tasks INNER JOIN public.user_info users ON tasks.email = users.email 
      WHERE tasks.name LIKE '%$_SESSION[search_string]%' AND tasks.email <> '$_SESSION[user_email]' ORDER BY deadline ASC");
  } else {
    $result = pg_query($db, "SELECT * FROM public.task_submission tasks INNER JOIN public.user_info users ON tasks.email = users.email 
      WHERE tasks.email <> '$_SESSION[user_email]' ORDER BY deadline ASC");
  }

  /*$query = "SELECT name, description, location, end_location, task_datetime, max_amt, email, deadline, end_datetime, submission_datetime, task_status FROM public.task_submission WHERE task_submission.email <> '$_SESSION[user_email]' ORDER BY deadline";
  $result = pg_query($db,$query);
  $num_rows = pg_num_rows($result);*/

  echo "<br> <br>";
  echo "<table>";
  echo "<tr>";
  echo "<th align='center' width='30'>" . "No" . "</th>";
  echo "<th align='center' width='200'>" . "Task Name" . "</th>";
  echo "<th align='center' width='200'>" . "Description" . "</th>";
  echo "<th align='center' width='200'>" . "Location" . "</th>";
  echo "<th align='center' width='200'>" . "End Location" . "</th>";
  echo "<th align='center' width='150'>" . "Datetime" . "</th>";
  echo "<th align='center' width='150'>" . "End Datetime" . "</th>";
  echo "<th align='center' width='100'>" . "Maximum Amount" . "</th>";
  echo "<th align='center' width='200'>" . "Display Name" . "</th>";
  echo "<th align='center' width='200'>" . "Time remaining" . "</th>";
  echo "<th align='center' width='150'>" . "Task Status" . "</th>";
  echo "<th align='center' width='150'>" . "Submit" . "</th>";
  echo "</tr>";
  $i = 1;
  $data = array();

  while ($row = pg_fetch_assoc($result)) {
    $timeLeft = strtotime($row['deadline']) - time();
    $days = ($timeLeft / 60 / 60 / 24) ;
    $hours = ($days - intval($days)) * 24;
    $min = ($hours - intval($hours)) * 60;

    if (intval($days) < -7) {
      continue;
    }

    echo"<tr>";
    echo "<td align='center' width='30'>" . $i . "</td>";
    echo "<td align='center' width='200'>" . $row['name'] . "</td>";
    echo "<td align='center' width='200'>" . $row['description'] . "</td>";
    echo "<td align='center' width='200'>" . $row['location'] . "</td>";
    echo "<td align='center' width='200'>" . $row['end_location'] . "</td>";
    echo "<td align='center' width='150'>" . $row['task_datetime'] . "</td>";
    echo "<td align='center' width='150'>" . $row['end_datetime'] . "</td>";
    echo "<td align='center' width='100'>" . $row['max_amt'] . "</td>";
    echo "<td align='center' width='200'>" . $row['display_name'] . "</td>";

    if ($timeLeft <= 0) {
      $days = intval($days) * -1;
      $hours = intval($hours) * -1;
      if (intval($days) == 0) {
        echo "<td align='center' width='200'>" . "Expired ".intval($hours)." hours ago". "</td>";
        echo "<td align='center' width='150'>" . $row['task_status'] . "</td>";
      } else {
        echo "<td align='center' width='200'>" . "Expired ".intval($days)." days ".intval($hours)." hours ago". "</td>";
        echo "<td align='center' width='150'>" . $row['task_status'] . "</td>";
      }
      echo "<td align='center' width='150'>" . "<button class='disabled'>Bid</button>" . "</td>";
      
    } else if ($row['task_status'] == 'in progress'){
      echo "<td align='center' width='200'>" . intval($days)." days ". intval($hours)." hours ". intval($min)." min "  . "</td>";
      echo "<td align='center' width='150'>" . $row['task_status'] . "</td>";
      echo "<td align='center' width='150'>" . '<p><input type="submit" name='.$row['submission_datetime'].' value = "Bid" ></p>' . "</td>"; 
    } else {
      echo "<td align='center' width='200'>" . intval($days)." days ". intval($hours)." hours ". intval($min)." min "  . "</td>";
      echo "<td align='center' width='150'>" . $row['task_status'] . "</td>";
      echo "<td align='center' width='150'>" . "<button class='disabled'>Bid</button>" . "</td>";     
    }
    echo "</tr>"; 

    if (isset($_POST[$row['submission_datetime']])) {
      unset($_SESSION['search_string']);

      if ($timeLeft <= 0) {
        //echo $timeLeft;
        echo '<script>alert("Submitting bid after bid deadline");</script>';
        echo "<script>location.href='list_task.php';</script>";
      } else {
        $_SESSION['tasker_email'] = $row['email'];
        $_SESSION['submission_datetime'] = $row['submission_datetime'];
        $_SESSION['max_amt'] = $row['max_amt'];

      //header(DIRECTORY."bid_task.php");
        echo "<script>location.href='bid_task.php';</script>";
      //echo "<br> successful";
      }
    }

    //echo "</tr>"; 
    $i++; }
    echo "</table>";
    ?>
  </form>
</body>
</html>