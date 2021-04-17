<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <title>My Bids</title>
</head>
<body>
<h1> TASK SOLVER </h1>
<h2> List of Bids Made </h2>
<form method="post" action="list_myBids.php">
    <br>
    <?php

    include_once("constants.php");
    $db     = pg_connect(FULL_DATABASE_INFO);
    $query = "
    SELECT * 
    FROM public.bid_task b, public.task_submission s
    WHERE b.bidder_email = '$_SESSION[user_email]' 
    AND b.submission_datetime = s.submission_datetime
    AND s.email = b.tasker_email
    ORDER BY bid_status";
    $result = pg_query($db,$query);
    echo "<div align='right'><a href='main_page.php' class='button main'>Back to Main</a></div>";
    echo "<table>";
    echo "<tr>";
    echo "<td align='center' width='30'>" . "Bid no." . "</td>";
    echo "<td align='center' width='200'>" . "Tasker Name" . "</td>";
    echo "<td align='center' width='200'>" . "Task Name" . "</td>";
    echo "<td align='center' width='200'>" . "Description" . "</td>";
    echo "<td align='center' width='150'>" . "Task Date & Time" . "</td>";
    echo "<td align='center' width='200'>" . "Location" . "</td>";
    echo "<td align='center' width='100'>" . "Bid Amount" . "</td>";
    echo "<td align='center' width='150'>" . "Bid Time" . "</td>";
    echo "<td align='center' width='200'>" . "Bid Status" . "</td>";
    echo "<td align='center' width='200'>" . "Update Bid" . "</td>";
    echo "<td align='center' width='200'>" . "Delete Bid" . "</td>";
    echo "</tr>";

    $i = 1;
    while ($row = pg_fetch_assoc($result)) {
        $nameQuery = "
            SELECT display_name
            FROM public.user_info
            WHERE email = '$row[tasker_email]'";
        $name = pg_query($db, $nameQuery);
        $namee = pg_fetch_row($name);
        $timeLeft = strtotime($row['deadline']) - time();
        echo "<tr>";
        echo "<td align='center' width='30'>" . $i . "</td>";
        echo "<td align='center' width='200'>" . $namee[0] . "</td>";
        echo "<td align='center' width='200'>" . $row['name'] . "</td>";
        echo "<td align='center' width='200'>" . $row['description'] . "</td>";
        echo "<td align='center' width='150'>" . $row['task_datetime'] . "</td>";
        echo "<td align='center' width='200'>" . $row['location'] . "</td>";
        echo "<td align='center' width='100'>" . $row['bid_amt'] . "</td>";
        echo "<td align='center' width='150'>" . $row['bid_datetime'] . "</td>";
        echo "<td align='center' width='200'>" . $row['bid_status'] . "</td>";
        echo "<td align='center' width='200'>" . '<p><input type="submit" name=' . $row['submission_datetime'] . ' value = "Update bid" ></p>' . "</td>";
        echo "<td align='center' width='200'>" . '<p><input type="submit" name="delete_bid" value = "Delete bid"></p>' . "</td>";
        echo "</tr>";
        if (isset($_POST[$row['submission_datetime']])) {
            if ($timeLeft <= 0) {
                echo '<script>alert(" Update error: Bidding period has ended.");</script>';
                echo "<script>location.href='list_myBids.php';</script>";
            }
            else {
                $_SESSION['tasker_email'] = $row['tasker_email'];
                $_SESSION['submission_datetime'] = $row['submission_datetime'];
                $_SESSION['task_name'] = $row['name'];
                $_SESSION['task_desc'] = $row['description'];
                $_SESSION['task_datetime'] = $row['task_datetime'];
                $_SESSION['previous_bid'] = $row['bid_amt'];
                echo "<script>location.href='update_bids.php';</script>";
                //header(DIRECTORY."delete_my_bid.php");

            }
        }
        if (isset($_POST['delete_bid'])){
            if($row['bid_status'] != 'in progress'){
                echo '<script>alert("Delete error: Bidding period has ended.");</script>';
                echo "<script>location.href='list_myBids.php';</script>";
            }
            else{
                $_SESSION['tasker_email'] = $row['tasker_email'];
                $_SESSION['submission_datetime'] = $row['submission_datetime'];
                $_SESSION['task_name'] = $row['name'];
                $_SESSION['task_desc'] = $row['description'];
                $_SESSION['task_datetime'] = $row['task_datetime'];
                $_SESSION['previous_bid'] = $row['bid_amt'];
                $_SESSION['bid_status'] = $row['bid_status'];
                echo "<script>location.href='delete_my_bid.php';</script>";
            }
        }
        $i++;

    }

    echo "</table>";

    ?>
</form>
</body>
</html>