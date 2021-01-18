<?php $this->startSection('head'); ?>
<script type="module" src="<?= JS_DIR; ?>app/pages/settings/account.js"></script>
<?php $this->endSection(); ?>

<?php $this->startSection('body'); ?>

Account

<template id="csrfToken">
	<input type="hidden" id="token" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
</template>

<label id="name" class="toggable-form">
	Name: 
	<span class="toggable-form-container">
		<?= $user->name ?? ''; ?>
	</span>
	<button class="form-toggler">Change</button>
</label>

<label id="second_name" class="toggable-form">
	Second name: 
	<span class="toggable-form-container">
		<?= $user->secondName ?? ''; ?>
	</span>
	<button class="form-toggler">Change</button>
</label>

<label id="surname" class="toggable-form">
	Surname: 
	<span class="toggable-form-container">
		<?= $user->surname ?? ''; ?>
	</span>
	<button class="form-toggler">Change</button>
</label>

<label id="login" class="toggable-form">
	Login: 
	<span class="toggable-form-container">
		<?= $user->login ?? ''; ?>
	</span>
	<button class="form-toggler">Change</button>
</label>

<label id="email" class="toggable-form">
	Email: 
	<span class="toggable-form-container">
		<?= $user->email ?? ''; ?>
	</span>
	<button class="form-toggler">Change</button>
</label>

<label id="birth" class="toggable-form">
	Birth: 
	<span class="toggable-form-container">
		<?= $user->getBirth()->format('Y-m-d') ?? ''; ?>
	</span>
	<button class="form-toggler">Change</button>
</label>

<label id="country" class="toggable-form">
	Country: 
	<span class="toggable-form-container">
		<?= (isset($user->country)) ? $user->country->name : ''; ?>
	</span>
	<button class="form-toggler">Change</button>
</label>

<label id="city" class="toggable-form">
	City: 
	<span class="toggable-form-container">
		<?= $user->city ?? ''; ?>
	</span>
	<button class="form-toggler">Change</button>
</label>

<label id="zip" class="toggable-form">
	Zip code: 
	<span class="toggable-form-container">
		<?= $user->zipCode ?? ''; ?>
	</span>
	<button class="form-toggler">Change</button>
</label>

<a href="<?php $this->renderRouteUri('settings_index_page'); ?>">Return</a>

<?php $this->endSection('body'); ?>