<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="styles.css">
  <title>Task Submission</title>
</head>
<body>

<h1> TASK SOLVER </h1>
<h2> Submission of Task </h2>
<ul>
    <?php echo "<div align='right'>$_SESSION[user_email]\n</div>"; ?>
    <div name ='display' method='POST' align='right'><a href='main_page.php' class='button main'>Back to Main</a></div>

    <form name ="display" action="task_submission.php" method="POST">
      Name of Task: <br>
      <input type ="text" name = "name" value='<?php if (isset($_POST[name])) { echo "$_POST[name]"; }?>'> <br>
      <br>
      Description: <br>
      <input type = "text" name = "description" value='<?php if (isset($_POST[description])) { echo "$_POST[description]"; }?>'> <br>
      <br>
      Location: <br>
      <input type = "text" name = "location" value='<?php if (isset($_POST[location])) { echo "$_POST[location]"; }?>'> <br>
      <br>
      End Location: <br>
      <input type = "text" name = "end_location" value='<?php if (isset($_POST[end_location])) { echo "$_POST[end_location]"; }?>'> <br>
      <br>
      Datetime: <br>
      <input type = "datetime-local" name = "datetime" value='<?php if (isset($_POST[datetime])) { echo "$_POST[datetime]"; }?>'> <br>
      <br>
      End Datetime: <br>
      <input type= "datetime-local" name= "end_datetime" value='<?php if (isset($_POST[end_datetime])) { echo "$_POST[end_datetime]"; }?>'> <br>
      <br>
      Maximum Amount: <br>
      <input type = "text" name = "max_amt"> <br>
      <br>
      Deadline: <br>
      <input type="datetime-local" name="deadline"> <br>
      <br>
      <input type= "submit" name="submit">
    </form>
  </ul>

  <?php
// Connect to the database. Please change the password in the following line accordingly
  include_once("constants.php");
  $db     = pg_connect(FULL_DATABASE_INFO);
  $t = time();

if (isset($_POST['submit'])) {

    if (empty($_POST['description'])) {
      $_POST['description'] = NULL;
    }
    if (empty($_POST['end_location'])) {
      $_POST['end_location'] = $_POST['location'];
    }
    if (empty($_POST['max_amt'])) {
      $_POST['max_amt'] = NULL;
    }
    if (empty($_POST['deadline'])) {
      $_POST['deadline'] = $_POST['datetime'];
    }
    if (empty($_POST['end_datetime'])) {
      $_POST['end_datetime'] = $_POST['datetime'];
    }

    $query = "INSERT INTO public.task_submission VALUES ( '$_POST[name]', '$_POST[description]', '$_POST[location]',
    '$_POST[end_location]', '$_POST[datetime]', '$_POST[max_amt]', 
    '$_SESSION[user_email]', '$_POST[deadline]', '$t', 
    '$_POST[end_datetime]', 'in progress')";
    
    $result = pg_query($db,$query);

    if ($result) {
      echo '<script>alert("Task submitted");</script>';
      echo "<script>location.href='main_page.php';</script>";
    }
    else {
      echo '<script>alert("Task submission failed");</script>';
      echo "<script>location.href='main_page.php';</script>";
    }
  } 

  ?>

</body>
</html>
