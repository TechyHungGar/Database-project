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
		
		if($_SESSION["balance"] < 100)
			$addFunds = true;
		else
			$addFunds = false;			
		
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
       <li class="active"><a href="home.php">Home</a></li>
	   <li class="dropdown">
		<?php if($addFunds == false) 
        echo '<a class="dropdown-toggle" data-toggle="dropdown" href="#">Request A Ride<span class="caret"></span></a>';
		?>
        <ul class="dropdown-menu">
          <li><a href="#" data-toggle="modal" data-target="#requestReg">Regular Trip</a></li>
          <li><a href="#" data-toggle="modal" data-target="#requestOne">One Time</a></li>         
        </ul>
		</li>
		<li class="dropdown">
		<?php if($addFunds == false) 
				echo '<a class="dropdown-toggle" data-toggle="dropdown" href="#">Offer A Ride<span class="caret"></span></a>';
		?>        
        <ul class="dropdown-menu">
          <li><a href="#" data-toggle="modal" data-target="#offerReg">Regular Trip</a></li>
          <li><a href="#" data-toggle="modal" data-target="#offerOne">One Time</a></li>         
        </ul>
		</li>   
      </ul>	 
      <ul class="nav navbar-nav navbar-right">
	   <?php
			if($admin)
				echo '<li><a href="admin.php"><span class="glyphicon glyphicon-wrench"></span> Admin Panel</a></li>';
		?>
        <li><a href="account.php"><span class="glyphicon glyphicon-user"></span><?php echo " ".$user;?></a></li>
		<li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<?php 
	if($addFunds)
	{
		echo '<div class="alert alert-danger"> Please Add Funds in Account Page to Continue Using Super </div>'; 
		goto here;	
	}
?>

<div class="modal fade" id="offerReg" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Offer A Regular Ride</h4>
            </div>
            <div class="modal-body">
                <form action="addOffer.php" method="POST">
				<div class="form-group">
					<label for="text"> Days of the Week: </label>
					<input type="text" name="daysOfWeek" class="form-control" placeholder="D-M-T-W-J-F-S">					
					<label for="text"> Departure City: </label>				
					<input type="text" name="city_province" class="form-control" placeholder="City/Province">
					<input type="text" name="postalCode" class="form-control" placeholder="PostalCode">
					<label for="test"> Max Radius: </label>
					<input type="text" name="radius" class="form-control" placeholder="km">
					<label for="test"> Rate: </label>
					<input type="text" name="rate" class="form-control" placeholder="$/km">	
					<label for="text"> Trip Description: </label>				
					<input type="text" name="tripDescription" class="form-control" placeholder="Max 255 characters">
				</div>
				
            </div>
            <div class="modal-footer">
            <button type="submit" name ="loginReg" class="btn btn-default">Submit</button> 
            </form>    
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="offerOne" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Offer A One Time Ride</h4>
            </div>
            <div class="modal-body">
                <form action="addOffer.php" method="POST">
				<div class="form-group">
					<label for="text"> Date&Time of Departure: </label><br>
					<input type="date" name="departureDate" class="form-control">
					<label for="text"> Departure City: </label>				
					<input type="text" name="city_province" class="form-control" placeholder="City/Province">
					<input type="text" name="postalCode" class="form-control" placeholder="PostalCode">
					<label for="test"> Max Radius: </label>
					<input type="text" name="radius" class="form-control"  placeholder="km">
					<label for="test"> Rate: </label>
					<input type="text" name="rate" class="form-control" placeholder="$/km">	
					<label for="text"> Trip Description: </label>				
					<input type="text" name="tripDescription" class="form-control" placeholder="Max 255 characters">					
				</div>				
            </div>
            <div class="modal-footer">
            <button type="submit" name ="loginOne" class="btn btn-default">Submit</button> 
            </form>    
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="requestReg" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Request A Regular Ride</h4>
            </div>
            <div class="modal-body">
                <form action="addRequest.php" method="POST">
				<div class="form-group">
					<label for="text"> Days of the Week: </label>
					<input type="text" name="daysOfWeek" class="form-control" placeholder="D-M-T-W-J-F-S">					
					<label for="text"> Departure City: </label>				
					<input type="text" name="city_province" class="form-control" placeholder="City/Province">
					<input type="text" name="postalCode" class="form-control" placeholder="PostalCode">
					<label for="text"> Destination City: </label>				
					<input type="text" name="city_provinceDEST" class="form-control" placeholder="City/Province">
					<input type="text" name="postalCodeDEST" class="form-control" placeholder="PostalCode">
					<label for="text"> Trip Description: </label>				
					<input type="text" name="tripDescription" class="form-control" placeholder="Max 255 characters">
				</div>
				
            </div>
            <div class="modal-footer">
            <button type="submit" name ="loginReg" class="btn btn-default">Submit</button> 
            </form>    
            </div>
        </div>
    </div>
