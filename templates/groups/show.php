<?php $this->startSection('head'); ?>
<script type="module" src="<?= JS_DIR; ?>app/pages/comments/in_group.js"></script>
<script type="module" src="<?= JS_DIR; ?>app/pages/groups/show.js"></script>
<?php $this->endSection(); ?>

<?php $this->startSection('body'); ?>

<div class="group">
	<?php $user = user();
		if ($user->hasRoleInGroup('ADMIN', $group) || $user->hasRoleInGroup('CREATOR', $group)): ?>
		<div class="group-actions">

			<form action="<?= 
				route('groups_update_page', [
					'id' => $group->getId(),
				]); 
			?>" method="POST" class="group-actions__form group-actions__form--update">
				<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
				<button type="submit" class="group__button btn--warning">
					Update
				</button>
			</form>

			<form action="<?= 
				route('groups_delete_process', [
					'id' => $group->getId(),
				]); 
			?>" method="POST" class="group-actions__form group-actions__form--delete">
				<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
				<button type="submit" class="group__button btn--danger">
					Delete
				</button>
			</form>

		</div>
	<?php endif; ?>

	<h2 class="group__header">
		<?= $group->name; ?>
	</h2>

	<p class="group__description">
		<?= $group->description; ?>
	</p>

	<div class="group-panel">
		<form action="<?= 
			route('groups_request_process', [
				'groupId' => $group->getId(),
				'userId' => $user->getId(),
				'roleId' => 1,
			]); 
		?>" method="POST" class="group-panel__form group-panel__form--join <?= 
			($user->isInGroup($group) || $user->hasRequestedRoleInGroup('FOLLOWER', $group)) ? 'display-none' : ''; 
		?>">
			<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
			<button type="submit" class="group__button">
				Join
			</button>
		</form>

		<form action="<?= 
			route('groups_discard_process', [
				'groupId' => $group->getId(),
				'userId' => $user->getId(),
				'roleId' => 1,
			]); 
		?>" method="POST" class="group-panel__form group-panel__form--unjoin <?= 
			($user->isInGroup($group)) ? '' : 'display-none'; 
		?>">
			<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
			<button type="submit" class="group__button">
				Unjoin
			</button>
		</form>

		<form action="<?= 
			route('groups_discard_process', [
				'groupId' => $group->getId(),
				'userId' => $user->getId(),
				'roleId' => 1,
			]); 
		?>" method="POST" class="group-panel__form group-panel__form--cancel-request <?= 
			($user->hasRequestedRoleInGroup('FOLLOWER', $group)) ? '' : 'display-none'; 
		?>">
			<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
			<button type="submit" class="group__button">
				Cancel request
			</button>
		</form>
	</div>
</div>

<?php $this->include('comments/template', [
	'token' => $token,
]); ?>

<section class="container" id="commentSection">
	<?php $this->include('comments/forms/save', [
		'token' => $token,
		'groupId' => $group->getId(),
	]); ?>

	<div id="commentsBase">
		<p class="no-result-msg">No posts found.</p>
	</div>

	<button id="loadComments">Load more comments</button>
</section>

<?php $this->endSection('body'); ?>