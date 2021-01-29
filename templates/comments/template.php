<template id="commentTemplate">
	<div class="comment">
		<span class="comment__modification">
			<span class="comment__modification--type"></span> 
			<time datetime="" class="comment__modification--date"></time>
		</span>
		<button class="comment__button--edit form-toggler">Edit</button>
		<form class="comment__form comment__form--delete">
			<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
			<button type="submit" class="comment__button--delete">Delete</button>
		</form>
		<form method="POST" class="comment__form comment__form--edit toggable-form display-none">
			<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
			<textarea name="content" class="comment__content"></textarea>
			<small class="error-msg"></small>
			<button type="submit" class="comment__button--save">Save</button>
		</form>
		<span class="comment__content--view toggable-text"></span>
		<?php $this->include('ratings/form', ['token' => $token]); ?>
	</div>
</template>