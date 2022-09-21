<?php 

	session_start();

	if (isset($_SESSION['account']) && isset($_SESSION['name'])) {

?>

<html>
	<head>

		<title>Reserve</title>
		
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
		
		<?php 
			require_once "database.php";
			
			if ( isset($_POST['reserve']) && isset($_POST['isbn']) )
			{
				$isbn = $conn -> real_escape_string($_POST['isbn']);
				$name = $conn -> real_escape_string($_SESSION['account']);
				$date = $conn -> real_escape_string(date('Y-m-d'));
				$sql = "INSERT INTO reservations (ISBN, Username, ReservedDate) VALUES ('$isbn', '$name', '$date')";
				
				echo "<div class='regisdiv'>";
				echo "<div class='custom-feedback'>";
				
				if ($conn->query($sql) === TRUE) 
				{
					$sql = "UPDATE books SET Reserved='Y' WHERE ISBN='$isbn'";
					
					if ($conn->query($sql) === TRUE) 
					{
						echo "<p class='feedback'>Reservation Confirmed</p>";
					} else {
						// Return reservations to its original state
						$sql = "DELETE FROM reservations WHERE ISBN='$isbn'";
						$conn->query($sql);
						echo "<p class='error'>Error: Failed to Reserve Book</p>";
					}
				} else {
					echo "<p class='error'>Error: Failed to Reserve Book</p>";
				}
				
				echo "<br><a href='search.php?pageno=1' class='search-button'>Return to Search</a>";
				echo "<a href='home.php' class='search-button'>Return to Dashboard</a>";
				echo "</div>";
				echo "</div>";
				echo '<div class="fixed-footer">';
				include("footer.php");
				echo '</div>';
				return;
			}
			
			$isbn = $conn -> real_escape_string($_GET['isbn']);
			$sql = "SELECT BookTitle, Author, ISBN FROM books WHERE ISBN='$isbn'";
			$result = $conn->query($sql);
			$row = $result->fetch_assoc();
			
			echo ('<form method="post">');
			echo ("<label>Reserve <span style='color: black;'>" . $row["BookTitle"] . "</span> by <span style='color: black;'>" . $row["Author"] . "</span> ?</label>");
			echo ('<input type-"hidden" ');
			echo ('name="isbn" type="hidden" value="'.htmlentities($row["ISBN"]).'">' . "<br><br>" );
			echo ("<a href='search.php?pageno=1' class='search-button'>Return to Search</a>");
			echo ('<button class="search-button reserve-button" type="submit" value="Reserve" name="reserve">Reserve</button>');
			echo ("\n</form>\n");
			include("footer.php");
			return;
			
		?>
		
	</body>

</html>

<?php 

	} else {

		$_SESSION["error"] = "You must login to use Library Services";
		header("Location: login.php");

		return;
	}

?>