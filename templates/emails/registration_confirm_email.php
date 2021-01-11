<?php $this->startSection('body'); ?>
<div>
	<h2>Hello <?= $receiver->getLogin(); ?>!</h2>

	<p>Here is a link for activating Your account.</p>

	<form action="<?= $route; ?>" method="POST">
		<p>Click a link below and You're ready to login!</p>
		<input type="hidden" name="<?= $token->getName(); ?>" value="<?= $token->getValue(); ?>">
		<button type="submit">Confirm registration</button>
	</form>

	<p>This email will be valid only for 2 hours.</p>

	<p>If it wasn't You, just ignore this email.</p>
</div>
<?php $this->endSection(); ?>