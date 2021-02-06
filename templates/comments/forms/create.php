<template id="commentFormCreate">
	<form action="<?= route('comments_create_process'); ?>" method="<?= method('comments_create_process'); ?>">
		<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
		<?php if (isset($id) && is_integer($id) && $id > 0): ?>
			<input type="hidden" name="post_id" value="<?= $id; ?>">
		<?php endif; ?>
		<input type="hidden" name="on_profile" value="1">
		<textarea name="content"></textarea>
		<small class="error-msg"></small>
		<button type="submit">Submit</button>
	</form>
</template>