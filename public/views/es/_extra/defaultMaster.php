<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8" />
	<?php
		echo $this->getHeaders([
			'title' =>	'Innsert Framework',
			'css'	=>	[
				'public/css/innsert.css'
			],
			'js'	=>	[
				'public/js/jquery.min.js',
				'public/js/innsert.js'
			]
		]);
	?>
</head>

<body>
	<?php echo $view ?>
</body>

</html>