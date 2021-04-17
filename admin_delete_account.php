<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	  <link rel="stylesheet" href="styles.css">
    <title>Delete Account</title>
</head>
<body>
<h1> Delete Account</h1>

<h2> Bid Details </h2>
<form method="post" action="admin_delete_account.php">ListAccount:
    <ul>
        Are you sure you want to delete this account? <br>
        <input type ="submit" name="yes" value='Yes'> <br>
    </ul>

    <?php
    // Connect to the database. Please change the password in the following line accordingly
    include_once("constants.php");
    echo "<form name ='display' method='POST' align='right'><a href='admin_main_page.php' class='button main'>Back to Main</a></form>";

    $db = pg_connect(FULL_DATABASE_INFO);
 
    if (isset($_POST['yes'])) {
        //$query = "DELETE FROM public.task_submission WHERE email = '$_SESSION[email]' AND submission_datetime = '$row[submission_datetime]'";
        $result = pg_query($db, "DELETE FROM public.user_info WHERE email = '$_SESSION[email]' ");
        //$result = pg_query($db,$query);
        if ($result) {
          echo "Account deleted\n";
          header(DIRECTORY."admin_main_page.php");
        } else {
          echo "Error deleting account\n";
        }
    }

    ?>
</form>
</body>
</html>
