<?php $this->startSection('head'); ?>
<script type="module" src="<?= JS_DIR; ?>app/pages/comments/on_profile.js"></script>
<script type="module" src="<?= JS_DIR; ?>app/pages/profiles/show.js"></script>
<?php $this->endSection(); ?>

<?php $this->startSection('body'); ?>

Account

<?php if ($this->isGranted('VERIFIED') && user() && user()->getId() != $user->getId()): ?>
	<form action="<?= route('relations_request_process', ['userId' => $user->getId(), 'relationId' => idForRelation('FRIEND')]); ?>" method="POST" class="friend-process-form friend-process-form--request <?= (areInRelation(user(), $user, idForRelation('FRIEND')) || 
	relationRequestSend(user(), $user, idForRelation('FRIEND'))) ? 'display-none' : ''; ?>">
		<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
		<button type="submit">Add friend</button>
	</form>

	<form action="<?= route('relations_request_cancel_process', ['userId' => $user->getId(), 'relationId' => idForRelation('FRIEND')]); ?>" method="POST" class="friend-process-form friend-process-form--cancel-request <?= (!areInRelation(user(), $user, idForRelation('FRIEND')) && 
	relationRequestSend(user(), $user, idForRelation('FRIEND'))) ? '' : 'display-none'; ?>">
		<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
		<button type="submit">Cancel request</button>
	</form>
	
	<form action="<?= route('relations_discard_process', ['userId' => $user->getId(), 'relationId' => idForRelation('FRIEND')]); ?>" method="POST" class="friend-process-form friend-process-form--discard <?= (areInRelation(user(), $user, idForRelation('FRIEND'))) ? '' : 'display-none'; ?>">
		<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
		<button type="submit">Unfriend</button>
	</form>
<?php endif; ?>

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
	<?php $this->include('comments/forms/save', [
		'token' => $token,
		'onProfile' => 1,
	]); ?>

	<div id="commentsBase">
		<p class="no-result-msg">No posts found.</p>
	</div>

	<button id="loadComments">Load more comments</button>
</section>

<?php $this->endSection('body'); ?>