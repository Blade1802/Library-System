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
			
			if ( isset($_POST['remove']) && isset($_POST['isbn']) )
			{
				$isbn = $conn -> real_escape_string($_POST['isbn']);
				$sql = "UPDATE books SET Reserved='N' WHERE ISBN='$isbn'";
				
				if ($conn->query($sql) === TRUE) 
				{	
					$sql = "DELETE FROM reservations WHERE ISBN='$isbn'";
					
					if ($conn->query($sql) === TRUE) 
					{
						$_SESSION["feedback"] = "Book has been removed from reservation successfully";
						
					} else {
						// Return books to the original state
						$sql = "UPDATE books SET Reserved='Y' WHERE ISBN='$isbn'";
						$conn->query($sql);
						$_SESSION["error"] = "Failed to remove the book from reservation";
					}
					
				} else {
					$_SESSION["error"] = "Failed to remove the book from reservation";
				}
				
				header("Location: view_reserved.php");
				return;
			}
			
			$isbn = $conn -> real_escape_string($_GET['isbn']);
			$sql = "SELECT BookTitle, Author, ISBN FROM books WHERE ISBN='$isbn'";
			$result = $conn->query($sql);
			$row = $result->fetch_assoc();
			
			echo ('<form method="post">');
			echo ("<label>Remove <span style='color: black;'>" . $row["BookTitle"] . "</span> by <span style='color: black;'>" . $row["Author"] . "</span> ?</label>");
			echo ('<input type-"hidden" ');
			echo ('name="isbn" type="hidden" value="'.htmlentities($row["ISBN"]).'">' . "<br><br>" );
			echo ("<a href='view_reserved.php?pageno=1' class='search-button'>Return</a>");
			echo ('<button class="search-button reserve-button" type="submit" value="Remove" name="remove">Remove</button>');
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