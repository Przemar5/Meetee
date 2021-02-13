<?php $this->startSection('head'); ?>
<script type="module" src="<?= JS_DIR; ?>app/pages/comments/show.js"></script>
<?php $this->endSection(); ?>

<?php $this->startSection('body'); ?>

Account


<img src="<?= IMG_DIR . $user->profile; ?>" width="400" height="300" alt="photo">

<label>
	Name: 
	<span class="attribute">
		<?= $user->name ?? ''; ?>
	</span>
</label>

<label>
	Second name: 
	<span class="attribute">
		<?= $user->secondName ?? ''; ?>
	</span>
</label>

<label>
	Surname: 
	<span class="attribute">
		<?= $user->surname ?? ''; ?>
	</span>
</label>

<label>
	Login: 
	<span class="toggable-text">
		<?= $user->login ?? ''; ?>
	</span>
</label>

<label>
	Email: 
	<span class="toggable-text">
		<?= $user->email ?? ''; ?>
	</span>
</label>

<label>
	Birth: 
	<span class="attribute">
		<?= $user->getBirth()->format('Y-m-d') ?? ''; ?>
	</span>
</label>

<label>
	Country: 
	<span class="attribute">
		<?= (isset($user->country)) ? $user->country->name : ''; ?>
	</span>
</label>

<label>
	City: 
	<span class="attribute">
		<?= $user->city ?? ''; ?>
	</span>
</label>

<label>
	Zip code: 
	<span class="attribute">
		<?= $user->zipCode ?? ''; ?>
	</span>
</label>

<?php $this->include('comments/template', [
	'token' => $token,
]); ?>

<section class="container" id="commentSection">
	<?php $this->include('comments/forms/create', [
		'token' => $token,
	]); ?>

	<div id="commentsBase">
		<p class="no-result-msg">No posts found.</p>
	</div>
</section>

<?php $this->endSection('body'); ?>