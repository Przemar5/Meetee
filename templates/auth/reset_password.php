<?php $this->startSection('head'); ?>
<script type="module" src="<?= JS_DIR; ?>app/pages/reset_password.js"></script>
<?php $this->endSection(); ?>

<?php $this->startSection('body'); ?>
<form method="POST">
	<h2>Reset password</h2>

	<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">

	<label>
		New Password
		<input type="password" name="password" value="Password1!">
		<?php $this->error('password'); ?>
		<small class="error-msg"></small>
	</label>

	<label>
		Retype new password
		<input type="password" name="repeat_password" value="Password1!">
		<small class="error-msg"></small>
	</label>

	<button type="submit">Confirm</button>
</form>
<?php $this->endSection(); ?>