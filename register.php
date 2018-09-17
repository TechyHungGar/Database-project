<?php
	//HEAD FOR ALL SCRIPTS
	//For browser to reload at every request and not cache
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	
	
	//SQL CONNECTION
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
   }
		
  </style>
</head>
<body> 

<?php
if(isset($_POST['register']))
{
	$memberName = $_POST['name'];
    $address = $_POST['address'];
	$email = $_POST['email'];
	$DOB = $_POST['DOB'];
	$deposit = ($_POST['deposit'] - 100);
	$password = $_POST['password'];
	 
	$sql = "INSERT INTO member (password, name, address, email, accountBalance, DOB) 
			VALUES ('$password', '$memberName', '$address', '$email', '$deposit', '$DOB')";
	$conn->query($sql);
	$conn->close();	
	echo '<script type="text/javascript"> window.open("index.php","_self");</script>'; 
}

if(isset($_POST['validate']))   // it checks whether the user clicked login button or not 
{
     $memberName = $_POST['name'];
     $address = $_POST['address'];
	 $email = $_POST['email'];
	 $DOB = $_POST['DOB'];	
	

	$sql = "SELECT * FROM member WHERE name = '$memberName' AND address = '$address' AND email = '$email' AND DOB = '$DOB'";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) 
	{
		echo '<div class="container">
				<div class="well">

				  <h2>REGISTER</h2>
				  Enter your info:<br><br>
				  <form action="" method="POST">
					<div class="form-group">
					  <label for="text">Name:</label>
					  <input type="text" name="name" class="form-control" placeholder="Firstname Lastname">
					</div>					
					<div class="form-group">
					  <label for="text">Address:</label>
					  <input type="text" name="address" class="form-control" placeholder="Street"> 
					</div>
					<div class="form-group">
					  <label for="text">Email:</label>
					  <input type="text" name="email" class="form-control" placeholder="example@domain.com">
					</div>
					<div class="form-group">
					  <label for="text">Date of Birth:</label>
					  <input type="date" name="DOB" class="form-control"> 
					</div>	
					<div class="form-group">
					  <label for="text">Initial Deposit (subscription is $100)</label>
					  <input type="text" name="deposit" class="form-control" placeholder="$">
					</div>						
					<div class="form-group">
					  <label for="text">Password:</label>
					  <input type="password" name="password" class="form-control"> 
					</div>					
					  <button type="submit" name ="register" class="btn btn-default">Submit</button><br>	
				  </form>
				</div>
				</div>';
		
    }
}
else {
form:
 ?>

<center><img src="images/minilogo.PNG"></center>

<div class="container">
<div class="well">

  <h2>REGISTER</h2>
  Enter valid information of another member:<br><br>
  <form action="" method="POST">
    <div class="form-group">
      <label for="text">Name:</label>
      <input type="text" name="name" class="form-control" placeholder="Firstname Lastname">
    </div>
	<div class="form-group">
	  <label for="text">Address:</label>
      <input type="text" name="address" class="form-control" placeholder="Street"> 
    </div>
	<div class="form-group">
	  <label for="text">Email:</label>
      <input type="text" name="email" class="form-control" placeholder="example@domain.com">
	</div>
	<div class="form-group">
	  <label for="text">Date of Birth:</label>
      <input type="date" name="DOB" class="form-control"> 
    </div>	  
	  <button type="submit" name ="validate" class="btn btn-default">Submit</button><br>	
  </form>
</div>
</div>


</body>
</html>


<?php
};
?>