<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="styles.css">
	<title>List of MyBids</title>
</head>
<body>
	<h1> TASK SOLVER </h1>
	<h2> Bids Made </h2>
        <form method = "post" action = "list_bids.php">

	<?php
  	// Connect to the database. Please change the password in the following line accordingly
 include_once("constants.php");
 $db     = pg_connect(FULL_DATABASE_INFO);

	$query = "SELECT * FROM public.bid_task WHERE bid_task.bidder_email = '$_SESSION[user_email]' "; // Show only bids that are from own account
	$result = pg_query($db,$query);  

	//echo "$_SESSION[user_email]";

	echo "<table>";
	echo "<tr>";

  echo "<td align='center' width='200'>" . "Tasker's Email" . "</td>";
  echo "<td align='center' width='200'>" . "Task Submission Date & Time" . "</td>";
  echo "<td align='center' width='200'>" . "Bidder's Email" . "</td>";
  echo "<td align='center' width='200'>" . "Bid Amount" . "</td>";
  echo "<td align='center' width='200'>" . "Bid Submission Date & Time" . "</td>";
  echo "<td align='center' width='200'>" . "Bid Status" . "</td>";
  echo "<td align='center' width='150'>" . "Submit" . "</td>";
  echo "<tr>";

  while ($row = pg_fetch_assoc($result)) {

    echo "<td align='center' width='200'>" . $row['tasker_email'] . "</td>";
    echo "<td align='center' width='200'>" . $row['submission_datetime'] . "</td>";
    echo "<td align='center' width='200'>" . $row['bidder_email'] . "</td>";
    echo "<td align='center' width='200'>" . $row['bidder_amt'] . "</td>";
    echo "<td align='center' width='200'>" . $row['bid_datetime'] . "</td>";
    echo "<td align='center' width='200'>" . $row['bid_status'] . "</td>";
    echo "<td align='center' width='200'>" . $row['name'] . "</td>";
    echo "<td align='center' width='200'>" . $row['description'] . "</td>";
    echo "<td align='center' width='200'>" . $row['location'] . "</td>";
    echo "<td align='center' width='200'>" . $row['end_location'] . "</td>";
    echo "<td align='center' width='200'>" . $row['task_datetime'] . "</td>";
    echo "<td align='center' width='200'>" . $row['email'] . "</td>";
   if ($days <= 0) {
      echo "<td align='center' width='200'>" . "EXPIRED"  . "</td>";
    } else {
      echo "<td align='center' width='200'>" . intval($days)." days ". intval($hours)." hours ". intval($min)." min "  . "</td>";
    }
    echo "<td align='center' width='200'>" . $row['bidder_amt'] . "</td>";
    echo "<td align='center' width='200'>" . $row['bid_status'] . "</td>";
    echo "<td align='center' width='150'>" . '<p><input type="submit" name='.$row['submission_datetime'].' value = "Withdraw bid" ></p>' . "</td>"; 
    echo "</tr>"; 
    if (isset($_POST[$row['submission_datetime']])) {
          $_SESSION['tasker_email'] = $row['tasker_email'];
          $_SESSION['submission_datetime'] = $row['submission_datetime'];
          $_SESSION['bidder_email'] = $row['bidder_email'];
        //header(DIRECTORY."bid_task.php");
          echo "<script>location.href='delete_bids.php';</script>";
        //echo "<br> successful";
      //echo "</tr>"; 
    }
}
    echo "</table>";
    ?>
    <input type="button" onclick="location.href='main_page.php';" value="Go to Main" />
  </form>
  </body>
  </html>