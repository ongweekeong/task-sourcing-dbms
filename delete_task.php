<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="styles.css">
  <title>Delete Submitted Tasks</title>
</head>
<body>
  <h1> TASK SOLVER</h1>

  <h2> Delete Task Details</h2>
  <form method="post" action="delete_task.php">
    <?php
    include_once("constants.php");
    $db     = pg_connect(FULL_DATABASE_INFO);

    $query = "SELECT * FROM public.task_submission WHERE task_submission.email = '$_SESSION[user_email]' ORDER BY task_submission.deadline DESC";
    $result = pg_query($db,$query);
    echo "$_SESSION[user_email]";
    echo "<h2> List of submitted tasks </h2>";
    echo "<div name ='display' method='POST' align='right'><a href='main_page.php' class='button main'>Back to Main</a></div>";
    echo "<table> ";
    echo "<tr>";
    echo "<th align='center' width='50'>" . "Number" . "</th>";
    echo "<th align='center' width='200'>" . "Name" . "</th>";
    echo "<th align='center' width='200'>" . "Description" . "</th>";
    echo "<th align='center' width='150'>" . "Time remaining" . "</th>";
    echo "<th align='center' width='100'>" . "Task Status" . "</th>";
    echo "<th align='center' width='150'>" . "Delete Task" . "</th>";
    echo "<tr>";
    $i = 1;
    while ($row = pg_fetch_assoc($result)) {
      $timeLeft = strtotime($row['deadline']) - time();
      $days = ($timeLeft / 60 / 60 / 24) ;
      $hours = ($days - intval($days)) * 24;
      $min = ($hours - intval($hours)) * 60;
      $submissiondatetime = $row['submission_datetime'];
      echo "<td align='center' width='50'>" . $i . "</td>";
      echo "<td align='center' width='200'>" . $row['name'] . "</td>";
      echo "<td align='center' width='200'>" . $row['description'] . "</td>";
      if ($days <= 0) {
        echo "<td align='center' width='150'>" . "EXPIRED"  . "</td>";
      } else {
        echo "<td align='center' width='150'>" . intval($days)." days ". intval($hours)." hours ". intval($min)." min "  . "</td>";
      }
      echo "<td align='center' width='100'>" . $row['task_status'] . "</td>";
      if ($row['task_status'] != "confirmed" ) {
        echo "<td align='center' width='150'>" . '<p><input type="submit" name='.$row['submission_datetime'].' value = "Delete" ></p>' . "</td>";
      } else {
        echo "<td align='center' width='150'>" . "Unavailable"."</td>";
      }

      echo "</tr>";
      if (isset($_POST[$row['submission_datetime']])) {
        if ($row['task_status'] != "in progress") {
          echo '<script>alert("Cannot delete a confirmed/cancelled task");</script>';
          echo "<script>location.href='main_page.php';</script>";
        }
        /*
        $query = "DELETE FROM public.task_submission WHERE task_submission.email = '$_SESSION[user_email]' AND task_submission.submission_datetime = '$row[submission_datetime]'";
        */
        $query = "UPDATE public.task_submission SET task_status = 'cancelled' WHERE email = '$_SESSION[user_email]' AND submission_datetime = '$row[submission_datetime]'";
        $result = pg_query($db,$query);
        if ($result) {
         echo '<script>alert("Task deleted successfully");</script>';
         
       } else {
         echo '<script>alert("Error deleting task");</script>';
       }
       echo "<script>location.href='main_page.php';</script>";
     }
     $i++; } 
     echo "</table> <br>";
     ?>

   </body>
   </html>