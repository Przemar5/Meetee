<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title></title>

		<link rel="stylesheet" type="text/css" href="./public/css/style.css">

		<style type="text/css">
			body {

			}

			label {
				display: block;
			}
		</style>

		<script src="./public/js/libs/DateAdapter.js"></script>
		<script src="./public/js/libs/security/validation/validation.js"></script>
		<script src="./public/js/libs/security/validation/template.js"></script>
		<script src="./public/js/libs/security/validation/form_validation.js"></script>
		<script type="module" src="./public/js/app/pages<?= str_replace('-', '_', $_SERVER['PATH_INFO'] ?? '/'); ?>.js" defer></script>
		
		<?= $this->section('head'); ?>
	</head>
	<body>
		<?= $this->render('includes/navbar'); ?>

		<?php $this->success(); ?>

		<?= $this->section('body'); ?>
	</body>
</html>