<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="styles.css">
  <title>Bidder Profile</title>
</head>
<body>
  <h1> Task Solver </h1>
  <form method = "post" action = "view_bidder_profile.php">

    <?php
    echo "<h1> User: $_SESSION[bidder_name] </h1>";
    echo "<div align='right'>$_SESSION[user_email] </div>";
    echo "<div align='right'><a href='main_page.php' class='button main'>Back to Main</a></div><br>";
    echo "<h2> Task Submission History </h2>";
    // Connect to the database. Please change the password in the following line accordingly
    include_once("constants.php");
    $db     = pg_connect(FULL_DATABASE_INFO);
    $taskDetails = "SELECT * FROM public.task_submission WHERE email = '$_SESSION[bidder_email]'";
    $taskDetail = pg_query($db,$taskDetails);
    //echo "$_SESSION[user_email]";
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
    echo "</tr>";
    $i = 1;
    while ($row = pg_fetch_assoc($taskDetail)){
            echo "<tr>";
        $timeLeft = strtotime($row['deadline']) - time();
        $days = ($timeLeft / 60 / 60 / 24) ;
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
            echo "<td align='center' width='200'>" . "EXPIRED"  . "</td>";
        } else {
            echo "<td align='center' width='200'>" . intval($days)." days ". intval($hours)." hours ". intval($min)." min "  . "</td>";
        }
        echo "<td align='center' width='200'>" . $row['task_status'] . "</td>";
        $i++;
        echo "</tr>";
    }

    echo "</table>";

    echo "<h2> Bids submitted </h2>";

    echo "<table>";
    echo "<tr>";
    echo "<td align='center' width='30'>" . "Bid no." . "</td>";
    echo "<td align='center' width='200'>" . "Task Name" . "</td>";
    echo "<td align='center' width='200'>" . "Description" . "</td>";
    echo "<td align='center' width='150'>" . "Task Date & Time" . "</td>";
    echo "<td align='center' width='200'>" . "Location" . "</td>";
    echo "<td align='center' width='100'>" . "Bid Amount" . "</td>";
    echo "<td align='center' width='200'>" . "Bid Status" . "</td>";
    echo "</tr>";

    $query = " SELECT * FROM public.bid_task b, public.task_submission s
    WHERE b.bidder_email = '$_SESSION[bidder_email]' 
    AND s.email = b.tasker_email
    AND s.submission_datetime = b.submission_datetime
    ORDER BY bid_status";

    $result = pg_query($db,$query);

   	$i = 1;
    while ($bid = pg_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td align='center' width='30'>" . $i . "</td>";
        echo "<td align='center' width='200'>" . $bid['name'] . "</td>";
        echo "<td align='center' width='200'>" . $bid['description'] . "</td>";
        echo "<td align='center' width='150'>" . $bid['task_datetime'] . "</td>";
        echo "<td align='center' width='200'>" . $bid['location'] . "</td>";
        echo "<td align='center' width='100'>" . $bid['bid_amt'] . "</td>";
        echo "<td align='center' width='200'>" . $bid['bid_status'] . "</td>";
        $i++;
        echo "</tr>";
    }
       /*
       if (!$qResult) {
        echo "Update failed";
        //echo "<script>location.href='main_page.php';</script>"; 
      } else {
        echo "Update successful";
        //echo "<script>location.href='main_page.php';</script>"; 
      }
      */
     /* $otherBidsQuery = "UPDATE public.bid_task SET bid_status = 'lost'
      WHERE tasker_email = '$_SESSION[user_email]' 
      AND submission_datetime = '$_SESSION[submission_datetime]'
      AND bidder_email != '$row[bidder_email]'";
      $otherResult = pg_query($db, $otherBidsQuery);
      /*
      if (!$qResult) {
        echo "Update 2 failed";
        //echo "<script>location.href='main_page.php';</script>"; 
      } else {
        echo "Update 2 successful";
        //echo "<script>location.href='main_page.php';</script>"; 
      }
      */
     /* $updateTaskQuery = "UPDATE public.task_submission SET task_status = 'confirmed'
      WHERE submission_datetime = '$_SESSION[submission_datetime]'
      AND email = '$_SESSION[user_email]'";
      $updateTask = pg_query($db, $updateTaskQuery);
      /*
      if (!$updateTask) {
        echo "Update 3 failed";
        //echo "<script>location.href='main_page.php';</script>"; 
      } else {
        echo "Update 3 successful";
        //echo "<script>location.href='main_page.php';</script>"; 
      }
      */
      /*echo "<script>location.href='main_page.php';</script>"; 
    }

    if(isset($_POST[$row['bidder_email']])){
    	$_SESSION['bidder_email'] = $row['bidder_email'];
    	echo "<script>location.href='view_bidder_profile.php';</script>";
    }
    echo "</tr>";
    $i++; 
  } */

  echo "</table>";
  ?>

</form>
</body>
</html>