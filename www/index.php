<!doctype html>
<html>
<?php include('../view/view_head.php'); ?>
<body>
	<?php include('../view/view_header.php'); ?>
	<blockquote>
		<?php
			include('../view/view_upload.php');
			include('../utils.php');
			(new TestList())->render();
		?>
	</blockquote>
</body>
</html>
