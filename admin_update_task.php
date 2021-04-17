<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">

    <title>Admin Update Task</title>
</head>
<body>
<h1> Admin Update Task</h1>

<h2> Bid Details</h2>
<form method="post" action="admin_update_task.php">ListTask:
    
    <?php
    // Connect to the database. Please change the password in the following line accordingly
    include_once("constants.php");
    $db     = pg_connect(FULL_DATABASE_INFO);
       echo "<ul><form name='update' method='POST'>";
       echo "<form name ='display' method='POST' align='right'><a href='admin_main_page.php' class='button main'>Back to Main</a></form>";

      echo "<li>Task Name:</li>
       <li><input type='text' name='name' value='$_SESSION[name]' /></li>
       <li>Task Description:</li>
       <li><input type='text' name='description' value='$_SESSION[description]' /></li>
       <li>Task Location:</li>
       <li><input type='text' name='location' value='$_SESSION[location]' /></li>
       <li>Task End Location:</li>
       <li><input type='text' name='end_location' value='$_SESSION[end_location]' /></li>
       <li>Task Datetime:</li>
       <li><input type='text' name='task_datetime' value='$_SESSION[task_datetime]' /></li>
       <li>Max Bid Amt:</li>
       <li><input type='text' name='max_amt' value='$_SESSION[max_amt]' /></li>
       <li>Email:</li>
       <li><input type='text' name='email' value='$_SESSION[email]' /></li>
       <li>Deadline</li>
       <li><input type='text' name='deadline' value='$_SESSION[deadline]' /></li>
       <li>End Datetime</li>
       <li><input type='text' name='end_datetime' value='$_SESSION[end_datetime]' /></li>
       <li>Task Status</li>
       <li><input type='text' name='task_status' value='$_SESSION[task_status]' /></li>
       <li><input type='submit' name='new' /></li>
       </form>
       </ul>";
       
    
    if (isset($_POST['new'])) {
      $t = time();
        $result = pg_query($db, "UPDATE public.task_submission SET name = '$_POST[name]', description = '$_POST[description]' , location = '$_POST[location]' , end_location = '$_POST[end_location]', task_datetime = '$_POST[task_datetime]',  max_amt = '$_POST[max_amt]', email = '$_POST[email]' ,  deadline = '$_POST[deadline]' , submission_datetime = '$t', end_datetime = '$_POST[end_datetime]' , task_status = '$_POST[task_status]' WHERE email = '$_SESSION[email]' AND submission_datetime = '$_SESSION[submission_datetime]' ");
        if ($result) {
            echo "Updated!\n";
            header(DIRECTORY."admin_main_page.php");
        }
        else {
            echo "Update failed.\n";
        }
    }

    ?>
</form>
</body>
</html>