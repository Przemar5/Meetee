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

		<script src="./public/js/libs/global/Interface.js"></script>
		<script src="./public/js/libs/http/Ajax.js"></script>
		<script src="./public/js/libs/view/TemplateGenerator.js"></script>
		
		<?= $this->section('head'); ?>
	</head>
	<body>
		<?= $this->render('includes/navbar'); ?>

		<?php $this->success(); ?>

		<?= $this->section('body'); ?>
	</body>
</html>