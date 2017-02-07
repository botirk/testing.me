<!doctype html>
<html>
<?php include('../view/view_head.php'); ?>
<body>
	<?php
		include('../view/view_header.php');
		if (!file_exists('../tests_json/'.$_GET['test'].'.json'))
			echo '<script>alert("Вы пытаетесь найти результаты несуществующего теста");</script>';
		else {
			include('../utils.php');
			$submit = new SubmitedTest(json_decode(file_get_contents('../tests_json/'.$_GET['test'].'.json'),true));
			echo '<script>alert("Правильно отвечено на ',$submit->correct(),' из ',$submit->all(),' вопросов");</script>';
			$submit->render();
		}
	?>
</body>
</html>
