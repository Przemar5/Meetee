<?php $this->startSection('body'); ?>
<form method="POST">
	<h2>Login</h2>

	<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">

	<label>
		Email
		<input type="text" name="email" value="<?= $_POST['email'] ?? ''; ?>">
	</label>

	<label>
		Password
		<input type="password" name="password">
	</label>

	<?php $this->error('general'); ?>

	<button type="submit">Login</button>

	<p>
		Don't have an account?
		<a href="<?php $this->renderRouteUri('registration_page'); ?>">Register</a>!
	</p>

	<p>
		Forgot Your password?
		<a href="<?php $this->renderRouteUri('forgot_password_page'); ?>">Click here</a>!
	</p>
</form>
<?php $this->endSection(); ?>