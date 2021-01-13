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

		<script src="./public/js/index.js"></script>
		
		<?= $this->section('head'); ?>
	</head>
	<body>
		<?php $this->success(); ?>

		<?= $this->render('includes/navbar'); ?>

		<?= $this->section('body'); ?>
	</body>
</html>