<?php $this->startSection('body'); ?>
<form method="POST">
	<h2>Registration</h2>

	<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">

	<label>
		Login
		<input type="text" name="login" value="<?= $_POST['login'] ?? ''; ?>">
		<?php $this->error('login'); ?>
	</label>

	<label>
		Email
		<input type="text" name="email" value="<?= $_POST['email'] ?? ''; ?>">
		<?php $this->error('email'); ?>
	</label>

	<label>
		Name
		<input type="text" name="name" value="<?= $_POST['name'] ?? ''; ?>">
		<?php $this->error('name'); ?>
	</label>

	<label>
		Surname
		<input type="text" name="surname" value="<?= $_POST['surname'] ?? ''; ?>">
		<?php $this->error('surname'); ?>
	</label>

	<label>
		Birth
		<input type="date" name="birth" value="<?= $_POST['birth'] ?? ''; ?>">
		<?php $this->error('birth'); ?>
	</label>

	<label>
		Password
		<input type="password" name="password" value="Password1!">
		<?php $this->error('password'); ?>
	</label>

	<label>
		Retype password
		<input type="password" name="repeat_password" value="Password1!">
	</label>

	<button type="submit">Register</button>

	<p>
		Already have an account? 
		<a href="<?php $this->renderRouteUri('login_page'); ?>">Login</a>!
	</p>

	<p>
		Didn't get activation email?
		<a href="<?php $this->renderRouteUri('registration_resend_page'); ?>">Resend</a>!
	</p>
</form>
<?php $this->endSection(); ?>