<?php 

	session_start();

	if (isset($_SESSION['account']) && isset($_SESSION['name'])) {

?>

<html>
	<head>

		<title>View Reserved</title>
		
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
			
			if (isset($_GET['pageno'])) {
				$pageno = $_GET['pageno'];
			} else {
				$pageno = 1;
			}
			
			$user = $conn->real_escape_string($_SESSION["account"]);
			$sql = "SELECT * FROM books INNER JOIN reservations ON books.ISBN = reservations.ISBN INNER JOIN categories ON books.Category = categories.CategoryID WHERE Username='$user'";
			$result = $conn->query($sql);
			
			$numrows = $result->num_rows;
			$rows_per_page = 5;
			$lastpage = ceil($numrows/$rows_per_page);
			
			$pageno = (int)$pageno;
			if ($pageno > $lastpage) {
				$pageno = $lastpage;
			}
			if ($pageno < 1) {
				$pageno = 1;
			}
			
			$limit = 'LIMIT ' . ($pageno - 1) * $rows_per_page . ',' . $rows_per_page;
			$sql = "SELECT * FROM books INNER JOIN reservations ON books.ISBN = reservations.ISBN INNER JOIN categories ON books.Category = categories.CategoryID WHERE Username='$user' $limit";
			$result = $conn->query($sql);
			
			echo "<div class='regisdiv'>";
			echo "<div class='custom-table'>";
			echo "<h2>Your Reserved Books</h2>";
			
			if (isset($_SESSION["error"])) {
				echo "<p class='error'>" . $_SESSION['error'] . "</p>";
				unset($_SESSION["error"]); 
			}
			
			if (isset($_SESSION["feedback"])) {
				echo "<p class='feedback'>" . $_SESSION['feedback'] . "</p>";
				unset($_SESSION["feedback"]); 
			}
			
			if ($result->num_rows > 0) {
				echo "<table  class='table table-dark'>";
				echo "<thead>";
					echo "<tr>";
						echo "<th scope='col'>ISBN</th>";
						echo "<th scope='col'>BookTitle</th>";
						echo "<th scope='col'>Author</th>";
						echo "<th scope='col'>Edition</th>";
						echo "<th scope='col'>Year</th>";
						echo "<th scope='col'>Category</th>";
						echo "<th scope='col'>Reserved Date</th>";
					echo "</tr>";
				echo "</thead>";
				echo "<tbody>";
				while ($row = $result->fetch_assoc())
				{
					echo "<tr><td>";
					echo(htmlentities($row["ISBN"]));
					echo "</td><td>";
					echo(htmlentities($row["BookTitle"]));
					echo "</td><td>";
					echo(htmlentities($row["Author"]));
					echo "</td><td class='text-center'>";
					echo(htmlentities($row["Edition"]));
					echo "</td><td>";
					echo(htmlentities($row["Year"]));
					echo "</td><td>";
					echo(htmlentities($row["CategoryDescription"]));
					echo "</td><td>";
					$date = date_format(date_create($row["ReservedDate"]), "j-M-Y");
					echo(htmlentities($date));
					echo "</td><td>";
					echo '<a href="unreserve.php?isbn='.htmlentities($row["ISBN"]).'">Remove</a>';
					echo "</td></tr>\n";
				}
				echo "</tbody>";
				echo "</table>\n";
				if ($pageno == 1) {
					echo " <div class='search-button search-button-disabled'>FIRST</div> <div class='search-button search-button-disabled'>PREV</div> ";
				} else {
					echo " <a href='{$_SERVER['PHP_SELF']}?pageno=1' class='search-button'>FIRST</a> ";
					$prevpage = $pageno-1;
					echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$prevpage' class='search-button'>PREV</a> ";
				}
				
				echo " <span style='margin: auto 20px;'> ( Page $pageno of $lastpage ) </span> ";
				
				if ($pageno == $lastpage) {
					echo " <div class='search-button search-button-disabled'>NEXT</div> <div class='search-button search-button-disabled'>LAST</div> ";
				} else {
					$nextpage = $pageno+1;
					echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$nextpage' class='search-button'>NEXT</a> ";
					echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$lastpage' class='search-button'>LAST</a> ";
				}
				echo "<br>";
				
			} else {
				echo "<h3>0 Results</h3><p class='error'>You Have No Reserved Books<p>";
			}
			
			echo "<br><a href='home.php' class='search-button'>Return to Home</a>";
			echo "</div>";
			echo "</div>";
			echo "<div class='fixed-footer'>";
			include("footer.php");
			echo "</div>";
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