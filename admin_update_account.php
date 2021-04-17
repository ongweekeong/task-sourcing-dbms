<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <title>Admin Update Account</title>
</head>
<body>
<h1> Admin Update Account</h1>

<h2> Account Details</h2>
<form method="post" action="admin_update_account.php">
    
    <?php
    // Connect to the database. Please change the password in the following line accordingly
    include_once("constants.php");
    echo "<form name ='display' method='POST' align='right'><a href='admin_main_page.php' class='button main'>Back to Main</a></form>";
    $db     = pg_connect(FULL_DATABASE_INFO);
       echo "<ul><form name='update' method='POST'>

       <li>Full Name:</li>
       <li><input type='text' name='full_name' value='$_SESSION[full_name]' /></li>
       <li>Display Name:</li>
       <li><input type='text' name='display_name' value='$_SESSION[display_name]' /></li>
       <li>Phone Number:</li>
       <li><input type='text' name='phone_number' value='$_SESSION[phone_number]' /></li>
       <li>Email:</li>
       <li><input type='text' name='email' value='$_SESSION[email]' /></li>
       <li>Password:</li>
       <li><input type='text' name='password' value='$_SESSION[password]' /></li>
       <li>is admin?:</li>
       <li><input type='text' name='isadmin' value='$_SESSION[isadmin]' /></li>
       <li><input type='submit' name='new' /></li>
       </form>
       </ul>";
       
    
    if (isset($_POST['new'])) {
      $t = time();
        $result = pg_query($db, "UPDATE public.user_info SET full_name = '$_POST[full_name]', display_name = '$_POST[display_name]' , phone_number = '$_POST[phone_number]' , email = '$_POST[email]', password = '$_POST[password]',  isadmin = '$_POST[isadmin]'WHERE email = '$_SESSION[email]' ");
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