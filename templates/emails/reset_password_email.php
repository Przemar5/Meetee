<?php $this->startSection('body'); ?>
<div>
	<h2>Hello <?= $receiver->getLogin(); ?>!</h2>

	<p>We've heard that You try to recover a password to Your account.</p>

	<form action="<?= $route; ?>" method="POST">
		<p>Click a link below to recover Your password.</p>
		<input type="hidden" name="<?= $token->getName(); ?>" value="<?= $token->getValue(); ?>">
		<button type="submit">Recover</button>
	</form>

	<p>This email will be valid only for 2 hours.</p>

	<p>If it wasn't You, just ignore this email.</p>
</div>
<?php $this->endSection(); ?>