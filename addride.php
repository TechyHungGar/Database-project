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
		$matchID = $_GET["id"];	
		$rideCost = $_GET["cost"];
		$requestTripID = $_GET["requestTrip"];	
		$offerTripID = $_GET["offerTrip"];	
	}
	
	//SQL CONNECTION
	$servername = "spc353_2.encs.concordia.ca";
	$username = "spc353_2";
	$password = "Tq5DjT";
	$dbname = "spc353_2";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	
	$sql = "SELECT * FROM matched 
			JOIN trip ON trip.tripID = matched.offerTripID AND matched.matchID = $matchID 
			JOIN offer ON offer.tripID = trip.tripID 
			JOIN member ON member.memberID = offer.driverID";
			
	$matches = $conn->query($sql);
	$match=$matches->fetch_assoc();	
	$driverID = $match["driverID"];	
	
	$sql = "INSERT INTO ride VALUES('$matchID', '$rideCost', null, '$driverID', '$userID')";
	$conn->query($sql);	
	
	$sql = "DELETE FROM matched WHERE matchID <> $matchID AND offerTripID = $offerTripID or requestTripID = $requestTripID)";
	$conn->query($sql);	
	
	$sql = "DELETE FROM offer WHERE tripID = $offerTripID";
	$conn->query($sql);	
	
	$sql = "DELETE FROM request WHERE tripID = $requestTripID";
	$conn->query($sql);
	
	$conn->close();
	
	echo '<script type="text/javascript"> window.open("home.php","_self");</script>';
?>