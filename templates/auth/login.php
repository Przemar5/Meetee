<?php $this->startSection('body'); ?>
<form method="POST">
	<h2>Login</h2>

	<input type="hidden" name="<?= $token->getName(); ?>" value="<?= $token->getValue(); ?>">

	<label>
		Email
		<input type="text" name="email" value="<?= $_POST['email'] ?? ''; ?>">
	</label>

	<label>
		Password
		<input type="password" name="password">
	</label>

	<?php if (isset($errors['general']))
			printf('<small>%s</small>', $errors['general']);
	?>

	<button type="submit">Login</button>
</form>
<?php $this->endSection(); ?>