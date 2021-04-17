<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="styles.css">
  <title>List of My Tasks and their bids</title>
</head>
<body>
  <h1> Task Solver </h1>
  <h2> Accept a Bid </h2>
  <form method = "post" action = "accept_bid.php">

    <?php
    // Connect to the database. Please change the password in the following line accordingly
    include_once("constants.php");
    $db     = pg_connect(FULL_DATABASE_INFO);

    $query = "SELECT *, CASE WHEN task_submission.description IS NULL THEN 'N/A' ELSE task_submission.description END FROM public.task_submission WHERE task_submission.email = '$_SESSION[user_email]' ORDER BY task_submission.deadline DESC";
    $result = pg_query($db,$query);
    echo "<div align='right'>$_SESSION[user_email]</div>";
    echo "<div align='right'><a href='main_page.php' class='button main'>Back to Main</a></div><br>";
    echo "<table>";
    echo "<tr>";
    echo "<td align='center' width='200'>" . "Number" . "</td>";
    echo "<td align='center' width='200'>" . "Name" . "</td>";
    echo "<td align='center' width='200'>" . "Description" . "</td>";
    echo "<td align='center' width='200'>" . "Time remaining" . "</td>";
    echo "<td align='center' width='200'>" . "Maximum Amount" . "</td>";
    echo "<td align='center' width='200'>" . "No. of bidders" . "</td>";
    echo "<td align='center' width='200'>" . "Lowest bid" . "</td>";
    echo "<td align='center' width='200'>" . "Accept a bid" . "</td>";
    echo "</tr>";
    //echo "</table";
    $i = 1;
    while ($row = pg_fetch_assoc($result)) {

      $timeLeft = strtotime($row['deadline']) - time();
      $days = ($timeLeft / 60 / 60 / 24) ;
      $hours = ($days - intval($days)) * 24;
      $min = ($hours - intval($hours)) * 60;
      $submissiondatetime = $row['submission_datetime'];

      $bidquery = "SELECT COUNT(*) FROM public.bid_task WHERE bid_task.tasker_email = '$_SESSION[user_email]' 
      AND bid_task.submission_datetime = '$submissiondatetime'";
      $roww = pg_query($db, $bidquery);
      $numBidders = pg_fetch_row($roww);

      $lowestBidQuery = "SELECT CASE WHEN MIN(bid_task.bid_amt) IS NULL THEN '$0.00' ELSE MIN(bid_task.bid_amt) END AS min_bid FROM public.bid_task WHERE bid_task.tasker_email = '$_SESSION[user_email]' AND bid_task.submission_datetime = '$submissiondatetime'";
      $ans = pg_query($db, $lowestBidQuery);
      $lowestBid = pg_fetch_assoc($ans);

      if (($days <= 0) || ($numBidders[0] == 0) || ($row['task_status'] != 'in progress')) {
        continue;
      } 

      echo "<td align='center' width='200'>" . $i . "</td>";
      echo "<td align='center' width='200'>" . $row['name'] . "</td>";
      echo "<td align='center' width='200'>" . $row['description'] . "</td>";
      echo "<td align='center' width='200'>" . $row['task_status'] . "</td>";
      echo "<td align='center' width='200'>" . $row['max_amt'] . "</td>";
      echo "<td align='center' width='200'>" . $numBidders[0] . "</td>"; 
      echo "<td align='center' width='200'>" . $lowestBid['min_bid'] . "</td>";     
      echo "<td align='center' width='150'>" . '<p><input type="submit" name='.$row['submission_datetime'].' value = "Select" ></p>' . "</td>"; 
      
      if (isset($_POST[$row['submission_datetime']])) {
        if ($numBidders[0] == 0) {
          echo "No bids to accept";
        } else {
          //$_SESSION['tasker_email'] = $row['tasker_email'];
          $_SESSION['submission_datetime'] = $row['submission_datetime'];
          echo "<script>location.href='confirm_accept_bid.php';</script>";
          break; 
        }
      }
      echo "</tr>";
      $i++; } 
      echo "</table><br>";
      ?>

    </form>
  </body>
  </html>