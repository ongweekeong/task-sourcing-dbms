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
<form method="post" action="admin_update_bid.php">ListTask:
    <ul>
        Previous Bid Amount is <?php echo "$_SESSION[bid_amt]"; ?> <br>
        <br>
        Bid amount: <br>
        <input type ="text" name = "bid_amt"> <br>
        <br>
        <input type= "submit" name="submit">
    </ul>

    <?php
    // Connect to the database. Please change the password in the following line accordingly
    include_once("constants.php");
    echo "<form name ='display' method='POST' align='right'><a href='admin_main_page.php' class='button main'>Back to Main</a></form>";

    $db     = pg_connect(FULL_DATABASE_INFO);

    /* if (isset($_POST['submit'])) {
       $query = "SELECT * FROM public.bid_task WHERE bidder_email = '$_POST[bidderEmail]'AND
       submission_datetime = '$_SESSION[submission_datetime]' AND tasker_email = '$_SESSION[tasker_email]'";
       $result = pg_query($db,$query);
       $row = pg_fetch_assoc($result);
       echo "<ul><form name='update' method='POST'>

       <li>Tasker Email:</li>
       <li><input type='text' name='u_tasker_email' value='$row[tasker_email]' /></li>
       <li>Task Datetime:</li>
       <li><input type='text' name='u_task_datetime' value='$row[task_datetime]' /></li>
       <li>Task location:</li>
       <li><input type='text' name='u_task_location' value='$row[task_location]' /></li>
       <li>Bidder Email:</li>
       <li><input type='text' name='u_bidder_email' value='$row[bidder_email]' /></li>
       <li>Bid Amount:</li>
       <li><input type='text' name='u_bid_amt' value='$row[bid_amt]' /></li>
       <li>Bid Datetime:</li>
       <li><input type='text' name='u_bid_datetime' value='$row[bid_datetime]' /></li>
       <li>Bid Status</li>
       <li><input type='text' name='u_bid_status' value='$row[bid_status]' /></li>
       <li><input type='submit' name='new' /></li>
       </form>
       </ul>";
     }*/
    if (isset($_POST['submit'])) {
        $result = pg_query($db, "UPDATE public.bid_task SET bid_amt = '$_POST[bid_amt]', bid_datetime = (select now()) WHERE bidder_email = '$_SESSION[bidder_email]'AND submission_datetime = '$_SESSION[submission_datetime]' AND tasker_email = '$_SESSION[tasker_email]'");
        if ($result) {
            echo "Updated!\n";
            header(DIRECTORY."admin_main_page.php");
        }
        else {
            echo "Update failed.\n";
        }
    }

    ?>
</form>
</body>
</html>