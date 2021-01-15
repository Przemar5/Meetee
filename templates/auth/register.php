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

		Retype password
		<input type="password" name="repeat_password" value="Password1!">
	</label>

	<button type="submit">Register</button>

	<script>
		const passField = document
			.querySelector('input[name="password"]');
		const repeatPassField = document
			.querySelector('input[name="repeat_password"]');

		passField.addEventListener('keyup', (evt) => {
			let errorDiv = evt.target.closest('label')
					.querySelector('.error-msg')
			errorDiv.innerText = ''

			try {
				if (userPasswordValidator(evt.target.value)) {
					repeatPassField.onkeyup = (evt2) => {
						console.log(passField.value.trim() === repeatPassField.value.trim())

						if (passField.value.trim() !== repeatPassField.value.trim()) {
							console.log('ok')
							throw new Error("Both passwords must be identical.")
						}
					}
				}
			} catch (err) {
				errorDiv.innerText = err.message
				console.log(err.message)
				repeatPassField.onkeyup = () => null
				repeatPassField.onkeydown = () => null
			}
		})

		const namesAndValidators = [
			['name', userNameValidator],
			['surname', userSurnameValidator],
			['birth', userBirthValidator],
			// ['password', userPasswordValidator],
			// ['repeat_password', userPasswordValidator]
			// [['password', userPasswordValidator], ['repeat_password', nullValidator],
			// 	([[name1, val1], [name2, val2]]) => {
			// 		let first = document
			// 			.querySelector('input[name="' + name1 + '"]')
			// 		let second = document
			// 			.querySelector('input[name="' + name2 + '"]')

			// 		if (val1(first.value)) {}
			// 	}
			// ],
		]
		namesAndValidators.forEach(fieldHandlerDispatcher)
	</script>

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