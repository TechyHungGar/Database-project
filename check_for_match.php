<?php
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	
	session_start();
	function getPostalCodes($radius, $postalCode)
	{
		$postalCode = substr($postalCode, -6,-3);
		
		$url = "http://api.geonames.org/findNearbyPostalCodesJSON?postalcode=$postalCode&country=CA&radius=$radius&username=353super&maxRows=500";

		$cURL = curl_init();

		curl_setopt($cURL, CURLOPT_URL, $url);
		curl_setopt($cURL, CURLOPT_HEADER, 0);
		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($cURL);
		curl_close($cURL);		
		return json_decode($result, true);
	}

   
	if(!isset($_SESSION["use"]))	
		echo '<script type="text/javascript"> window.open("index.php","_self");</script>';	
	else
	{
		$user = $_SESSION["use"];
		$userID = $_SESSION["id"];
	}
	 
	$servername = "spc353_2.encs.concordia.ca";
	$username = "spc353_2";
	$password = "Tq5DjT";
	$dbname = "spc353_2";
	
	$conn = new mysqli($servername, $username, $password, $dbname);
	
	$sql = "SELECT Dep.postalCode AS depPC, Dest.postalCode AS destPC, trip.departureDate, 
			trip.daysOfWeek, trip.tripType, trip.tripDescription, trip.tripID, request.riderID FROM request 
			JOIN trip ON trip.tripID = request.tripID 
			JOIN city Dest ON Dest.cityID = trip.destinationCity 
			JOIN city Dep ON Dep.cityID = trip.departureCity";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) 
	{
		//----->LOOP ALL REQUESTS<-------
		while($row = $result->fetch_assoc()) 
		{
			//All Request Info
			$requestDestinationPC = strtoupper(substr($row["destPC"], -6,-3));
			$requestDeparturePC = strtoupper(substr($row["depPC"], -6,-3));			
			$requestTripType = $row["tripType"];			//type
			$requestTripID = $row["tripID"];				//tripID
			$requestDaysOfWeek = $row["daysOfWeek"]; 		//For regular
			$requestDepartureDate = $row["departureDate"];	//For oneTime				
			$riderID = $row["riderID"];	//For oneTime	
			
			$sql = " SELECT * FROM offer 
					 JOIN trip ON trip.tripType = '$requestTripType' and trip.tripID = offer.tripID and offer.driverID <> $riderID 
					 JOIN city ON city.cityID = trip.departureCity";
			$offers = $conn->query($sql);
			
			if ($offers->num_rows > 0) 
			{				
				while($offer = $offers->fetch_assoc()) 
				{						
					$valid = false;
					if($requestTripType == 'oneTime')
					{
						if($requestDepartureDate == $offer["departureDate"])
							$valid = true;
					}
					else if($requestTripType == 'regular')
					{
						if($requestDaysOfWeek == $offer["daysOfWeek"])
							$valid = true;
					}
					if($valid)
					{					
						$obj = getPostalCodes($offer["radius"], $offer["postalCode"]);
						$offerID = $offer["tripID"];
						if(substr($obj['status']['message'],0,9) != 'no postal')
							foreach($obj['postalCodes'] as $item)							
									if($item['postalCode'] == $requestDestinationPC)
									{									
										$distance = $item['distance'];
										$sql = "SELECT * FROM matched WHERE requestTripID = $requestTripID AND offerTripID = $offerID";
										$matches = $conn->query($sql);
										
										if($matches->num_rows == 0)
										{											
											$sql = "INSERT INTO matched(offerTripID, requestTripID, distance)
													VALUES ('$offerID', '$requestTripID','$distance')";
											$conn->query($sql);
										}
									}
										
					}					
				}
			}			
		}
		
	}
	$conn->close();
    echo '<script type="text/javascript"> window.open("home.php","_self");</script>';	 
?>