<?php $this->startSection('body'); ?>
<form method="POST">
	<h2>Delete account</h2>

	<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">

	<p>Are You sure You want to delete Your account? This action is inreversible.</p>

	<button type="submit">Delete</button>

	<p>
		<a href="<?php $this->renderRouteUri('main'); ?>">Go to main page</a>!
	</p>
</form>
<?php $this->endSection(); ?>