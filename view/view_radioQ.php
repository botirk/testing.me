<div class='panel panel-default'>
	<div class='panel-heading'><span><?php echo $this->getQJSON()["question"]; ?></span></div>
	<div class='panel-body'>
		<?php 
			$i = 0;
			foreach($this->getQJSON()["variations"] as $variation) { 
		?>
			<label><input type='radio' name='<?php echo $this->getNum(); ?>' value='<?php echo $i++; ?>'><?php echo $variation; ?></label><br>
		<?php } ?>
	</div>
</div>
