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
		$type = $_GET["type"];
		$id = $_GET["id"];		
	}
	
	//SQL CONNECTION
	$servername = "spc353_2.encs.concordia.ca";
	$username = "spc353_2";
	$password = "Tq5DjT";
	$dbname = "spc353_2";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	
	if($type == 'trip')
	{
		$sql = "DELETE FROM trip WHERE tripID = $id";
		$conn->query($sql);
		$conn->close();
		echo '<script type="text/javascript"> window.open("home.php","_self");</script>';
	}
	if($type == 'member')
	{
		$sql = "DELETE FROM member WHERE memberID = $id";
		$conn->query($sql);
		$conn->close();
		echo '<script type="text/javascript"> window.open("admin.php","_self");</script>';
	}
	
?>