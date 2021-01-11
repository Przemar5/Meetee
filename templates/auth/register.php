<?php $this->startSection('body'); ?>
<form method="POST">
	<h2>Registration</h2>

	<input type="hidden" name="<?= $token->getName(); ?>" value="<?= $token->getValue(); ?>">

	<label>
		Login
		<input type="text" name="login" value="<?= $_POST['login'] ?? ''; ?>">
		<?php if (isset($errors['login']))
				echo sprintf('<small>%s</small>', $errors['login']); 
		?>
	</label>

	<label>
		Email
		<input type="text" name="email" value="<?= $_POST['email'] ?? ''; ?>">
		<?php if (isset($errors['email']))
				echo sprintf('<small>%s</small>', $errors['email']); 
		?>
	</label>

	<label>
		Name
		<input type="text" name="name" value="<?= $_POST['name'] ?? ''; ?>">
		<?php if (isset($errors['name']))
				echo sprintf('<small>%s</small>', $errors['name']); 
		?>
	</label>

	<label>
		Surname
		<input type="text" name="surname" value="<?= $_POST['surname'] ?? ''; ?>">
		<?php if (isset($errors['surname']))
				echo sprintf('<small>%s</small>', $errors['surname']); 
		?>
	</label>

	<label>
		Birth
		<input type="date" name="birth" value="<?= $_POST['birth'] ?? ''; ?>">
		<?php if (isset($errors['birth']))
				echo sprintf('<small>%s</small>', $errors['birth']); 
		?>
	</label>

	<label>
		Password
		<input type="password" name="password">
		<?php if (isset($errors['password']))
				echo sprintf('<small>%s</small>', $errors['password']); 
		?>
	</label>

	<label>
		Retype password
		<input type="password" name="repeat_password">
	</label>

	<button type="submit">Register</button>
</form>
<?php $this->endSection(); ?>