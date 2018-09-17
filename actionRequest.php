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
		$action = $_GET["action"];
		$id = $_GET["id"];
		$options = $_GET["option"];			
	}
	
	//SQL CONNECTION
	$servername = "spc353_2.encs.concordia.ca";
	$username = "spc353_2";
	$password = "Tq5DjT";
	$dbname = "spc353_2";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	//ADMIN ONLY
	if($_SESSION["priv"] == 1)
	{
		if($action == 'promote')
		{
			
			$sql = "UPDATE member SET privilege = 1 WHERE memberID = $id";
			$conn->query($sql);
			$conn -> close();
			echo '<script type="text/javascript"> window.open("admin.php","_self");</script>';
		}
		if($action == 'demote')
		{
			
			$sql = "UPDATE member SET privilege = 0 WHERE memberID = $id";
			$conn->query($sql);
			$conn -> close();
			echo '<script type="text/javascript"> window.open("admin.php","_self");</script>';
		}
		if($action == 'activate')
		{
			
			$sql = "UPDATE member SET status = 1 WHERE memberID = $id";
			$conn->query($sql);
			$conn -> close();
			echo '<script type="text/javascript"> window.open("admin.php","_self");</script>';
		}
		if($action == 'suspend')
		{
			
			$sql = "UPDATE member SET status = 0 WHERE memberID = $id";
			$conn->query($sql);
			$conn -> close();
			echo '<script type="text/javascript"> window.open("admin.php","_self");</script>';
		}
	}	
	//ALL USER ACTION
	if($action == 'rating')
	{
		$sql = "UPDATE ride SET rideRating = $options WHERE matchedID = $id";
		$conn->query($sql);
		$conn -> close();
		echo '<script type="text/javascript"> window.open("account.php","_self");</script>';
	}
	if($action == 'addFunds')
	{
		$sql = "UPDATE member SET accountBalance = (accountBalance + $options) WHERE memberID = $id";
		$conn->query($sql);
		
		$sql = "SELECT accountBalance FROM member WHERE memberID = $id";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		
		$_SESSION["balance"] = $row["accountBalance"];
		$conn -> close();
		
		echo '<script type="text/javascript"> window.open("account.php","_self");</script>';
	}
	if($action == 'withdraw')
	{
		$sql = "SELECT accountBalance FROM member WHERE memberID = $id";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		
		if(($row["accountBalance"] - $options) >= 0)
		{
			$sql = "UPDATE member SET accountBalance = (accountBalance - $options) WHERE memberID = $id";
			$conn->query($sql);
			$_SESSION["balance"] = ($row["accountBalance"] - $options);
		}	
		
		$conn -> close();
		
		echo '<script type="text/javascript"> window.open("account.php","_self");</script>';
	}
	
	
	
	
	
	
?>