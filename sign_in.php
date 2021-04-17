<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <title>Sign In Page</title>
</head>
<body>
<h1> TASK SOLVER </h1>
<h2> Sign In page </h2>
<ul>
    <form name ="display" action="sign_in.php" method="POST">
        Email: <br>
        <input type ="text" name = "email"> <br>
        <br>
        Password: <br>
        <input type = "password" name = "password"> <br>
        <br>
        <input type= "submit" name="submit">
        <br>
        <div name ='display' method='POST'><a href='sign_up.php'>First time here? Sign up!</a></div>
</ul>

<?php
// Connect to the database. Please change the password in the following line accordingly
include_once("constants.php");
$db     = pg_connect(FULL_DATABASE_INFO);

if (isset($_POST['submit'])) {
    $query = "SELECT * FROM public.user_info 
    WHERE email = '$_POST[email]' AND password = '$_POST[password]') ";
    $result = pg_query($db,$query);

    if ($result) {
        echo '<script>alert("Successful");</script>';
        $_SESSION['user_email'] = $_POST['email'];
        header(DIRECTORY."main_page.php");
        /*	$t=time();
            echo($t . "<br>");
            echo(date("Y-m-d",$t));*/
    }
    else {
        echo '<script>alert("Try again!");</script>';
    }
}

?>
</form>
</body>
</html>