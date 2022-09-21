<?php 

	session_start();
	unset($_SESSION["account"]);
	
	require_once "database.php";
	
	$uname = '';
	$pass = '';
	
	if (isset($_POST['uname']) && isset($_POST['password'])) {

		$uname = $conn -> real_escape_string($_POST['uname']);
		$pass = $conn -> real_escape_string($_POST['password']);
		
		if (empty($uname)) {
			
			$_SESSION["error"] = "Username is required";
			
		} else if (empty($pass)) {
			
			$_SESSION["error"] = "Password is required";
			
		} else {

			$sql = "SELECT * FROM users WHERE Username='$uname' AND Password='$pass'";
			
			$result = $conn->query($sql);
			
			if ($result->num_rows === 1) {

				$row = $result->fetch_assoc();
				
				if ($row['Username'] === $uname && $row['Password'] === $pass) {

					$_SESSION['account'] = $row['Username'];

					$_SESSION['name'] = $row['FirstName'] . ' ' . $row['Surname'];

					header("Location: home.php");

					return;
					
				} else {

					$_SESSION["error"] = "Incorrect Username or Password";
					header("Location: login.php");

					return;
					
				}
				
			} else {
				
				$_SESSION["error"] = "Incorrect Username or Password";
				header("Location: login.php");

				return;
				
			}		
		}	
	}
	
?>

<html>

	<head>

		<title>Login</title>
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="CSS/style.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
		
	</head>

	<body>
	
		<header>
			<?php include("header.php"); ?>
		</header>
		<div class="regisdiv">
			<form method="post">

				<h2>LOGIN</h2>

				<?php if (isset($_SESSION["error"])) { ?>

					<p class="error"><?php echo $_SESSION["error"]; ?></p>
					
				<?php unset($_SESSION["error"]); } ?>
				
				<?php if (isset($_SESSION["feedback"])) { ?>

					<p class="feedback"><?php echo $_SESSION["feedback"]; ?></p>
					
				<?php unset($_SESSION["feedback"]); } ?>
				
				<input type="text" name="uname" placeholder="User Name" value="<?php echo $uname; ?>"><br>
				
				<input type="password" name="password" placeholder="Password"><br> 

				<button type="submit">Login</button>
				<p>Don't have an account? <a href="register.php">Register now</a></p>
				
			</form>
		</div>
		<div class="fixed-footer">
			<?php include("footer.php"); ?>
		</div>
		
	</body>

</html>