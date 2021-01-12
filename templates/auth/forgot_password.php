<?php $this->startSection('body'); ?>
<form method="POST">
	<h2>Forgotten Password Recovery</h2>

	<input type="hidden" name="<?= $token->getName(); ?>" value="<?= $token->getValue(); ?>">

	<label>
		Email
		<input type="text" name="email" value="<?= $_POST['email'] ?? ''; ?>">
	</label>

	<?php $this->renderError('general'); ?>

	<button type="submit">Send Recovery Mail</button>

	<p>
		Don't have an account?
		<a href="<?php $this->renderRouteUri('registration'); ?>">Register</a>!
	</p>
</form>
<?php $this->endSection(); ?>