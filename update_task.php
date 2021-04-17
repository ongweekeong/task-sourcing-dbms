<?php
session_start();
?>
<!DOCTYPE html>
<head>
    <link rel="stylesheet" href="styles.css">
    <title>UPDATE PostgreSQL data with PHP</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style>
  li {list-style: none;}
</style>
</head>
<body>

<h1> TASK SOLVER </h1>
  <h2>Update a Task</h2>
  <form method="post" action="update_task.php">
    <?php
    include_once("constants.php");
    $db     = pg_connect(FULL_DATABASE_INFO);

    $query = "SELECT * FROM public.task_submission WHERE task_submission.email = '$_SESSION[user_email]' ORDER BY task_submission.deadline DESC";
    //this query shows all the tasks by the user sorted by deadline

    $res = pg_query($db,$query);

    echo "<div align='right'>$_SESSION[user_email]</div>";
    echo "<div align='right'><a href='main_page.php' class='button main'>Back to Main</a></div>";
    echo "<table>";
    echo "<tr>";
    echo "<td align='center' width='50'>" . "No." . "</td>";
    echo "<td align='center' width='200'>" . "Name" . "</td>";
    echo "<td align='center' width='200'>" . "Description" . "</td>";
    echo "<td align='center' width='150'>" . "Time remaining" . "</td>";
    echo "<td align='center' width='100'>" . "Task Status" . "</td>";
    echo "<td align='center' width='125'>" . "Update Task" . "</td>";
    echo "<tr>";
    $i = 1;
    while ($row = pg_fetch_assoc($res)) {
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
        echo "<td align='center' width='100'>" . intval($days)." days ". intval($hours)." hours ". intval($min)." min "  . "</td>";
      }
      echo "<td align='center' width='125'>" . $row['task_status'] . "</td>";

      if (($row['task_status'] == "in progress") && ($days > 0)) {
        echo "<td align='center' width='200'>" . '<p><input type="submit" name='.$row['submission_datetime'].' value = "Update" ></p>' . "</td>";
      } else {
        echo "<td align='center' width='200'>" . "Unavailable"."</td>";
      }
      echo "</tr>"; 
      
      if (isset($_POST[$row['submission_datetime']])) {
        if ($row['task_status'] == "confirmed") {
          echo '<script>alert("cannot update a confirmed taask");</script>';
          echo "<script>location.href='main_page.php';</script>"; 
        }
        $result = pg_query($db, "SELECT name, description, location, end_location,task_datetime, max_amt, email, deadline, submission_datetime, end_datetime, task_status FROM public.task_submission WHERE email ='$_SESSION[user_email]' 
          AND task_submission.submission_datetime = '$row[submission_datetime]'"); 
        //this task retrieves the task values for the task the user requested

    $row = pg_fetch_assoc($result);   // To store the result row

    echo "<ul><form name='update' method='POST'>
    <li>name:</li>
    <li><input type='text' name='u_name' value='$row[name]' /></li>
    <li>Description:</li>
    <li><input type='text' name='u_description' value='$row[description]' /></li>
    <li>location:</li>
    <li><input type='text' name='u_location' value='$row[location]' /></li>
    <li>End Location:</li>
    <li><input type='text' name='u_end_location' value='$row[end_location]' /></li>
    <li>Datetime:</li>
    <li><input type='text' name='u_task_datetime' value='$row[task_datetime]' /></li>
    <li>End Datetime:</li>
    <li><input type='text' name='u_end_datetime' value='$row[end_datetime]' /></li>
    <li>Maximum Bid:</li>
    <li><input type='text' name='u_max_amt' value='$row[max_amt]' /></li>
    <li>Deadline</li>
    <li><input type='text' name='u_deadline' value='$row[deadline]' /></li>
    <li><input type='submit' name='new' /></li>
    </form>
    </ul>";
  }  
  $i++; 
} 
echo "</table>";
echo "<br>";

  if (isset($_POST['new'])) { // Submit the update SQL command
    //do the neccessary checks in php to ensure the entries are correct
    if (empty($_POST["u_name"])) {
      echo '<script>alert("name cannot be empty");</script>';
      echo "<script>location.href='update_task.php';</script>"; 
    } 
    if (empty($_POST["u_location"])) {
      echo '<script>alert("location cannot be empty");</script>';
      echo "<script>location.href='update_task.php';</script>"; 
    } 
    if (empty($_POST["u_task_datetime"])) {
      echo '<script>alert("task datetime cannot be empty");</script>';
      echo "<script>location.href='update_task.php';</script>"; 
    } 
    if ($_POST["u_task_datetime"] > $_POST["u_end_datetime"]) {
      echo '<script>alert("end_datetime cannot be after task_endtime");</script>';
      echo "<script>location.href='update_task.php';</script>"; 
    }
    if ($_POST["u_task_datetime"] > $_POST["u_end_datetime"]) {
      echo '<script>alert("task_datetime cannot be after task_endtime");</script>';
      echo "<script>location.href='update_task.php';</script>"; 
    } else if ($_POST["u_deadline"] > $_POST["u_task_datetime"]) {
      echo '<script>alert("deadline cannot be after task_datetime");</script>';
      echo "<script>location.href='update_task.php';</script>";       
    } else if (time() > strtotime($_POST["u_deadline"])) {
      echo '<script>alert("deadline already over");</script>';
      echo "<script>location.href='update_task.php';</script>";       
    } else {
      //update the database with the new details 
      $result = pg_query($db, "UPDATE public.task_submission SET name = '$_POST[u_name]', description = '$_POST[u_description]',location = '$_POST[u_location]',end_location = '$_POST[u_end_location]',task_datetime = '$_POST[u_task_datetime]', end_datetime = '$_POST[u_end_datetime]', max_amt = '$_POST[u_max_amt]', deadline = '$_POST[u_deadline]' WHERE email ='$_SESSION[user_email]' AND task_datetime = '$_POST[u_task_datetime]' AND location = '$_POST[u_location]'");
      if (!$result) {
        echo '<script>alert("update failed");</script>';
        echo "<script>location.href='update_task.php';</script>"; 
      } else {
        echo '<script>alert("update successful");</script>';
        echo "<script>location.href='update_task.php';</script>"; 
      }
    }
  }
  ?>
</form>
</body>
</html>

