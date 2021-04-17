<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
      <link rel="stylesheet" href="styles.css">
	<title>View Accounts</title>
</head>
<body>
	<h1> TASK SOLVER </h1>
	<h2> List of Accounts </h2>
<form method="post" action="admin_view_account.php">

    <?php
    include_once("constants.php");
    $db     = pg_connect(FULL_DATABASE_INFO);
    $query = "SELECT * FROM public.user_info WHERE isadmin = false";
    $result = pg_query($db, $query);
    echo "<div align='right'><a href='admin_main_page.php' class='button main'>Back to Main</a></div>";

    echo "<table>";
    echo "<tr>";
    echo "<td align='center' width='100'>" . "No." . "</td>";
    echo "<td align='center' width='200'>" . "Full Name" . "</td>";
    echo "<td align='center' width='200'>" . "Display Name" . "</td>";
    echo "<td align='center' width='200'>" . "Phone Number" . "</td>";
    echo "<td align='center' width='200'>" . "Email" . "</td>";
    echo "<td align='center' width='200'>" . "Password" . "</td>";
    echo "<td align='center' width='200'>" . "Update Account" . "</td>";
    echo "<td align='center' width='200'>" . "Delete Account" . "</td>";
    echo "<tr>";

    $i = 1;
    $j = 1;
    while ($row = pg_fetch_assoc($result)) {
        echo "<td align='center' width='100'>" . $i . "</td>";
        echo "<td align='center' width='200'>" . $row['full_name'] . "</td>";
        echo "<td align='center' width='200'>" . $row['display_name'] . "</td>";
        echo "<td align='center' width='200'>" . $row['phone_number'] . "</td>";
        echo "<td align='center' width='200'>" . $row['email'] . "</td>";
        echo "<td align='center' width='200'>" . $row['password'] . "</td>";
        echo "<td align='center' width='200'>" . '<p><input type="submit" name='.$j.' value ="Update Account"></p>' . "</td>";
        if (isset($_POST[$j])) {
            $_SESSION['full_name'] = $row['full_name'];
            $_SESSION['display_name'] = $row['display_name'];
            $_SESSION['phone_number'] = $row['phone_number'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['password'] = $row['password'];
            $_SESSION['isadmin'] = $row['isadmin'];
             echo "<script>location.href='admin_update_account.php';</script>";
        }
        echo "<td align='center' width='200'>" . '<p><input type="submit" name='.$i.' value ="Delete Account"</p>'. "</td>";
        if (isset($_POST[$i])){
            $_SESSION['email'] = $row['email'];
    
            echo "<script>location.href='admin_delete_account.php';</script>";
            //echo "yass";
        }
        
        echo "</tr>";
        $i++; $j++;
        }

    echo "</table>";

    ?>
</form>
</html>