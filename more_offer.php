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
		$offerID = $_GET["offerID"];
		$matchID = $_GET["matchID"];
		
	}
	
	//SQL CONNECTION
	$servername = "spc353_2.encs.concordia.ca";
	$username = "spc353_2";
	$password = "Tq5DjT";
	$dbname = "spc353_2";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	$sql = "SELECT * FROM offer 
			JOIN trip ON driverID = $userID AND trip.tripID = offer.tripID AND offer.tripID = $offerID
			JOIN city ON trip.departureCity= city.cityID";
	$offers = $conn -> query($sql);
	$offer = $offers->fetch_assoc();
	
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
					<td><b>Offer ID:</b> <?php echo $offerID ?> <br><b>Trip Type:</b> <?php echo $offer["tripType"] ?></td>
				</tr>
				<tr>
					<td><b>Departure:</b><br>			
						   
					City/Province: <?php echo $offer["city_province"] ?><br>
				    Postal Code: <?php echo $offer["postalCode"] ?><br>
				
					<?php 	
						if($offer["tripType"] == 'oneTime')
							echo "Departure Date: " .$offer["departureDate"];
						else
							echo "Days of the week: " .$offer["daysOfWeek"];
					?>			
										
				</tr>
				<tr>
					<td><b>More Info: </b><br>
						   Radius: <?php echo $offer["radius"] ?><br>
						   Trip Description: <?php echo $offer["tripDescription"] ?>
					</td>
				</tr>
				<tr>
				<td>
				<?php
					if($matchID != null)
					{					
						echo '<div class="alert alert-success">Matched - Awaiting Acceptance From Rider</div>';
						$sql = "SELECT * FROM matched 
								JOIN trip ON trip.tripID = matched.requestTripID AND matched.matchID = $matchID 
								JOIN request ON request.tripID = trip.tripID 
								JOIN member ON member.memberID = request.riderID";
						$matches = $conn->query($sql);
						$match=$matches->fetch_assoc();
						
						echo "Rider is: " .$match["name"]. ' <a href="#" data-toggle="modal" data-target="#message"><span class="glyphicon glyphicon-envelope"> </span></a><br>';
						echo "Ride distance: " .$match["distance"]. "km<br>";
						echo "Net income if accepted: " .$offer["tariff"] * $match["distance"]."$";						
					}
					else
						echo '<div class="alert alert-warning">Pending Match</div>';
					
				?>
				</td>
				</tr>
				<tr>
					<td>
						<div align="right"><a href="delete.php?type=trip&id=<?php echo $offerID ?>" class="btn btn-danger" role="button">Delete Offer</a>
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