<form action="<?= $route; ?>" method="post">
	<h3>Create group</h3>
	<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
	<label>
		Name
		<input type="text" name="name" value="<?= $_POST['name'] ?? ''; ?>">
		<small class="error-msg">
			<?php $this->error('name'); ?>
		</small>
	</label>
	<label>
		Description
		<textarea name="description"><?= $_POST['description'] ?? ''; ?></textarea>
		<small class="error-msg">
			<?php $this->error('description'); ?>
		</small>
	</label>
	<button type="submit">
		<?= (isset($submitText)) ? $submitText : 'Save'; ?>
	</button>
</form>