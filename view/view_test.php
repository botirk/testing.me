<blockquote>
<form method="POST" action='./result.php?test=<?php echo $_GET['test']; ?>'>
	<h3>Тестирование: <?php echo $_GET["test"]; ?></h3>
	<?php foreach($this->questions as $q) { $q->render(); } ?>
	<button type="submit">Завершить</button>
</form>
</blockquote>

