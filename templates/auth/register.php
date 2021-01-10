<?php $this->startSection('body'); ?>
<form method="POST">
	<h2>Registration</h2>

	<input type="hidden" name="<?= $token->getName(); ?>" value="<?= $token->getValue(); ?>">

	<label>
		Login
		<input type="text" name="login">
	</label>

	<label>
		Email
		<input type="text" name="email">
	</label>

	<label>
		Name
		<input type="text" name="name">
	</label>

	<label>
		Surname
		<input type="text" name="surname">
	</label>

	<label>
		Birth
		<input type="date" name="birth">
	</label>

	<label>
		Password
		<input type="password" name="password">
	</label>

	<button type="submit">Register</button>
</form>
<?php $this->endSection(); ?>