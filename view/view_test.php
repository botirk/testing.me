<blockquote>
<form method="POST" action='./result.php?test=<?php echo $_GET['test']; ?>'>
	<?php foreach($this->questions as $q) { $q->render(); } ?>
	<button type="submit">Завершить</button>
</form>
</blockquote>

