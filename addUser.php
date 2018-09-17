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
		$membershipFee = 100;
		$memberName = $_POST["name"];
		$memberPassword = $_POST["password"];
		$memberAddress = $_POST["address"];
		$memberEmail = $_POST["email"];
		$memberDOB = $_POST["dob"];
		$memberInitialDeposit = $_POST["balance"] - $membershipFee;
		$memberPrivilege = $_POST["admin"];
		$memberInsuranceCoverage = $_POST["insurance"];
		$memberLisence = $_POST["lisence"];
	}
	
	//SQL CONNECTION
	$servername = "spc353_2.encs.concordia.ca";
	$username = "spc353_2";
	$password = "Tq5DjT";
	$dbname = "spc353_2";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	
	if($memberInsuranceCoverage == 1 && $memberLisence == 1)
	{
		//create member account
		$sql = "INSERT INTO member (password, name, address, email, accountBalance, privilege, DOB) 
				VALUES ('$memberPassword', '$memberName', '$memberAddress', '$memberEmail', '$memberInitialDeposit', '$memberPrivilege', '$memberDOB')";
		$conn->query($sql);		
		
	}
	
	$conn->close();
	
	
	
	echo '<script type="text/javascript"> window.open("admin.php","_self");</script>';
?>