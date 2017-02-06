<!doctype html>
<html>
<head>
	<title>testing.me</title>
	<link rel="stylesheet" href="./js/bootstrap.min.css">
	<link rel="stylesheet" href="./js/bootstrap-theme.min.css">
	<script src="./js/jquery-3.1.1.min.js"></script>
	<script src="./js/bootstrap.min.js"></script>
</head>	
<body>
	<nav class="navbar navbar-default">
  		<div class="container-fluid">
	  		<div class="navbar-header">
			  <a class="navbar-brand" href="/">testing.me</a>
			</div>
		</div>
	</nav>
	<blockquote>
	<p>Выберете тест</p>
		<div class="list-group">
			<?php
				$dirHandler = opendir("../tests_json/");
				while($file = readdir($dirHandler)) {
					$file = substr($file,0,-5);
					if ($file)
						echo "<a href=\"./test.php?test=$file\" class=\"list-group-item\">$file</a>";
				}
				closedir($dirHandler);
			?>
		</div>
	</blockquote>
</body>
</html>
