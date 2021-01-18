<?php $this->startSection('head'); ?>
<script type="module" src="<?= JS_DIR; ?>app/pages/register.js"></script>
<?php $this->endSection(); ?>

<?php $this->startSection('body'); ?>
<form method="POST">
	<h2>Registration</h2>

	<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">

	<label>
		Login
		<input type="text" name="login" value="<?= $_POST['login'] ?? ''; ?>">
		<small class="error-msg">
			<?php $this->error('login'); ?>
		</small>
	</label>

	<label>
		Email
		<input type="text" name="email" value="<?= $_POST['email'] ?? ''; ?>">
		<small class="error-msg">
			<?php $this->error('email'); ?>
		</small>
	</label>

	<label>
		Name
		<input type="text" name="name" value="<?= $_POST['name'] ?? ''; ?>">
		<small class="error-msg">
			<?php $this->error('name'); ?>
		</small>
	</label>

	<label>
		Surname
		<input type="text" name="surname" value="<?= $_POST['surname'] ?? ''; ?>">
		<small class="error-msg">
			<?php $this->error('surname'); ?>
		</small>
	</label>

	<label>
		Birth
		<input type="date" name="birth" value="<?= $_POST['birth'] ?? ''; ?>">
		<small class="error-msg">
			<?php $this->error('birth'); ?>
		</small>
	</label>

	<label>
		Password
		<input type="password" name="password" value="Password1!">
		<small class="error-msg">
			<?php $this->error('password'); ?>
		</small>
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