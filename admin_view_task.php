<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
  <title>View Submitted Tasks</title>
</head>
<body>
  <h1> TASK SOLVER</h1>

  <h2> View Task Details</h2>
  <form method="post" action="admin_view_task.php">
    <?php
    // Connect to the database. Please change the password in the following line accordingly
    include_once("constants.php");
    $db     = pg_connect(FULL_DATABASE_INFO);

    $query = "SELECT * FROM public.task_submission ORDER BY deadline DESC";
    $result = pg_query($db,$query);
    echo "<h2> List of submitted tasks </h2>";
    echo "<div' align='right'><a href='admin_main_page.php' class='button main'>Back to Main</a></div>";
    echo "<table>";
    echo "<tr>";
    echo "<td align='center' width='200'>" . "Number" . "</td>";
    echo "<td align='center' width='200'>" . "Tasker Email" . "</td>";
    echo "<td align='center' width='200'>" . "Name" . "</td>";
    echo "<td align='center' width='200'>" . "Description" . "</td>";
    echo "<td align='center' width='200'>" . "Time remaining" . "</td>";
    echo "<td align='center' width='200'>" . "Task Status" . "</td>";
    echo "<td align='center' width='200'>" . "Update Task" . "</td>";
    echo "<td align='center' width='200'>" . "Delete Task" . "</td>";
    echo "</tr>";
    $i = 1;
    while ($row = pg_fetch_assoc($result)) {
      $timeLeft = strtotime($row['deadline']) - time();
      $days = ($timeLeft / 60 / 60 / 24) ;
      $hours = ($days - intval($days)) * 24;
      $min = ($hours - intval($hours)) * 60;
      $submissiondatetime = $row['submission_datetime'];
      echo "<tr>";
      echo "<td align='center' width='200'>" . $i . "</td>";
      echo "<td align='center' width='200'>" . $row['email'] . "</td>";
      echo "<td align='center' width='200'>" . $row['name'] . "</td>";
      echo "<td align='center' width='200'>" . $row['description'] . "</td>";
      if ($days <= 0) {
        echo "<td align='center' width='200'>" . "EXPIRED"  . "</td>";
      } else {
        echo "<td align='center' width='200'>" . intval($days)." days ". intval($hours)." hours ". intval($min)." min "  . "</td>";
      }
      echo "<td align='center' width='200'>" . $row['task_status'] . "</td>";
      echo "<td align='center' width='200'>" .'<p><input type="submit" name='.$row['submission_datetime'].' value = "Update" ></p>' . "</td>";
      if (isset($_POST[$row['submission_datetime']])) {  
        $_SESSION['name'] = $row['name'];
        $_SESSION['description'] = $row['description'];
        $_SESSION['location'] = $row['location'];
        $_SESSION['end_location'] = $row['end_location'];
        $_SESSION['task_datetime'] = $row['task_datetime'];
        $_SESSION['max_amt'] = $row['max_amt'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['deadline'] = $row['deadline'];
        $_SESSION['submission_datetime'] = $row['submission_datetime'];
        $_SESSION['end_datetime'] = $row['end_datetime'];
        $_SESSION['task_status'] = $row['task_status'];
        echo "<script>location.href='admin_update_task.php';</script>";
      }
      echo "<td align='center' width='200'>" .'<p><input type="submit" name='.$i.' value = "Delete" ></p>' . "</td>";
      /*echo "<td align='center' width='200'>" . '<p><input type="submit" name='."{$i} update_task"."value = "Update" ></p>" . "</td>";
      echo "<td align='center' width='200'>" . '<p><input type="submit" name='."{$i} delete_task"." value = "Delete" ></p>" . "</td>";*/

      if (isset($_POST[$i])) {
            $_SESSION['email'] = $row['email'];
            $_SESSION['submission_datetime'] = $row['submission_datetime'];
            echo "<script>location.href='admin_delete_task.php';</script>";
      }
      echo "</tr>";
      $i++; } 
      echo "</table>";
      ?>
    </form>
    </body>
    </html>