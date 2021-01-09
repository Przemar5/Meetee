<?php $this->startSection('body'); ?>
<form method="POST">
	<h2>Registration</h2>
	<label>
		Username
		<input type="text" name="username">
	</label>
	<label>
		Password
		<input type="password" name="password">
	</label>
	<button type="submit">Register</button>
</form>
<?php $this->endSection(); ?>