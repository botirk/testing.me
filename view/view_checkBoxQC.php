<div class='panel panel-default'>
	<div class='panel-heading'><span class='text-<?php echo $this->isCorrect() ? 'success' : 'danger' ?>'><?php echo $this->getQJSON()["question"]; ?></span></div>
	<div class='panel-body'>
		<?php 
			$i = 0;
			foreach($this->getQJSON()["variations"] as $variation) {
		?>
			<label class='bg-<?php echo $this->isAnswer($i) ? 'success' : 'danger' ?> text-white'><input type='checkbox' <?php if($this->isset($i++)) echo 'checked'; ?> disabled><?php echo $variation; ?></label><br>
		<?php } ?>
	</div>
</div>
