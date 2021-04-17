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
  <h1> ACCEPT A BID </h1>
  <form method = "post" action = "confirm_accept_bid.php">

    <?php
    // Connect to the database. Please change the password in the following line accordingly
    include_once("constants.php");
    $db     = pg_connect(FULL_DATABASE_INFO);
    $taskDetails = "SELECT * FROM public.task_submission tasks INNER JOIN public.user_info users ON tasks.email = users.email 
    WHERE tasks.email = '$_SESSION[user_email]' AND tasks.submission_datetime = '$_SESSION[submission_datetime]'";
    $taskDetail = pg_query($db,$taskDetails);
    $ans = pg_fetch_assoc($taskDetail);
    //echo "$_SESSION[user_email]";
    echo "<table>";
    echo "<tr>";
    echo "<td align='center' width='200'>" . "Task Name" . "</td>";
    echo "<td align='center' width='200'>" . "Description" . "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td align='center' width='200'>" . $ans['name'] . "</td>";
    echo "<td align='center' width='200'>" . $ans['description'] . "</td>";
    echo "</tr>";
    echo "</table>";

    echo "<h3> Bids submitted </h3>";

    echo "<table>";
    echo "<tr>";
    echo "<td align='center' width='200'>" . "Number" . "</td>";    
    echo "<td align='center' width='200'>" . "Bidder name" . "</td>";
    echo "<td align='center' width='200'>" . "Bid amount" . "</td>";  
    echo "<td align='center' width='200'>" . "Accept bid" . "</td>";        
    echo "</tr>";

    $query = "SELECT * FROM public.bid_task WHERE bid_task.tasker_email = '$_SESSION[user_email]' AND bid_task.submission_datetime = '$_SESSION[submission_datetime]' ORDER BY bid_task.bid_amt DESC";
    $result = pg_query($db,$query);

    $i = 1;

    while ($row = pg_fetch_assoc($result)) {
    	$nameQuery = "
      SELECT display_name
      FROM public.user_info
      WHERE email = '$row[bidder_email]'";
      $name = pg_query($db, $nameQuery);
      $namee = pg_fetch_row($name);
      echo "<tr>";
      echo "<td align='center' width='200'>" . $i . "</td>";
      echo "<td align='center' width='200'>" . '<p><input type="submit" name="profile" value = '.$namee[0].' ></p>' . "</td>";
      echo "<td align='center' width='200'>" . $row['bid_amt'] . "</td>";
      echo "<td align='center' width='150'>" . '<p><input type="submit" name='.$i.' value = "Select" ></p>' . "</td>"; 

      if(isset($_POST['profile'])) {
        $_SESSION['bidder_email'] = $row['bidder_email'];
        $_SESSION['bidder_name'] = $namee[0];
        echo "<script>location.href='view_bidder_profile.php';</script>";
      }
      elseif(isset($_POST[$i])) {
       $bidquery = "UPDATE public.bid_task SET bid_status = 'won'
       WHERE tasker_email = '$_SESSION[user_email]' 
       AND submission_datetime = '$_SESSION[submission_datetime]'
       AND bidder_email = '$row[bidder_email]'";
       $qResult = pg_query($db, $bidquery);
       
       $otherBidsQuery = "UPDATE public.bid_task SET bid_status = 'lost'
       WHERE tasker_email = '$_SESSION[user_email]' 
       AND submission_datetime = '$_SESSION[submission_datetime]'
       AND bidder_email != '$row[bidder_email]'";
       $otherResult = pg_query($db, $otherBidsQuery);

       $updateTaskQuery = "UPDATE public.task_submission SET task_status = 'confirmed', task_doer = '$row[bidder_email]'
       WHERE submission_datetime = '$_SESSION[submission_datetime]'
       AND email = '$_SESSION[user_email]'";
       $updateTask = pg_query($db, $updateTaskQuery);

       if ((!$updateTaskQuery) || (!$otherBidsQuery) || (!$bidquery)) {
        echo '<script>alert("Error occured.");</script>';
        echo "<script>location.href='confirm_accept_bid.php';</script>";   
      }
      echo '<script>alert("Successful.");</script>';
      echo "<script>location.href='main_page.php';</script>"; 
    }


    echo "</tr>";
    $i++; 
  } 

  echo "</table><br>";
  ?>

  <input type="button" onclick="location.href='main_page.php';" value="Go to Main" />
</form>
</body>
</html>