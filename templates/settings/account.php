<?php $this->startSection('head'); ?>
<script type="module" src="<?= JS_DIR; ?>app/pages/settings/account.js"></script>
<?php $this->endSection(); ?>

<?php $this->startSection('body'); ?>

Account

<label id="profilePhoto" class="updatable-photo-container">
	<img src="<?= IMG_DIR . $user->profile; ?>" width="400" height="300" alt="photo">
	<form method="POST" enctype="multipart/form-data">
		<input type="file" name="profile">
		<button type="submit">Submit</button>
		<small class="error-msg"></small>
	</form>
</label>

<label id="name" class="toggable-container">
	Name: 
	<form method="POST" class="toggable-form attribute-form display-none">
		<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
		<input type="text" class="form-input" name="name" value="<?= $user->name; ?>" required>
		<small class="error-msg"></small>
		<button type="submit">Submit</button>
	</form>
	<span class="toggable-text">
		<span class="attribute">
			<?= $user->name ?? ''; ?>
		</span>
		<button class="form-toggler">Change</button>
	</span>
</label>

<label id="second_name" class="toggable-container">
	Second name: 
	<form method="POST" class="toggable-form attribute-form display-none">
		<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
		<input type="text" class="form-input" name="second_name" value="<?= $user->secondName; ?>">
		<small class="error-msg"></small>
		<button type="submit">Submit</button>
	</form>
	<span class="toggable-text">
		<span class="attribute">
			<?= $user->secondName ?? ''; ?>
		</span>
		<button class="form-toggler">Change</button>
	</span>
</label>

<label id="surname" class="toggable-container">
	Surname: 
	<form method="POST" class="toggable-form attribute-form display-none">
		<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
		<input type="text" class="form-input" name="surname" value="<?= $user->surname; ?>" required>
		<small class="error-msg"></small>
		<button type="submit">Submit</button>
	</form>
	<span class="toggable-text">
		<span class="attribute">
			<?= $user->surname ?? ''; ?>
		</span>
		<button class="form-toggler">Change</button>
	</span>
</label>

<label id="login" class="toggable-container">
	Login: 
	<span class="toggable-text">
		<?= $user->login ?? ''; ?>
		<button class="form-toggler">Change</button>
	</span>
</label>

<label id="email" class="toggable-container">
	Email: 
	<span class="toggable-text">
		<?= $user->email ?? ''; ?>
	</span>
	<button class="form-toggler">Change</button>
</label>

<label id="birth" class="toggable-container">
	Birth: 
	<form method="POST" class="toggable-form attribute-form display-none">
		<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
		<input type="date" class="form-input" name="birth" value="<?= $user->getBirth()->format('Y-m-d'); ?>" required>
		<small class="error-msg"></small>
		<button type="submit">Submit</button>
	</form>
	<span class="toggable-text">
		<span class="attribute">
			<?= $user->getBirth()->format('Y-m-d') ?? ''; ?>
		</span>
		<button class="form-toggler">Change</button>
	</span>
</label>

<label id="country" class="toggable-container">
	Country: 
	<form method="POST" class="toggable-form attribute-form display-none">
		<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
		<select name="country">
			<?php foreach ($countries as $country): ?>
				<option value="<?= $country['id']; ?>"<?= ($country['id'] == $user->country->getId()) ? ' selected' : ''; ?>>
					<?= $country['name']; ?>
				</option>
			<?php endforeach; ?>
		</select>
		<small class="error-msg"></small>
		<button type="submit">Submit</button>
	</form>
	<span class="toggable-text">
		<span class="attribute">
			<?= (isset($user->country)) ? $user->country->name : ''; ?>
		</span>
		<button class="form-toggler">Change</button>
	</span>
</label>

<label id="city" class="toggable-container">
	City: 
	<form method="POST" class="toggable-form attribute-form display-none">
		<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
		<input type="text" class="form-input" name="city" value="<?= $user->city; ?>">
		<small class="error-msg"></small>
		<button type="submit">Submit</button>
	</form>
	<span class="toggable-text">
		<span class="attribute">
			<?= $user->city ?? ''; ?>
		</span>
		<button class="form-toggler">Change</button>
	</span>
</label>

<label id="zip" class="toggable-container">
	Zip code: 
	<form method="POST" class="toggable-form attribute-form display-none">
		<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
		<input type="text" class="form-input" name="zip" value="<?= $user->zipCode; ?>">
		<small class="error-msg"></small>
		<button type="submit">Submit</button>
	</form>
	<span class="toggable-text">
		<span class="attribute">
			<?= $user->zipCode ?? ''; ?>
		</span>
		<button class="form-toggler">Change</button>
	</span>
</label>

<label>
	Gender
	<form method="POST" class="attribute-form">
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
		<button type="submit">Submit</button>
		<small class="error-msg">
			<?php $this->error('gender'); ?>
		</small>
	</form>
</label>

<a href="<?php $this->renderRouteUri('settings_index_page'); ?>">Return</a>

<?php $this->endSection('body'); ?>