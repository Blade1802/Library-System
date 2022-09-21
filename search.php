<?php 

	session_start();

	if (isset($_SESSION['account']) && isset($_SESSION['name'])) {

?>

<html>
	<head>

		<title>Search</title>
		
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
		
		<?php 
			require_once "database.php";
			
			if (isset($_POST['search_submit']) || isset($_GET['pageno'])) {
				
				if (isset($_GET['pageno'])) {
					$pageno = $_GET['pageno'];
					$where = $_SESSION['where'];
				} else {
					$pageno = 1;
				}
				
				if (isset($_POST['search_submit'])) {
					if ($_POST['search_submit'] == 2) {
						if (isset($_POST["category"])) {
							$c = $conn->real_escape_string($_POST["category"]);
							$where = "WHERE Category='$c'";
							$_SESSION['where'] = $where;
						} else {
							$_SESSION["error"] = "Please select a Category";
							header("Location: search.php");
							return;
						}
					} else {
						if ($_POST['partial'] == 2) {
							$t = $conn->real_escape_string($_POST["title"]);
							$a = $conn->real_escape_string($_POST["author"]);
							if (empty($a)) {
								$_SESSION["error"] = "Please enter an Author";
								header("Location: search.php");
								return;
							} elseif (empty($t)) {
								$_SESSION["error"] = "Please enter a Book Title";
								header("Location: search.php");
								return;
							} else {
								$where = "WHERE BookTitle LIKE '%$t%' OR Author LIKE '%$a%'";
							}
							$where = "WHERE BookTitle LIKE '%$t%' AND Author LIKE '%$a%'";
							$_SESSION['where'] = $where;
						} else {
							$t = $conn->real_escape_string($_POST["title"]);
							$a = $conn->real_escape_string($_POST["author"]);
							if (empty($t) && empty($a)) {
								$_SESSION["error"] = "Please enter either a Title or an Author";
								header("Location: search.php");
								return;
							} elseif (empty($a)) {
								$where = "WHERE BookTitle LIKE '%$t%'";
							} elseif (empty($t)) {
								$where = "WHERE Author LIKE '%$a%'";
							} else {
								$where = "WHERE BookTitle LIKE '%$t%' OR Author LIKE '%$a%'";
							}
							$_SESSION['where'] = $where;
						}
					}
				}
				
				$sql = "SELECT * FROM books $where";
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
				$sql = "SELECT * FROM books INNER JOIN categories ON books.Category = categories.CategoryID $where $limit";
				$result = $conn->query($sql);
				echo "<div class='regisdiv'>";
				echo "<div class='custom-table'>";
				echo "<h2>Search Results:</h2>";
				
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
						if ($row["Reserved"] == "N") {
							echo '<a href="reserve.php?isbn='.htmlentities($row["ISBN"]).'">Reserve</a>';
						} else {
							echo 'Reserved';
						}
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
					echo "<h3>0 Results</h3><p class='error'>No Books Found Matching Your Criteria<p>";
				}
				
				echo "<br><a href='search.php' class='search-button'>Return to Search</a>";
				echo "<a href='home.php' class='search-button'>Return to Home</a>";
				echo "</div>";
				echo "</div>";
				echo "<div class='fixed-footer'>";
				include("footer.php");
				echo "</div>";
				return;
			}
			
		?>
		
		<div class="regisdiv">
			<form method="post">

				<h2>Search</h2>

				<?php if (isset($_SESSION["error"])) { ?>

					<p class="error"><?php echo $_SESSION["error"]; ?></p>
					
				<?php unset($_SESSION["error"]); } ?>

				<label>By Book Title:</label>
				<input type="text" name="title" placeholder="Book Title"><br>
				
				<div class="text-center">
					<label class="clickInput">
						<input type="radio" name="partial" value="1" checked> OR
					</label>
								
					<label class="clickInput">
						<input type="radio" name="partial" value="2"> AND
					</label>
				</div>
				
				<label>By Author:</label>
				<input type="text" name="author" placeholder="Author"><br>
				<button class="dashboard-button" name="search_submit" value="1" type="submit">Search</button>
				
				<br><br>
				
				<label>By Book Category:</label>
				<select name="category">
					<option value="" disabled hidden selected>Select Book Category</option>
					<?php
						$sql = "SELECT * FROM categories";
						$result = $conn->query($sql);
						while ($row = $result->fetch_assoc())
						{
					?>
					<option value="<?php echo(htmlentities($row["CategoryID"])); ?>"><?php echo(htmlentities($row["CategoryDescription"])); ?></option>
					<?php
						}
					?>
				</select>
				<br>

				<button class="dashboard-button" name="search_submit" value="2" type="submit">Search</button>
				
			</form>
		</div>
		
		
		<?php include("footer.php"); ?>
		
	</body>
</html>

<?php 

	} else {

		$_SESSION["error"] = "You must login to use Library Services";
		header("Location: login.php");

		return;
	}

?>