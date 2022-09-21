<html>
	<body>
		<header>
			<nav class="navbar navbar-inverse navbar-fixed-top">
				<div class="container-fluid">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>                        
						</button>
						<a class="navbar-brand" href="home.php" style="color: white;"><span class="glyphicon glyphicon-book"></span> LIBRARY </a>
					</div>
					<div class="collapse navbar-collapse" id="myNavbar">
						<ul class="nav navbar-nav">
							<li><a href="home.php">Home</a></li>
							<li><a href="search.php">Search</a></li>
							<li><a href="view_reserved.php">Reserved Books</a></li>
						</ul>
						<ul class="nav navbar-nav navbar-right">
							<?php if ( !isset($_SESSION["account"])) { ?>
							<li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
							<?php } else { ?>
							<li><a><span class="glyphicon glyphicon-user"></span> <?php echo ($_SESSION["name"]); ?></a></li>
							<li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
							<?php } ?>
						</ul>
					</div>
				</div>
			</nav>
		</header>
	</body>
</html>