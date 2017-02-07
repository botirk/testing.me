<!doctype html>
<html>
<?php include('../view/view_head.php'); ?>
<body>
	<?php 
		include('../view/view_header.php');
		if (!file_exists('../tests_json/'.$_GET['test'].'.json'))
			echo '<script>alert("Вы пытаетесь пройти несуществующий тест");</script>';
		else {
			include('../utils.php');
			(new Test(json_decode(file_get_contents('../tests_json/'.$_GET['test'].'.json'),true)))->render();
		}
	?>
</body>
</html>
