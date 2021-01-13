<?php $this->startSection('body'); ?>
<form method="POST">
	<h2>Resend registration email</h2>

	<p>Write an email to resend You registration verification email.</p>

	<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">

	<label>
		Email
		<input type="text" name="email" value="<?= $_POST['email'] ?? ''; ?>">
		<?php $this->renderError('email'); ?>
	</label>

	<?php $this->renderError('general'); ?>

	<button type="submit">Resend</button>

	<p>
		Already have an activated account? 
		<a href="<?php $this->renderRouteUri('login_page'); ?>">Login</a>!
	</p>

	<p>
		Don't have an account? 
		<a href="<?php $this->renderRouteUri('register_page'); ?>">Register</a>!
	</p>
</form>
<?php $this->endSection(); ?>