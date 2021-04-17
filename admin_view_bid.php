<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
      <link rel="stylesheet" href="styles.css">
    <title>View All Bids</title>
</head>
<body>
<h1> TASK SOLVER </h1>

<h2> List of Bids Made </h2>
<form method="post" action="admin_view_bid.php">

    <?php
    // Connect to the database. Please change the password in the following line accordingly
    include_once("constants.php");
    $db     = pg_connect(FULL_DATABASE_INFO);

    /*$query = "SELECT * FROM public.bid_task WHERE bidder_email = '$_SESSION[user_email]' ORDER BY bid_status"; // Show only bids that are from own account
    $result = pg_query($db,$query);*/

    $query = "
	SELECT * 
	FROM public.bid_task b, public.task_submission s
	WHERE  b.submission_datetime = s.submission_datetime
	AND s.email = b.tasker_email
	ORDER BY bid_status";
    $result = pg_query($db,$query);
    echo "<div align='right'><a href='admin_main_page.php' class='button main'>Back to Main</a></div>";

    echo "<table>";
    echo "<tr>";
    echo "<td align='center' width='100'>" . "Bid no." . "</td>";
    echo "<td align='center' width='200'>" . "Tasker's Email" . "</td>";
    echo "<td align='center' width='200'>" . "Task Date & Time" . "</td>";
    echo "<td align='center' width='200'>" . "Location" . "</td>";
    echo "<td align='center' width='200'>" . "Bid Amount" . "</td>";
    echo "<td align='center' width='200'>" . "Bid Time" . "</td>";
    echo "<td align='center' width='200'>" . "Bid Status" . "</td>";
    echo "<td align='center' width='200'>" . "Update Bid" . "</td>";
    echo "<td align='center' width='200'>" . "Delete Bid" . "</td>";
    echo "<tr>";

    $i = 1;
    while ($row = pg_fetch_assoc($result)) {

        $timeLeft = strtotime($row['deadline']) - time();
        echo "<td align='center' width='100'>" . $i . "</td>";
        echo "<td align='center' width='200'>" . $row['tasker_email'] . "</td>";
        echo "<td align='center' width='200'>" . $row['task_datetime'] . "</td>";
        echo "<td align='center' width='200'>" . $row['location'] . "</td>";
        echo "<td align='center' width='200'>" . $row['bid_amt'] . "</td>";
        echo "<td align='center' width='200'>" . $row['bid_datetime'] . "</td>";
        echo "<td align='center' width='200'>" . $row['bid_status'] . "</td>";
        echo "<td align='center' width='200'>" . '<p><input type="submit" name="update_bid" value = "Update bid" ></p>' . "</td>";
        echo "<td align='center' width='200'>" . '<p><input type="submit" name="delete_bid" value = "Delete bid"></p>' . "</td>";
        echo "</tr>";
        if (isset($_POST['update_bid'])) {
            $t = time();
            $_SESSION['tasker_email'] = $row['tasker_email'];
            $_SESSION['submission_datetime'] = $row['submission_datetime'];
            $_SESSION['bidder_email'] = $row['bidder_email'];
            $_SESSION['bid_amt'] = $row['bid_amt'];
                echo "<script>location.href='admin_update_bid.php';</script>";
        } 
        if (isset($_POST['delete_bid'])){
            $_SESSION['tasker_email'] = $row['tasker_email'];
            $_SESSION['submission_datetime'] = $row['submission_datetime'];
            $_SESSION['bidder_email'] = $row['bidder_email'];
            echo "<script>location.href='admin_delete_bid.php';</script>";
        } 
        
        $i++;

    }

    echo "</table>";

    ?>
</form>
</body>
</html>