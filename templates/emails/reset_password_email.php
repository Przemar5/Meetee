<?php $this->startSection('body'); ?>
<div>
	<h2>Hello <?= $receiver->login; ?>!</h2>

	<p>We've heard that You try to recover a password to Your account.</p>

	<p>Click a link below to recover Your password.</p>
	
	<p>By doing so You will be redirected to our page.</p>

	<form action="<?= $route; ?>" method="POST">
		<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
		<button type="submit">Recover</button>
	</form>

	<p>This email will be valid only for 2 hours.</p>

	<p>If it wasn't You, just ignore this email.</p>
</div>
<?php $this->endSection(); ?>