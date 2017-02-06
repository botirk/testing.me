<?
    $test = new Test(1);
    if (!$test->access) {
        echo 'Вам запрещено проходить этот тест!';
    }
?>
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
		<span style="margin-right:1ex">Выберете тест или загрузите свой</span> 
		<input type="file" name="json" style="display:inline; color:transparent;" accept=".json">
		<script>
			$(":file").change(function(){ if ($(":file")[0].files.length == 1){
				var data = new FormData();	
				data.append("json",$(":file")[0].files[0]);
				
				var request = new XMLHttpRequest();
				request.onreadystatechange = function(){ if(request.readyState == 4){
					var json = JSON.parse(request.responseText);
					if (json.error)
						alert(json.error);
					else if (json.success) {
						$("list-group").append("<a href=./test.php?test=" + json.success.slice(0,-5) + "\" class=\"list-group-item\">$file</a>");
					}
				}};
				request.open("POST","./upload.php");
				request.send(data);
			}});
		</script>
		<div class="list-group">
			<?php
				$dirHandler = opendir('../tests_json/');
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
