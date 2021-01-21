<?php $this->startSection('head'); ?>
<script type="module" src="<?= JS_DIR; ?>app/pages/posts/show.js"></script>
<?php $this->endSection(); ?>

<?php $this->startSection('body'); ?>

Account

<label id="name" class="toggable-container">
	Name: 
	<span class="attribute">
		<?= $user->name ?? ''; ?>
	</span>
</label>

<label id="second_name" class="toggable-container">
	Second name: 
	<span class="attribute">
		<?= $user->secondName ?? ''; ?>
	</span>
</label>

<label id="surname" class="toggable-container">
	Surname: 
	<span class="attribute">
		<?= $user->surname ?? ''; ?>
	</span>
</label>

<label id="login" class="toggable-container">
	Login: 
	<span class="toggable-text">
		<?= $user->login ?? ''; ?>
	</span>
</label>

<label id="email" class="toggable-container">
	Email: 
	<span class="toggable-text">
		<?= $user->email ?? ''; ?>
	</span>
</label>

<label id="birth" class="toggable-container">
	Birth: 
	<span class="attribute">
		<?= $user->getBirth()->format('Y-m-d') ?? ''; ?>
	</span>
</label>

<label id="country" class="toggable-container">
	Country: 
	<span class="attribute">
		<?= (isset($user->country)) ? $user->country->name : ''; ?>
	</span>
</label>

<label id="city" class="toggable-container">
	City: 
	<span class="attribute">
		<?= $user->city ?? ''; ?>
	</span>
</label>

<label id="zip" class="toggable-container">
	Zip code: 
	<span class="attribute">
		<?= $user->zipCode ?? ''; ?>
	</span>
</label>

<template id="postTemplate">
	<div class="post">
		<span class="post__modification">
			<span class="post__modification--type"></span> 
			<time datetime="" class="post__modification--date"></time>
		</span>
		<button class="post__button--edit form-toggler">Edit</button>
		<button class="post__button--delete">Delete</button>
		<form method="POST" class="post__form toggable-form display-none">
			<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
			<textarea name="content" class="post__content"></textarea>
			<small class="error-msg"></small>
			<button type="submit" class="post__button--save">Save</button>
		</form>
		<span class="post__content--view toggable-text"></span>
	</div>
</template>

<div class="container">
	<?php $this->include('posts/forms/create', ['token' => $token]); ?>

	<div id="posts"></div>
</div>

<?php $this->endSection('body'); ?>