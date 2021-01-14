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

		<!-- development version, includes helpful console warnings -->
		<!-- <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script> -->
		<script type="module" src="./public/js/init.js"></script>
		<script type="module" src="./public/js/libs/security/validation/validation.js"></script>
		
		<?= $this->section('head'); ?>
	</head>
	<body>
		<?= $this->render('includes/navbar'); ?>

		<?php $this->success(); ?>

		<?= $this->section('body'); ?>
	</body>
</html>