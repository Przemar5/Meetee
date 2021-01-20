<form action="<?= route('posts_create_process'); ?>" method="<?= method('posts_create_process'); ?>">
	<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
	<textarea name="content"></textarea>
	<button>Submit</button>
</form>