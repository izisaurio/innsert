<ul class="pagination right">

	<?php $firstPageAttrs = ($pagination->index == 1) ? ['class' => 'dis'] : []; ?>

	<li>
		<?php echo a(
			'<i class="fa fa-angle-double-left"></i>',
			url($pagination->urlBase)->add(1)->params($pagination->urlParams)->make(),
			($pagination->index == 1 ? ['class' => 'dis'] : [])
		) ?>
	</li>

	<?php foreach (range(
		max(1, $pagination->index - 3),
		min($pagination->totalPages, $pagination->index + 3)
	) as $page) : ?>

		<li <?php echo attrs(($page == $pagination->index ? ['class' => 'act'] : [])) ?>>
			<?php echo a($page, url($pagination->urlBase)->add($page)->params($pagination->urlParams)->make()) ?>
		</li>

	<?php endforeach; ?>

	<li>
		<?php echo a(
			'<i class="fa fa-angle-double-right"></i>',
			url($pagination->urlBase)->add($pagination->totalPages)->params($pagination->urlParams)->make(),
			($pagination->index == $pagination->totalPages ? ['class' => 'dis'] : [])
		) ?>
	</li>

</ul>