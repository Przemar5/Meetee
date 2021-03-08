<?php $this->startSection('head'); ?>
<script type="module" src="<?= JS_DIR; ?>app/pages/groups/show.js"></script>
<?php $this->endSection(); ?>

<?php $this->startSection('body'); ?>

<div class="group">
	<h2 class="group__header">
		<?= $group->name; ?>
	</h2>

	<p class="group__description">
		<?= $group->description; ?>
	</p>

	<div class="group__panel">
		<?php $user = user(); ?>
		<form action="<?= 
			route('groups_request_process', [
				'groupId' => $group->getId(),
				'userId' => $user->getId(),
				'roleId' => 1,
			]); 
		?>" method="POST" class="group__form group__form--join <?= 
			($user->isInGroup($group)) ? 'display-none' : ''; 
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
		?>" method="POST" class="group__form group__form--unjoin <?= 
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
		?>" method="POST" class="group__form group__form--cancel-request <?= 
			($user->hasRequestedRoleInGroup('FOLLOWER', $group)) ? '' : 'display-none'; 
		?>">
			<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
			<button type="submit" class="group__button">
				Cancel request
			</button>
		</form>
	</div>
</div>

<?php $this->endSection('body'); ?>