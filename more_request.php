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
		$requestTripID = $_GET["requestID"];
		$matchID = $_GET["matchID"];		
	}
	
	//SQL CONNECTION
	$servername = "spc353_2.encs.concordia.ca";
	$username = "spc353_2";
	$password = "Tq5DjT";
	$dbname = "spc353_2";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	$sql = "SELECT dest.postalCode AS destPostalCode, dest.city_province AS destCityProvince, dep.postalCode AS depPostalCode, dep.city_province AS depCityProvince,
		    trip.tripType, trip.departureDate, trip.daysOfWeek, trip.tripDescription FROM request
			JOIN trip ON riderID = $userID AND trip.tripID = request.tripID AND request.tripID = $requestTripID
			JOIN city dep ON trip.departureCity = dep.cityID
			JOIN city dest ON trip.destinationCity = dest.cityID";
	$requests = $conn -> query($sql);
	$request = $requests->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
   body {
    background-color: #232322;
	}
   .img-rounded{
	    background-color: #ffffff;
	tab { padding-left: 4em; }
   }
		
  </style>
</head>
<body> 
<center><img src="images/minilogo.PNG"></center>

<div class="container">
	<div class="well">
		<table class="table">
			<thead>
				<tr>
					<td><b>Request ID:</b> <?php echo $requestTripID ?> <br><b>Trip Type:</b> <?php echo $request["tripType"] ?></td>
				</tr>
				<tr>
					<td><b>Departure:</b><br>			
						   
					City/Province: <?php echo $request["depCityProvince"] ?><br>
				    Postal Code: <?php echo $request["depPostalCode"] ?><br>
				
					<?php 	
						if($request["tripType"] == 'oneTime')
							echo "Departure Date: " .$request["departureDate"];
						else
							echo "Days of the week: " .$request["daysOfWeek"];
					?>			
										
				</tr>
				<tr>
					<td><b>Destination: </b><br>
						  City/Province: <?php echo $request["destCityProvince"] ?><br>
							Postal Code: <?php echo $request["destPostalCode"] ?><br>
					</td>
				</tr>
				<tr>
					<td><b>Trip Description: </b><br>
						<?php echo $request["tripDescription"] ?><br>							
					</td>
				</tr>
				<tr>
				<td>
				<?php
					if($matchID != null)
					{							
						$sql = "SELECT * FROM matched 
								JOIN trip ON trip.tripID = matched.offerTripID AND matched.matchID = $matchID 
								JOIN offer ON offer.tripID = trip.tripID 
								JOIN member ON member.memberID = offer.driverID";
						$matches = $conn->query($sql);
						$match=$matches->fetch_assoc();
						$rideCost = $match["tariff"] * $match["distance"];
						echo "Driver is: " .$match["name"]. ' <a href="#" data-toggle="modal" data-target="#message"><span class="glyphicon glyphicon-envelope"> </span></a><br>';
						echo "Ride distance: " .$match["distance"]. "km<br>";
						echo "Total Ride Cost if accepted: " .$rideCost."$";
						echo '<div align="center"><a href="addride.php?id='.$matchID.'&requestTrip='.$requestTripID.'&offerTrip='.$match["tripID"].'&cost='.$rideCost.'" class="btn btn-success" role="button">Accept Offer</a>';	
					}
					else
						echo '<div class="alert alert-warning">Pending Match</div>';
					
				?>
				</td>
				</tr>
				<tr>
					<td>
						<div align="right"><a href="delete.php?type=trip&id=<?php echo $offerID ?>" class="btn btn-danger" role="button">Delete Request</a>
						<a href="home.php" class="btn btn-default" role="button">Back</a></div>
					</td>				
				</tr>
				 
			</thead>
			<tbody>			
</div>
</div>
<div class="modal fade" id="message" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Message To: <?php echo $match["name"] ?></</h4>
            </div>
            <div class="modal-body">
               <form action="sendMessage.php" method="POST">
				<div class="form-group">
					<label for="text"> Message Body: </label><br>									
					<input type="text" name="message" class="form-control" placeholder="Max 255 characters">					
				</div>				
            </div>
            <div class="modal-footer">
				<button type="submit" name ="submit" class="btn btn-default" value="<?php echo $match["memberID"] ?>">Submit</button> 
				</form>    
            </div>            
        </div>
    </div>
</div>


</body>
</html>