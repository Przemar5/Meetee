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

	<?php $this->renderError('general'); ?>

	<button type="submit">Login</button>

	<p>
		Don't have an account?
		<a href="<?php $this->renderRouteUri('registration'); ?>">Register</a>!
	</p>

	<p>
		Forgot Your password?
		<a href="<?php $this->renderRouteUri('forgot_password'); ?>">Click here</a>!
	</p>
</form>
<?php $this->endSection(); ?>