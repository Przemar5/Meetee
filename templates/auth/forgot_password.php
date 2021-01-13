<?php $this->startSection('body'); ?>
<form method="POST">
	<h2>Forgotten Password Recovery</h2>

	<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">

	<label>
		Email
		<input type="text" name="email" value="<?= $_POST['email'] ?? ''; ?>">
	</label>

	<?php $this->error('general'); ?>

	<button type="submit">Send Recovery Mail</button>

	<p>
		Don't have an account?
		<a href="<?php $this->renderRouteUri('registration_page'); ?>">Register</a>!
	</p>
</form>
<?php $this->endSection(); ?>