<?php
	session_start();
	
	require_once "database.php";
	
	$uname = $pass = $pass2 = $fname = $surname = $line1 = $line2 = $city = $telephone = $mobile = "";
	$uname_err = $pass_err = $pass2_err = $fname_err = $surname_err = $line1_err = $line2_err = $city_err = $telephone_err = $mobile_err = "";

	if($_SERVER["REQUEST_METHOD"] == "POST") {
		
		// Validate username
		if(empty(trim($_POST["uname"]))) {
			$uname_err = "Please enter a username.";
		} elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["uname"]))) {
			$uname_err = "Username can only contain letters, numbers, and underscores.";
		} else {
			$uname = $conn -> real_escape_string($_POST["uname"]);
			$sql = "SELECT * FROM users WHERE Username='$uname'";
			
			$result = $conn->query($sql);
			
			if ($result->num_rows === 1) {
				$uname_err = "This username is already taken.";
			}
		}
		
		// Validate password
		if(empty(trim($_POST["pass"]))){
			$pass_err = "Please enter a password.";     
		} elseif(strlen(trim($_POST["pass"])) != 6){
			$pass_err = "Password must have exactly 6 characters.";
		} else{
			$pass = $conn -> real_escape_string($_POST["pass"]);
		}
		
		// Validate confirm password
		if(empty(trim($_POST["pass2"]))){
			$pass2_err = "Please confirm password.";     
		} else{
			$passcheck = $conn -> real_escape_string($_POST["pass2"]);
			if(empty($pass_err) && ($pass != $passcheck)){
				$pass2_err = "Password did not match.";
			} else {
				$pass2 = $conn -> real_escape_string($_POST["pass2"]);
			}
		}
		
		// Validate first name
		if(empty(trim($_POST["fname"]))){
			$fname_err = "Please enter first name.";     
		} else{
			$fname = $conn -> real_escape_string($_POST["fname"]);
		}
		
		// Validate surname
		if(empty(trim($_POST["surname"]))){
			$surname_err = "Please enter surname.";     
		} else{
			$surname = $conn -> real_escape_string($_POST["surname"]);
		}
		
		// Validate Address line 1
		if(empty(trim($_POST["line1"]))){
			$line1_err = "Please enter address line 1.";     
		} else{
			$line1 = $conn -> real_escape_string($_POST["line1"]);
		}
		
		// Validate Address line 2
		if(empty(trim($_POST["line2"]))){
			$line2_err = "Please enter address line 2.";     
		} else{
			$line2 = $conn -> real_escape_string($_POST["line2"]);
		}
		
		// Validate city
		if(empty(trim($_POST["city"]))){
			$city_err = "Please enter city.";     
		} else{
			$city = $conn -> real_escape_string($_POST["city"]);
		}
		
		// Validate telephone
		if(empty(trim($_POST["telephone"]))){
			$telephone_err = "Please enter telephone number.";     
		} elseif(!is_numeric($_POST['telephone'])) {
			$telephone_err = "Invalid Number: Please enter only numbers.";
		} elseif(strlen(trim($_POST["telephone"])) != 7){
			$telephone_err = "Number must have 7 digits.";
		} else{
			$telephone = $conn -> real_escape_string($_POST["telephone"] + 0);
		}
		
		// Validate mobile
		if(empty(trim($_POST["mobile"]))){
			$mobile_err = "Please enter mobile number.";     
		} elseif(!is_numeric($_POST['mobile'])) {
			$mobile_err = "Invalid Number: Please enter only numbers.";
		} elseif(strlen(trim($_POST["mobile"])) != 10){
			$mobile_err = "Number must have 10 digits.";
		} else{
			$mobile = $conn -> real_escape_string($_POST["mobile"] + 0);
		}
		
		// If no errors are present, INSERT into the database
		if(empty($username_err) && empty($pass_err) && empty($pass2_err) && empty($fname_err) && empty($surname_err) && empty($line1_err) && empty($line2_err) && empty($city_err) && empty($telephone_err) && empty($mobile_err)){
        
			$sql = "INSERT INTO users (Username, Password, FirstName, Surname, AddressLine1, AddressLine2, City, Telephone, Mobile) VALUES ('$uname', '$pass', '$fname', '$surname', '$line1', '$line2', '$city', '$telephone', '$mobile')";
			if ($conn->query($sql) === TRUE) 
			{
				$_SESSION["feedback"] = "Account created successfully";
			} else {
				$_SESSION["error"] = "Error in creating the account";
			}
			
			header("Location: login.php");
			return;
		}
	}
