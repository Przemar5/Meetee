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

		<div class="comment__subcomments"></div>
		<button class="comment__button--show-subs">Show subcomments</button>

		<div class="comment__commenting">
			<button type="button" class="comment__button--comment">Comment</button>
			<form action="<?= route('comments_create_process'); ?>" method="<?= method('comments_create_process'); ?>" class="comment__form comment__form--comment display-none">
				<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
				<input type="hidden" name="parent_id" value="null">
				<input type="hidden" name="topic_id" value="null">
				<input type="hidden" name="group_id" value="null">
				<input type="hidden" name="on_profile" value="1">
				<textarea name="content"></textarea>
				<small class="error-msg"></small>
				<button type="submit">Submit</button>
			</form>
		</div>
	</div>
</template>