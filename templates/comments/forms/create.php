<form action="<?= route('comments_create_process'); ?>" method="<?= method('comments_create_process'); ?>">
	<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
	<?php if (isset($id) && is_integer($id) && $id > 0): ?>
		<input type="hidden" name="post_id" value="<?= $id; ?>">
	<?php endif; ?>
	<textarea name="content"></textarea>
	<button>Submit</button>
</form>