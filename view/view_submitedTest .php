<blockquote>
	<h3>Результаты: <?php echo $_GET["test"]; ?></h3>
	<?php foreach($this->submitedQuestions as $q) { $q->render(); } ?>
</blockquote>

