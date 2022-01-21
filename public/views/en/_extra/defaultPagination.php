<div>
	<select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" style="display: inline-block; width: 75px;">
		<?php for ($i = 1; $i <= $pagination->totalPages; $i++): ?>

			<?php if ($pagination->index == $i): ?>
				<option selected="selected">
					<?php echo $i ?>
				</option>
			<?php else: ?>
				<option <?php echo attrs(['value' => url($pagination->urlBase, $i)->params($pagination->urlParams)]) ?>>
					<?php echo $i ?>
				</option>
			<?php endif ?>

		<?php endfor ?>
	</select>
</div>