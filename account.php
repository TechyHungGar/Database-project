<?php
	//HEAD FOR ALL SCRIPTS
	//For browser to reload at every request and not cache
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	
	//start php session and test for existing user, if no redirect to login
    session_start();
	if(!isset($_SESSION["use"]))	
		echo '<script type="text/javascript"> window.open("index.php","_self");</script>';	
	else
	{
		$user = $_SESSION["use"];
		$userID = $_SESSION["id"];
		if($_SESSION["priv"] == 1)
			$admin = true;
		else
			$admin = false;
	}
	
	$servername = "spc353_2.encs.concordia.ca";
	$username = "spc353_2";
	$password = "Tq5DjT";
	$dbname = "spc353_2";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Super</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
    /* Remove the navbar's default margin-bottom and rounded borders */ 
    .navbar {
      margin-bottom: 0;
      border-radius: 0;
    }
    
    /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
    .row.content {height: 550px;}
    
    /* Set gray background color and 100% height */
    .sidenav {
      padding-top: 20px;
      background-color: #f1f1f1;
      height: 100%;
	  overflow: auto;
    }
    
    /* Set black background color, white text and some padding */
    footer {
      background-color: #555;
      color: white;
      padding: 15px;
    }
    
    /* On small screens, set height to 'auto' for sidenav and grid */
    @media screen and (max-width: 767px) {
      .sidenav {
        height: auto;
        padding: 15px;
		overflow: auto;
      }
      .row.content {height:auto;} 
    }
	.col-sm-5 {
	 border: 1px solid #888;
    border-radius:3px; }
	
  </style>
</head>
<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
	  
      <img src="images/minilogo.PNG" height="50" width="129">
	  
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">	  
       <li><a href="home.php">Home</a></li>
	   </ul>	 
      <ul class="nav navbar-nav navbar-right">
	  <?php
			if($admin)
				echo '<li><a href="admin.php"><span class="glyphicon glyphicon-wrench"></span> Admin Panel</a></li>';
		?>
        <li class="active"><a href="account.php"><span class="glyphicon glyphicon-user"></span><?php echo " ".$user;?></a></li>
		<li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="modal fade" id="addFunds" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Add Funds</h4>
            </div>
            <div class="modal-body">
                <form action="actionRequest.php" method="GET">
				<input type="hidden" name="id" value="<?php echo $userID; ?>"/>
				<input type="text" class="form-control" name="option" placeholder="$"/>					
            </div>
            <div class="modal-footer">
            <button type="submit" name ="action" value="addFunds" class="btn btn-default">Add Funds</button> 
            </form>    
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="withdraw" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Withdraw Funds</h4>
            </div>
            <div class="modal-body">
                <form action="actionRequest.php" method="GET">
				<input type="hidden" name="id" value="<?php echo $userID; ?>"/>
				<input type="text" class="form-control" name="option" placeholder="$"/>					
            </div>
            <div class="modal-footer">
            <button type="submit" name ="action" value="withdraw" class="btn btn-default">Withdraw Funds</button> 
            </form>    
            </div>
        </div>
    </div>
</div>

<div class="container-fluid text-center">    
  <div class="row content">    
    <div class="col-sm-6 text-center">
		<h3> Ride History </h3>
		<?php
			//GET RIDES
			$sql = "SELECT * FROM ride WHERE driverID = $userID OR riderID = $userID";
			$rides = $conn->query($sql);
			
			$sql = "SELECT * FROM member WHERE memberID = $userID";
			$members = $conn->query($sql);
			
			if($rides -> num_rows > 0)
			{
				echo '<table class="table table-condensed">
					<thead>
						  <tr>
							<td><b>Ride Cost</td>
							<td><b>Driver</td>
							<td><b>Rider</td>
							<td><b>Ride Rating</td>						
						  </tr>
					</thead><tbody>';
				while($ride = $rides->fetch_assoc())
				{	
					echo'<tr>';
					echo'<td>$'.$ride["rideCost"].'</td>';
					if($ride["driverID"] == $userID)
					{
						echo'<td>'.$user.' </td>';						
					}
					else
					{
						$otherParty = $ride["driverID"];						
						$sql = "SELECT * FROM member WHERE memberID = $otherParty";
						$otherMembers = $conn->query($sql);
						$otherMember = $otherMembers -> fetch_assoc();
						echo'<td>'.$otherMember["name"].' </td>';
					}					
					
					if($ride["riderID"] == $userID)					
						echo'<td>'.$user.' </td>';				
					else
					{
						$otherParty = $ride["riderID"];						
						$sql = "SELECT * FROM member WHERE memberID = $otherParty";
						$otherMembers = $conn->query($sql);
						$otherMember = $otherMembers -> fetch_assoc();
						echo'<td>'.$otherMember["name"].' </td>';
					}
					
					if($ride["riderID"] == $userID && $ride["rideRating"] == null)
					{
						$rideID = $ride["matchedID"];
						echo'<td> <form action="actionRequest.php" method="GET">
								  <input type="hidden" name="id" value="'.$rideID.'" />
								  <label class="radio-inline"><input type="radio" name="option" value="1">1</label>
								  <label class="radio-inline"><input type="radio" name="option" value="2">2</label>
								  <label class="radio-inline"><input type="radio" name="option" value="3">3</label>
								  <label class="radio-inline"><input type="radio" name="option" value="4">4</label>
								  <label class="radio-inline"><input type="radio" name="option" value="5">5</label><br>
								  <button type="submit" name="action" value="rating" class="btn btn-xs">Submit</button></form> 
								  </td></tr>';
					}
					else if($ride["rideRating"] == null)
						echo'<td> Rider must rate </td></tr>';
					else
						echo'<td>'.$ride["rideRating"].'</td></tr>';
					
				}
				echo '</tbody></table>';
			}
			else
			{
				echo "<p> You have not taken any rides </p>";
			}
		?>      			
	</div>
	<div class="col-sm-6 text-center">
		<h3> Account Info </h3>	
		<?php
			//GET RIDES			
			$sql = "SELECT * FROM member WHERE memberID = $userID";
			$members = $conn->query($sql);			
			
			$member = $members->fetch_assoc();
			
			echo '<table class="table"><tbody>';				
			echo '<tr><td><b>Account Balance:</b></td><td>$'.$member["accountBalance"].'</td></tr></tbody></table>';
			if($member["accountBalance"] >= 200)
				echo'<div class="alert alert-success"><a href="#" data-toggle="modal" data-target="#withdraw">CLICK HERE to Withdraw Funds</a></div>';
			else if($member["accountBalance"] < 100)
				echo'<div class="alert alert-warning"><a href="#" data-toggle="modal" data-target="#addFunds">CLICK HERE to Add Funds to Continue Using Super</a></div>';
			else
			{
				$deltaWithdraw = (200 - $member["accountBalance"]);
				echo'<div class="alert alert-info">Make another <strong>$'.$deltaWithdraw.'</strong> and you can withdraw your funds!</div>';
			}
		?>  
		
	</div>
  </div> 
</div>


