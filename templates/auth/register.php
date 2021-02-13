<?php $this->startSection('head'); ?>
<script type="module" src="<?= JS_DIR; ?>app/pages/register.js"></script>
<?php $this->endSection(); ?>

<?php $this->startSection('body'); ?>
<form method="POST" enctype="multipart/form-data">
	<h2>Registration</h2>

	<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">

	<label id="profileImageContainer">
		Profile image
		<img id="profileImage" accept="image/*" src="<?= IMG_DIR . $this->getDefaultProfileImageFilename(); ?>" width="400" height="300" alt="photo">
		<input type="file" name="profile">
		<small class="error-msg"></small>
	</label>

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
		Second name
		<input type="text" name="second_name" value="<?= $_POST['second_name'] ?? ''; ?>">
		<small class="error-msg">
			<?php $this->error('second_name'); ?>
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
		Gender
		<select name="gender">
			<option value=""<?php 
				if (!isset($_POST['gender']))
					echo ' selected';
				?>>Don't want to say</option>
			<option value="male"<?php 
				if (isset($_POST['gender']) && $_POST['gender'] === 'male')
					echo ' selected'; 
				?>>Male</option>
			<option value="female"<?php 
				if (isset($_POST['gender']) && $_POST['gender'] === 'female')
					echo ' selected'; 
				?>>Female</option>
			<option value="other"<?php 
				if (isset($_POST['gender']) && $_POST['gender'] === 'other')
					echo ' selected'; 
				?>>Other</option>
		</select>
		<small class="error-msg">
			<?php $this->error('gender'); ?>
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
		Country
		<select name="country">
			<?php foreach ($countries as $country): ?>
				<option value="<?= $country['id']; ?>"<?= 
					(isset($_POST['country']) && $_POST['country'] == $country['id']) ? ' selected' : ''; 
				?>>
					<?= $country['name']; ?>
				</option>
			<?php endforeach; ?>
		</select>
		<small class="error-msg">
			<?php $this->error('country'); ?>
		</small>
	</label>

	<label>
		City
		<input type="text" name="city" value="<?= $_POST['city'] ?? ''; ?>">
		<small class="error-msg">
			<?php $this->error('city'); ?>
		</small>
	</label>

	<label>
		Zip code
		<input type="text" name="zip" value="<?= $_POST['zip'] ?? ''; ?>">
		<small class="error-msg">
			<?php $this->error('zip'); ?>
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