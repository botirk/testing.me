<div class='panel panel-default'>
	<div class='panel-heading'><span class='text-<?php echo $this->isCorrect() ? 'success' : 'danger' ?>'><?php echo $this->getQJSON()["question"]; ?></span></div>
	<div class='panel-body'>
		<input type='text' value=<?php echo '"'.$this->text().'"' ?> class=<?php echo $this->isCorrect() ? '"bg-success text-white"' : '"bg-danger text-white"'  ?> disabled>
		<?php if (!$this->isCorrect()) echo '<span class="text-success">'.$this->getQJSON()["answer"].'</span>'; ?>
	</div>
</div>
