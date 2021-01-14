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

		<script type="module" src="./public/js/init.js"></script>
		
		<?= $this->section('head'); ?>
	</head>
	<body>
		<?= $this->render('includes/navbar'); ?>

		<?php $this->success(); ?>

		<?= $this->section('body'); ?>
	</body>
</html>