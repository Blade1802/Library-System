<?php 

	session_start();

	if (isset($_SESSION['account']) && isset($_SESSION['name'])) {

?>

<!DOCTYPE html>

<html>

	<head>

		<title>Home</title>
		
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
		<h1>Welcome</h1>
		<div class="regisdiv">
			<div class="dashboard">

				<h2>Dashboard</h2>

				<?php if (isset($_SESSION["error"])) { ?>

					<p class="error"><?php echo $_SESSION["error"]; ?></p>
					
				<?php unset($_SESSION["error"]); } ?>

				<a href="search.php" class="dashboard-button" >SEARCH BOOKS</a><br>
				
				<a href="view_reserved.php" class="dashboard-button" type="submit">VIEW RESERVED BOOKS</a><br> 
				
			</div>
		</div>
		
		<div class="fixed-footer">
			<?php include("footer.php"); ?>
		</div>
		
	</body>

</html>

<?php 

	} else {

		$_SESSION["error"] = "You must login to use Library Services";
		header("Location: login.php");

		return;
	}

?>