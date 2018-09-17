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
	else if($_SESSION["priv"] == 0)
		echo '<script type="text/javascript"> window.open("home.php","_self");</script>';
	else
	{
		$user = $_SESSION["use"];
		$userID = $_SESSION["id"];
	}
	
	$servername = "spc353_2.encs.concordia.ca";
	$username = "spc353_2";
	$password = "Tq5DjT";
	$dbname = "spc353_2";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	
	$sql = "SELECT * FROM member WHERE memberID = '$userID'";
	$result = $conn->query($sql);				
	$row = $result -> fetch_assoc();
		
	if($row["privilege"] == 0)
		echo '<script type="text/javascript"> window.open("home.php","_self");</script>';	

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
	   <li><a href="#" data-toggle="modal" data-target="#addUser">Add User</a></li>	   
      </ul>	 
      <ul class="nav navbar-nav navbar-right">	
		<li class="active"><a href="admin.php"><span class="glyphicon glyphicon-wrench"></span> Admin Panel</a></li>
        <li><a href="account.php"><span class="glyphicon glyphicon-user"></span><?php echo " ".$user;?></a></li>
		<li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Enter User Info</h4>
            </div>
            <div class="modal-body">
                <form action="addUser.php" method="POST">
				<div class="form-group">
					<label for="text"> Name: </label>
					<input type="text" name="name" class="form-control" placeholder="Firstname-Lastname">					
					<label for="text"> Password: </label>				
					<input type="password" name="password" class="form-control">				
					<label for="test"> Address: </label>
					<input type="text" name="address" class="form-control" placeholder="City/Province">
					<label for="test"> Email: </label>
					<input type="text" name="email" class="form-control" placeholder="your-email@domain.com">	
					<label for="text"> Date Of Birth: </label>				
					<input type="date" name="dob" class="form-control">
					<label for="text"> Membership Payment Amount: </label>				
					<input type="text" name="balance" class="form-control" placeholder="$">									
					<label><input type="radio" name="admin" value="1">Admin</label> <label><input type="radio" name="admin" value="0">Member</label><br>
					<br><label>Valid Lisence? <input type="radio" name="lisence" value="1">Yes</label> <label><input type="radio" name="lisence" value="0">No</label><br>
					<label>Covered By Insurance? <input type="radio" name="insurance" value="1">Yes</label> <label><input type="radio" name="insurance" value="0">No</label>
				</div>
				
            </div>
            <div class="modal-footer">
            <button type="submit" name ="loginReg" class="btn btn-default">Submit</button> 
            </form>    
            </div>
        </div>
    </div>
</div>

<?php

	$sql = "SELECT * FROM member JOIN driver ON member.memberID = driver.memberID AND member.memberID <> $userID";
	$result = $conn->query($sql);
	
?>

<div class="container-fluid text-center"> 
<h1> User List: </h1>
<table class="table">
	<thead>
	<tr>
		<td><b>Member ID</td>
		<td><b>Name</td>
		<td><b>Address</td>
		<td><b>Email</td>
		<td><b>Date of Birth</td>
		<td><b>Account Balance</td>
		<td><b>License</td>
		<td><b>Insurance</td>		
		
	</tr>
	</thead>
	<tbody>
	<?php
		while($row = $result -> fetch_assoc())
		{
			echo '<tr>';
			echo '<td>' .$row["memberID"]. '</td>';
			echo '<td>' .$row["name"]. '</td>';
			echo '<td>' .$row["address"]. '</td>';
			echo '<td>' .$row["email"]. '</td>';
			echo '<td>' .$row["DOB"]. '</td>';
			echo '<td>' .$row["accountBalance"]. '$</td>';
			if($row["driversLisence"] == 1)
				echo '<td>Valid</td>';
			else
				echo '<td class="danger">Not Valid</td>';
			if($row["insuranceStatus"] == 1)
				echo '<td>Insured</td>';
			else
				echo '<td class="danger">Not Insured</td>';
			if($row["privilege"] == 1)
				echo '<td class="success"><a href="actionRequest.php?action=demote&id='.$row["memberID"].'"> Demote </a></td>';
			else
				echo '<td class="info"><a href="actionRequest.php?action=promote&id='.$row["memberID"].'"> Promote </a></td>';
			if($row["status"] ==1)
				echo '<td class="success"><a href="actionRequest.php?action=suspend&id='.$row["memberID"].'"> Suspend </a></td>';
			else
				echo '<td class="warning"><a href="actionRequest.php?action=activate&id='.$row["memberID"].'"> Activate </a></td>';
			echo '<td class="danger"><a href="delete.php?type=member&id='.$row["memberID"].'"> Delete </a></td>';
			echo '</tr>';
		}
	?>
	</tbody>
	</table>	
</div>

