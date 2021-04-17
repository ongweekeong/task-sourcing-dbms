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

  <h2> My Task Submission History </h2>
  <form method="post" action="list_my_task.php">
    <?php
    include_once("constants.php");
    $db     = pg_connect(FULL_DATABASE_INFO);

    $query = "SELECT name, description, location, end_location, task_datetime, max_amt, email, deadline, end_datetime, submission_datetime, task_status FROM public.task_submission
    WHERE email = '$_SESSION[user_email]' ORDER BY deadline DESC";
    $result = pg_query($db,$query);
    $num_rows = pg_num_rows($result);

    echo "<div name ='display' method='POST' align='right'><a href='main_page.php' class='button main'>Back to Main</a></div>";
    echo "<br>";
    echo "<table>";
    echo "<tr>";
    echo "<th align='center' width='50'>" . "No" . "</th>";
    echo "<th align='center' width='200'>" . "Name" . "</th>";
    echo "<th align='center' width='200'>" . "Description" . "</th>";
    echo "<th align='center' width='200'>" . "Location" . "</th>";
    echo "<th align='center' width='200'>" . "End Location" . "</th>";
    echo "<th align='center' width='150'>" . "Datetime" . "</th>";
    echo "<th align='center' width='150'>" . "End Datetime" . "</th>";
    echo "<th align='center' width='100'>" . "Maximum Amount" . "</th>";
    echo "<th align='center' width='200'>" . "Bidding time remaining" . "</th>";
    echo "<th align='center' width='200'>" . "Task Status" . "</th>";
    echo "</tr>";

    $i = 1;
    $data = array();

    while ($row = pg_fetch_assoc($result)) {
      $timeLeft = strtotime($row['deadline']) - time();
      $days = ($timeLeft / 60 / 60 / 24) ;
      $hours = ($days - intval($days)) * 24;
      $min = ($hours - intval($hours)) * 60;
      echo"<tr>";
      echo "<td align='center' width='50'>" . $i . "</td>";
      echo "<td align='center' width='200'>" . $row['name'] . "</td>";
      echo "<td align='center' width='200'>" . $row['description'] . "</td>";
      echo "<td align='center' width='200'>" . $row['location'] . "</td>";
      echo "<td align='center' width='200'>" . $row['end_location'] . "</td>";
      echo "<td align='center' width='150'>" . $row['task_datetime'] . "</td>";
      echo "<td align='center' width='150'>" . $row['end_datetime'] . "</td>";
      echo "<td align='center' width='100'>" . $row['max_amt'] . "</td>";
      if ($timeLeft <= 0) {
        echo "<td align='center' width='200'>" . "EXPIRED"  . "</td>";
        echo "<td align='center' width='200'>" . $row['task_status'] . "</td>";
      } else {
        echo "<td align='center' width='200'>" . intval($days)." days ". intval($hours)." hours ". intval($min)." min "  . "</td>";
        echo "<td align='center' width='200'>" . $row['task_status'] . "</td>";
      }
      echo "</tr>";

      if (isset($_POST[$row['submission_datetime']])) {
        if ($timeLeft <= 0) {
          echo '<script>alert("Submitting bid after bid deadline!");</script>';
          echo "<script>location.href='list_task.php';</script>";
        } else {
          $_SESSION['tasker_email'] = $row['email'];
          $_SESSION['submission_datetime'] = $row['submission_datetime'];
          echo '<script>alert("Successful!");</script>';
          echo "<script>location.href='bid_task.php';</script>";
                //echo "<br> successful";
        }
      }
      $i++; }
      echo "</table> <br>";
      ?>
    </form>
  </body>
  </html>