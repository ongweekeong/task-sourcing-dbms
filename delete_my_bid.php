<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="styles.css">
  <title>Delete Bids</title>
</head>
<body>
  <h1> Delete bids</h1>

  <h2> Bid Details </h2>
  <form method="post" action="delete_my_bid.php">ListTask:
    <ul>
      Are you sure you want to delete this bid? <br>
      <input type ="submit" name="yes" value='Yes'> <br>
    </ul>

    <?php
    echo "<table>";
    echo "<tr>";
    echo "<td align='center' width='200'>" . "Task Name" . "</td>";
    echo "<td align='center' width='200'>" . "Description" . "</td>";
    echo "<td align='center' width='200'>" . "Date & Time" . "</td>";
    echo "<td align='center' width='200'>" . "My Bid" . "</td>";
    echo "<td align='center' width='200'>" . "Bid Status" . "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td align='center' width='200'>" . $_SESSION['task_name'] . "</td>";
    echo "<td align='center' width='200'>" . $_SESSION['task_desc'] . "</td>";
    echo "<td align='center' width='200'>" . $_SESSION['task_datetime'] . "</td>";
    echo "<td align='center' width='200'>" . $_SESSION['previous_bid'] . "</td>";
    echo "<td align='center' width='200'>" . $_SESSION['bid_status'] . "</td>";
    echo "</tr>";
    echo "</table>";
    // Connect to the database. Please change the password in the following line accordingly
    include_once("constants.php");
    $db     = pg_connect(FULL_DATABASE_INFO);

    if (isset($_POST['yes'])) {
      if($_SESSION['bid_status'] != 'in progress'){
        echo '<script>alert("Error: Bidding period has ended.");</script>';
        echo "<script>location.href='list_myBids.php';</script>";
      }
      else{
        $query = "UPDATE public.bid_task 
        SET bid_status = 'lost', bid_datetime = (select now()) 
        WHERE bidder_email = '$_SESSION[user_email]' 
        AND submission_datetime = '$_SESSION[submission_datetime]' 
        AND tasker_email = '$_SESSION[tasker_email]'";
        $result = pg_query($db, $query);
        if ($result) {
          echo '<script>alert("Updated!");</script>';
          echo "<script>location.href='list_myBids.php';</script>";
        }
        else {
          echo '<script>alert("Bid update failed!");</script>';
          echo "<script>location.href='list_myBids.php';</script>";
        }
      }
    }

    ?>
  </form>
  <input type="button" onclick="location.href='main_page.php';" value="Go to Main" />
</body>
</html>
