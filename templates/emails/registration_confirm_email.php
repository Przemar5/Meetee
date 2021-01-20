<?php $this->startSection('body'); ?>
<div>
	<h2>Hello <?= $receiver->login; ?>!</h2>

	<p>Here is a link for activating Your account.</p>

	<p>By clicking it You will be redirected to our page.</p>

	<form action="<?= $route; ?>" method="POST">
		<p>Click a link below and You're ready to login!</p>
		<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
		<button type="submit">Confirm registration</button>
	</form>

	<p>This email will be valid only for 2 hours.</p>

	<p>If it wasn't You, just ignore this email.</p>
</div>
<?php $this->endSection(); ?>