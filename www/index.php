<!doctype html>
<html>
<?php include('../view/view_head.php'); ?>
<body>
	<?php include('../view/view_header.php'); ?>
	<blockquote>
		<?php
			include('../view/view_upload.php');
			include('../utils.php');
			(new QuestionList())->render();
		?>
	</blockquote>
</body>
</html>
