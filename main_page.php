<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <title>User Main Page</title>
</head>
<body>
<h1> TASK SOLVER </h1>
<h2> Submit task or Submit bid?</h2>
<ul>
    <form name="display" method="POST" align="center">
        <input type="submit" name="submit_task" value="Create New Task"> &#09
        <input type="submit" name="list_my_task" value="Task History"> &#09
        <input type="submit" name="update_task" value="Update Task"> &#09
        <input type="submit" name="delete_task" value="Delete Task"> &#09
        <input type="submit" name="view_task_list" value="View Available Tasks"> &#09
        <input type="submit" name="list_my_bids" value="View/Update My Bids"> &#09
        <input type="submit" name="accept_bid" value="Accept a Bid"> &#09
        <br>
    </form>
</ul>

<p>

    <?php
    // Connect to the database. Please change the password in the following line accordingly
    include_once("constants.php");
    $db = pg_connect(FULL_DATABASE_INFO);
    $query = "SELECT display_name FROM public.user_info WHERE email = '$_SESSION[user_email]'";
    $result = pg_query($db, $query);
    $row = pg_fetch_row($result);
    echo "<div align='right'; style='vertical-align:top;font-size:1.5em;color:black'>$row[0]</div>";
    echo "<div align='right'; style='vertical-align:top;font-size:1em;color:black'>$_SESSION[user_email]</div>";
    echo "<form name ='display' method='POST' align='right'><a href='sign_in.php' class='button logout'>Logout</a></form>";
    echo "<br>";

    if (isset($_POST['submit_task'])) {
        header(DIRECTORY . "task_submission.php");
    } else if (isset($_POST['update_task'])) {
        header(DIRECTORY . "update_task.php");
    } else if (isset($_POST['view_task_list'])) {
        header(DIRECTORY . "list_task.php");
    } else if (isset($_POST['delete_task'])) {
        header(DIRECTORY . "delete_task.php");
    } else if (isset($_POST['list_my_bids'])) {
        header(DIRECTORY . "list_myBids.php");
    } else if (isset($_POST['list_my_task'])) {
        header(DIRECTORY . "list_my_task.php");
    } else if (isset($_POST['accept_bid'])) {
        header(DIRECTORY . "accept_bid.php");
    }

    if (isset($_POST['logout'])) {
        header(DIRECTORY . "sign_in.php");
    }

    $query = "SELECT * FROM public.task_submission WHERE task_submission.email = '$_SESSION[user_email]' 
    AND task_submission.task_status = 'in progress' AND task_submission.deadline >= (select now())
    ORDER BY task_submission.deadline ASC";

    $result = pg_query($db, $query);

    echo "<h2> Active Task Submissions </h2>";
    echo "<table>";
    echo "<tr>";
    echo "<th align='center' width='30'>" . "No." . "</th>";
    echo "<th align='center' width='200'>" . "TaskName" . "</th>";
    echo "<th align='center' width='200'>" . "Description" . "</th>";
    echo "<th align='center' width='200'>" . "Location" . "</th>";
    echo "<th align='center' width='200'>" . "End Location" . "</th>";
    echo "<th align='center' width='150'>" . "Datetime" . "</th>";
    echo "<th align='center' width='100'>" . "Maximum Amount" . "</th>";
    //echo "<th align='center' width='200'>" . "Tasker Email" . "</th>";
    echo "<th align='center' width='200'>" . "Time remaining" . "</th>";
    echo "<th align='center' width='200'>" . "Task Status" . "</th>";
    //echo "<th align='center' width='200'>" . "Submit" . "</th>";
    echo "<th align='center' width='100'>" . "No. of bidders" . "</th>";
    echo "<th align='center' width='100'>" . "Lowest bid" . "</th>";
    echo "</tr>";
    $i = 1;
    while ($row = pg_fetch_assoc($result)) {
        echo "<tr>";
        $timeLeft = strtotime($row['deadline']) - time();
        $days = ($timeLeft / 60 / 60 / 24);
        $hours = ($days - intval($days)) * 24;
        $min = ($hours - intval($hours)) * 60;
        $submissiondatetime = $row['submission_datetime'];
        echo "<td align='center' width='30'>" . $i . "</td>";
        echo "<td align='center' width='200'>" . $row['name'] . "</td>";
        echo "<td align='center' width='200'>" . $row['description'] . "</td>";
        echo "<td align='center' width='200'>" . $row['location'] . "</td>";
        echo "<td align='center' width='200'>" . $row['end_location'] . "</td>";
        echo "<td align='center' width='150'>" . $row['task_datetime'] . "</td>";
        echo "<td align='center' width='100'>" . $row['max_amt'] . "</td>";
        //echo "<td align='center' width='200'>" . $row['email'] . "</td>";
        if ($days <= 0) {
            echo "<td align='center' width='200'>" . "EXPIRED" . "</td>";
        } else {
            echo "<td align='center' width='200'>" . intval($days) . " days " . intval($hours) . " hours " . intval($min) . " min " . "</td>";
        }
        echo "<td align='center' width='200'>" . $row['task_status'] . "</td>";

        $bidquery = "SELECT COUNT(*) FROM public.bid_task WHERE bid_task.tasker_email = '$_SESSION[user_email]' 
          AND bid_task.submission_datetime = '$submissiondatetime'";
        $roww = pg_query($db, $bidquery);
        $numBidders = pg_fetch_row($roww);


        echo "<td align='center' width='100'>" . $numBidders[0] . "</td>";

        $lowestBidQuery = "SELECT MIN(bid_task.bid_amt) AS min_bid FROM public.bid_task WHERE bid_task.tasker_email = '$_SESSION[user_email]' AND bid_task.submission_datetime = '$submissiondatetime'";
        $ans = pg_query($db, $lowestBidQuery);
        $lowestBid = pg_fetch_assoc($ans);

        
        echo "<td align='center' width='100'>" . $lowestBid['min_bid'] . "</td>";
        echo "</tr>";
        $i++;
    }
    echo "</table>";

    $query = "
    SELECT * 
    FROM public.bid_task b
    INNER JOIN public.task_submission s
    ON b.tasker_email = s.email AND b.submission_datetime = s.submission_datetime
    WHERE b.bidder_email = '$_SESSION[user_email]' 
    AND b.bid_status = 'in progress'
    ORDER BY s.deadline ASC";
    $result = pg_query($db,$query);

    echo "<h2> Active Bid Submissions </h2>";
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
        $i++;
        echo "</tr>";
    }
     echo "</table>";

    ?>
</body>
</html>