?>

<html>

	<head>

		<title>Register</title>
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="CSS/style.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
		
	</head>

	<body style="display: block">
	
		<header>
			<?php include("header.php"); ?>
		</header>
		
		<div class="regisdiv">
			<form method="post" id="register">

				<h2>REGISTER</h2>

				<label>User Name</label>
				<input type="text" name="uname" placeholder="User Name" value="<?php echo $uname; ?>">
				<span class="<?php echo (!empty($uname_err)) ? 'is-invalid' : ''; ?>"><?php echo $uname_err; ?></span><br>

				<label>Password</label>
				<input type="password" name="pass" placeholder="Password" value="<?php echo $pass; ?>">
				<span class="<?php echo (!empty($pass_err)) ? 'is-invalid' : ''; ?>"><?php echo $pass_err; ?></span><br> 
				
				<label>Confirm Password</label>
				<input type="password" name="pass2" placeholder="Confirm Password" value="<?php echo $pass2; ?>">
				<span class="<?php echo (!empty($pass2_err)) ? 'is-invalid' : ''; ?>"><?php echo $pass2_err; ?></span><br> 
				
				<label>First Name</label>
				<input type="text" name="fname" placeholder="First Name" value="<?php echo $fname; ?>">
				<span class="<?php echo (!empty($fname_err)) ? 'is-invalid' : ''; ?>"><?php echo $fname_err; ?></span><br> 
				
				<label>Surname</label>
				<input type="text" name="surname" placeholder="Surname" value="<?php echo $surname; ?>">
				<span class="<?php echo (!empty($surname_err)) ? 'is-invalid' : ''; ?>"><?php echo $surname_err; ?></span><br> 
				
				<label>Address</label>
				<input type="text" name="line1" placeholder="Line 1" value="<?php echo $line1; ?>">
				<span class="<?php echo (!empty($line1_err)) ? 'is-invalid' : ''; ?>"><?php echo $line1_err; ?></span><br> 
				<input type="text" name="line2" placeholder="Line 2" value="<?php echo $line2; ?>">
				<span class="<?php echo (!empty($line2_err)) ? 'is-invalid' : ''; ?>"><?php echo $line2_err; ?></span><br> 
				
				<label>City</label>
				<input type="text" name="city" placeholder="City" value="<?php echo $city; ?>">
				<span class="<?php echo (!empty($city_err)) ? 'is-invalid' : ''; ?>"><?php echo $city_err; ?></span><br>
				
				<label>Telephone</label>
				<input type="text" name="telephone" placeholder="Telephone" maxlength="7" value="<?php echo $telephone; ?>">
				<span class="<?php echo (!empty($telephone_err)) ? 'is-invalid' : ''; ?>"><?php echo $telephone_err; ?></span><br> 
				
				<label>Mobile</label>
				<input type="text" name="mobile" placeholder="Mobile" maxlength="10" value="<?php echo $mobile; ?>">
				<span class="<?php echo (!empty($mobile_err)) ? 'is-invalid' : ''; ?>"><?php echo $mobile_err; ?></span><br> 

				<button type="submit">Register</button>
				<br><br>
				
			</form>
		</div>
		
		<?php include("footer.php"); ?>
		
	</body>

</html>