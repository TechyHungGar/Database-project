<?php
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	
	session_start();
	if(isset($_POST['loginReg']))    
	{
		$tripType = 'regular';
		$daysOfWeek = $_POST['daysOfWeek'];
		$departureDate = null;
	}
	else if(isset($_POST['loginOne']))    
	{
		$tripType = 'oneTime';
		$departureDate = $_POST['departureDate'];
		$daysOfWeek = null;		
	}
	else
		echo '<script type="text/javascript"> window.open("home.php","_self");</script>';
	
	$userID = $_SESSION["id"];
	$tripDescription = $_POST['tripDescription'];
	$city_province = $_POST['city_province'];
	$postalCode = $_POST['postalCode'];
	$city_provinceDEST = $_POST['city_provinceDEST'];
	$postalCodeDEST = $_POST['postalCodeDEST'];
		 
	$servername = "spc353_2.encs.concordia.ca";
	$username = "spc353_2";
	$password = "Tq5DjT";
	$dbname = "spc353_2";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	//Insert Departure City
	$sql = "INSERT INTO city (postalCode, city_province) 
			VALUES ('$postalCode', '$city_province')";	
	$conn->query($sql);
	
	//SaveID of Departure city
	$sql = "SET @departureCityID = LAST_INSERT_ID()";	
	$conn->query($sql);	
	
	//Insert Destination City
	$sql = "INSERT INTO city (postalCode, city_province) 
			VALUES ('$postalCodeDEST', '$city_provinceDEST')";	
	$conn->query($sql);
	
	//SaveID of Destination city
	$sql = "SET @destinationCityID = LAST_INSERT_ID()";	
	$conn->query($sql);
	
	if(isset($_POST['loginReg']))    
	{
		//Insert Into Trip
		$sql = "INSERT INTO trip (departureCity, destinationCity, tripType, departureDate, daysOfWeek, radius, tariff, tripDescription)
				VALUES(@departureCityID, @destinationCityID, '$tripType', null,'$daysOfWeek', null,null,'$tripDescription')";
	}
	else
	{
		$sql = "INSERT INTO trip (departureCity, destinationCity, tripType, departureDate, daysOfWeek, radius, tariff, tripDescription)
				VALUES(@departureCityID, @destinationCityID, '$tripType', '$departureDate' ,null, null,null,'$tripDescription')";
	}
	$conn->query($sql);

	$sql = "SET @tripID = LAST_INSERT_ID()";	
	$conn->query($sql);

	$sql = "UPDATE city SET tripID = @tripID WHERE cityID = @departureCityID or cityID = @destinationCityID";	
	$conn->query($sql);

	$sql = "INSERT INTO request	VALUES ($userID, @tripID)";
	$conn->query($sql);
	
	$conn->close();
	
	echo '<script type="text/javascript"> window.open("check_for_match.php","_self");</script>';

?>