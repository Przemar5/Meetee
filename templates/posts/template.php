<template id="postTemplate">
	<div class="post">
		<span class="post__modification">
			<span class="post__modification--type"></span> 
			<time datetime="" class="post__modification--date"></time>
		</span>
		<button class="post__button--edit form-toggler">Edit</button>
		<form class="post__form post__form--delete">
			<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
			<button type="submit" class="post__button--delete">Delete</button>
		</form>
		<form method="POST" class="post__form post__form--edit toggable-form display-none">
			<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
			<textarea name="content" class="post__content"></textarea>
			<small class="error-msg"></small>
			<button type="submit" class="post__button--save">Save</button>
		</form>
		<span class="post__content--view toggable-text"></span>
		<?php $this->include('ratings/form', ['token' => $token]); ?>
	</div>
</template>