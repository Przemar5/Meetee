<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title></title>

		<link rel="stylesheet" type="text/css" href="./public/css/style.css">

		<style type="text/css">
			label { display: block; }
		</style>

		<script src="<?= JS_DIR; ?>global.js"></script>
		<script src="<?= JS_DIR; ?>libs/DateAdapter.js"></script>
		<script src="<?= JS_DIR; ?>libs/security/validation/validation.js"></script>
		<script src="<?= JS_DIR; ?>libs/security/validation/template.js"></script>
		<script src="<?= JS_DIR; ?>libs/security/validation/form_validation.js"></script>
		
		<?= $this->section('head'); ?>
	</head>
	<body>
		<?= $this->render('includes/navbar'); ?>

		<?php $this->success(); ?>

		<?= $this->section('body'); ?>
	</body>
</html>