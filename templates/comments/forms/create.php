<template id="commentFormCreate">
	<form action="<?= route('comments_create_process'); ?>" method="<?= method('comments_create_process'); ?>">
		<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
		<input type="hidden" name="parent_id" value="null">
		<input type="hidden" name="topic_id" value="null">
		<input type="hidden" name="group_id" value="null">
		<input type="hidden" name="on_profile" value="1">
		<textarea name="content"></textarea>
		<small class="error-msg"></small>
		<button type="submit">Submit</button>
	</form>
</template>