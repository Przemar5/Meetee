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

<?php $this->include('posts/template', ['token' => $token]); ?>

<div class="container">
	<?php $this->include('posts/forms/create', ['token' => $token]); ?>

	<div id="posts">
		<p class="no-result-msg">No posts found.</p>
	</div>
</div>

<?php $this->endSection('body'); ?>