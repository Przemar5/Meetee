<?php $this->startSection('body'); ?>
<form method="POST">
	<h2>Registration</h2>

	<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">

	<label>
		Login
		<input type="text" name="login" value="<?= $_POST['login'] ?? ''; ?>">
		<?php $this->renderError('login'); ?>
	</label>

	<label>
		Email
		<input type="text" name="email" value="<?= $_POST['email'] ?? ''; ?>">
		<?php $this->renderError('email'); ?>
	</label>

	<label>
		Name
		<input type="text" name="name" value="<?= $_POST['name'] ?? ''; ?>">
		<?php $this->renderError('name'); ?>
	</label>

	<label>
		Surname
		<input type="text" name="surname" value="<?= $_POST['surname'] ?? ''; ?>">
		<?php $this->renderError('surname'); ?>
	</label>

	<label>
		Birth
		<input type="date" name="birth" value="<?= $_POST['birth'] ?? ''; ?>">
		<?php $this->renderError('birth'); ?>
	</label>

	<label>
		Password
		<input type="password" name="password" value="Password1!">
		<?php $this->renderError('password'); ?>
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
</form>
<?php $this->endSection(); ?>