</div>

 <div class="modal fade" id="requestOne" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Request A One Time Ride</h4>
            </div>
            <div class="modal-body">
                <form action="addRequest.php" method="POST">
				<div class="form-group">
					<label for="text"> Date&Time of Departure: </label><br>
					<input type="date" name="departureDate" class="form-control">
					<label for="text"> Departure City: </label>				
					<input type="text" name="city_province" class="form-control" placeholder="City/Province">
					<input type="text" name="postalCode" class="form-control" placeholder="PostalCode">
					<label for="text"> Destination City: </label>				
					<input type="text" name="city_provinceDEST" class="form-control" placeholder="City/Province">
					<input type="text" name="postalCodeDEST" class="form-control" placeholder="PostalCode">
					<label for="text"> Trip Description: </label>				
					<input type="text" name="tripDescription" class="form-control" placeholder="Max 255 characters">
				</div>				
            </div>
            <div class="modal-footer">
            <button type="submit" name ="loginOne" class="btn btn-default">Submit</button> 
            </form>    
            </div>
        </div>
    </div>
</div>

<div class="container-fluid text-center">    
  <div class="row content">    
    <div class="col-sm-5 text-center"> 
		<h1>Offers</h1>
		<?php
			//GET OFFERS AND REQUESTS
			$sql = "SELECT * FROM request JOIN trip ON request.riderID = $userID AND trip.tripID = request.tripID";
			$requests = $conn->query($sql);
			
			$sql = "SELECT * FROM offer JOIN trip ON driverID = $userID AND trip.tripID = offer.tripID";
			$offers = $conn->query($sql);
			
			if($offers -> num_rows > 0)
			{
				echo '<table class="table">
					<thead>
						  <tr>
							<td><b>Offer ID</td>
							<td><b>Trip Type</td>
							<td><b>Trip Info</td>
							<td><b>Match Status</td>
							<td><b>More Info</td>
						  </tr>
					</thead><tbody>';
				while($offer = $offers->fetch_assoc())
				{
					$offerTripID = $offer["tripID"];
					$sql = "SELECT * FROM matched WHERE offerTripID = $offerTripID";
					$matches = $conn->query($sql);
					
					
				    echo'<tr>';
					echo'<td>'.$offer["tripID"].'</td>';
					echo'<td>'. $offer["tripType"].' </td>';
					if($offer["tripType"] == 'oneTime')
						echo'<td>'. $offer["departureDate"].' </td>';
					else
						echo'<td>'. $offer["daysOfWeek"].' </td>';
					
					if($matches -> num_rows > 0)
					{
						echo'<td class="success"> Matched </td>';
						$match = $matches->fetch_assoc();
						$matchID = $match["matchID"];
					}
					else
					{
						echo'<td class="warning"> Pending </td>';
						$matchID = null;
					}
					
					echo '<td><a href="more_offer.php?offerID='.$offerTripID.'&matchID='.$matchID.'"> View </a></td></tr>';
				}
				echo '</tbody></table>';
			}
			else
			{
				echo "<p> You have posted no offers </p>";
			}
		?>      	  
				  
	</div>
	<div class="col-sm-5 text-center"> 
		<h1>Requests</h1>
		<?php
			if($requests -> num_rows > 0)
			{
				echo '<table class="table">
					<thead>
						  <tr>
							<td><b>Offer ID</td>
							<td><b>Trip Type</td>
							<td><b>Trip Info</td>
							<td><b>Match Status</td>
							<td><b>More Info</td>
						  </tr>
					</thead><tbody>';
				while($request = $requests->fetch_assoc())
				{
					$requestTripID = $request["tripID"];
					$sql = "SELECT * FROM matched WHERE requestTripID = $requestTripID";
					$matches = $conn->query($sql);
					
				    echo'<tr>';
					echo'<td>'.$request["tripID"].'</td>';
					echo'<td>'. $request["tripType"].' </td>';
					if($request["tripType"] == 'oneTime')
						echo'<td>'. $request["departureDate"].' </td>';
					else
						echo'<td>'. $request["daysOfWeek"].' </td>';
					
					if($matches -> num_rows > 0)
					{
						echo'<td class="success"> Matched </td>';
						$match = $matches->fetch_assoc();
						$matchID = $match["matchID"];
					}
					else
					{
						echo'<td class="warning"> Pending </td>';
						$matchID = null;
					}
					
					echo '<td><a href="more_request.php?requestID='.$requestTripID.'&matchID='.$matchID.'"> View </a></td></tr>';
					
				}
				echo '</tbody></table>';
			}
			else
			{
				echo "<p> You have posted no requests </p>";
			}
		?>
    </div>
	<div class="col-sm-2 sidenav">
	<?php

	// Check connection	
		$sql = "SELECT * FROM inbox WHERE receipientID = '$userID'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) 
		{
			
			while($row = $result -> fetch_assoc())
			{
				$sender = $row['senderID'];			
				$sql = "SELECT * FROM member WHERE memberID = '$sender'";
				$senderID = $conn->query($sql);
				$senderIDRow = $senderID -> fetch_assoc();
				echo 
				'From: '.$senderIDRow["name"]. '<div class="well">
					<p>'.$row["messageBody"].'</p>
				</div> ';
			}
		}
		else
		{
			echo '
			<div class="well">
				<p>You Have No Messages</p>
			</div> ';
		}
		$conn->close(); 		
	?>  
	
      
    </div>
  </div>
</div>

<?php here: ?>



</body>
</html>


			

