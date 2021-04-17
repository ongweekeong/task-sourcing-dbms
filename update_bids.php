<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <title>Update Bids</title>
</head>
<body>
<h1> Update bids</h1>

<h2> Bid Details</h2>
<form method="post" action="update_bids.php">
    <ul>
        Bid amount: <br>
        <input type ="text" name = "bid_amt"> <br>
        <br>
        <input type= "submit" name="submit">
    </ul>

    <?php
    echo "<div name ='display' method='POST' align='right'><a href='main_page.php' class='button main'>Back to Main</a></div>";
    echo "<br>";
    echo "<table>";
    echo "<tr>";
    echo "<td align='center' width='200'>" . "Task Name" . "</td>";
    echo "<td align='center' width='200'>" . "Description" . "</td>";
    echo "<td align='center' width='200'>" . "Date & Time" . "</td>";
    echo "<td align='center' width='200'>" . "Previous Bid" . "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td align='center' width='200'>" . $_SESSION['task_name'] . "</td>";
    echo "<td align='center' width='200'>" . $_SESSION['task_desc'] . "</td>";
    echo "<td align='center' width='200'>" . $_SESSION['task_datetime'] . "</td>";
    echo "<td align='center' width='200'>" . $_SESSION['previous_bid'] . "</td>";
    echo "</tr>";
    echo "</table><br>";
    // Connect to the database. Please change the password in the following line accordingly
    include_once("constants.php");
    $db     = pg_connect(FULL_DATABASE_INFO);

    if (isset($_POST['submit'])) {
        $result = pg_query($db, "UPDATE public.bid_task SET bid_amt = '$_POST[bid_amt]', bid_datetime = (select now()) WHERE bidder_email = '$_SESSION[user_email]'AND submission_datetime = '$_SESSION[submission_datetime]' AND tasker_email = '$_SESSION[tasker_email]'");
        if ($result) {
            echo '<script>alert("Bid update successful!");</script>';
            header(DIRECTORY."list_myBids.php");
        }
        else {
            echo '<script>alert("Bid update failed!");</script>';
            header(DIRECTORY."list_myBids.php");
        }
    }

    ?>
</form>
</body>
</html>