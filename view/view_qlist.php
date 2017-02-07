<div class="list-group">
	<?php foreach($this->getList() as $q) { ?>
		<a href="./test.php?test=<?php echo $q; ?>" class="list-group-item"><?php echo $q; ?></a>
	<?php } ?>
</div>
