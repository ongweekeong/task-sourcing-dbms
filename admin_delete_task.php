<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
      <link rel="stylesheet" href="styles.css">
    <title>Delete Task</title>
</head>
<body>
<h1> Delete bids</h1>

<h2> Bid Details </h2>
<form method="post" action="admin_delete_task.php">ListTask:
    <ul>
        Are you sure you want to delete this task? <input type ="submit" name="yes" value='Yes'> <br>
    </ul>

    <?php
    // Connect to the database. Please change the password in the following line accordingly
    include_once("constants.php");
    $db = pg_connect(FULL_DATABASE_INFO);
    //echo "1233\n";
    echo "$_SESSION[email]\n";
    //echo "$_SESSION[submission_datetime]";
    if (isset($_POST['yes'])) {
        //$query = "DELETE FROM public.task_submission WHERE email = '$_SESSION[email]' AND submission_datetime = '$row[submission_datetime]'";
        $result = pg_query($db, "DELETE FROM public.task_submission WHERE email = '$_SESSION[email]' AND submission_datetime = '$_SESSION[submission_datetime]'");
        //$result = pg_query($db,$query);
        if ($result) {
          echo "Task deleted\n";
          header(DIRECTORY."admin_main_page.php");
        } else {
          echo "Error deleting task\n";
        }
    }
    echo "<div align='right'><a href='admin_main_page.php' class='button main'>Back to Main</a></div>";

    ?>
</form>
</body>
</html>
