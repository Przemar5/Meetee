<template id="commentFormCreate">
	<form action="<?= 
		(isset($route)) ? $route : route('comments_create_process'); 
		?>" method="POST">
		<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
		<input type="hidden" name="parent_id" value="null">
		<input type="hidden" name="topic_id" value="<?= 
			(isset($topicId)) ? $topicId : 'null'; ?>">
		<input type="hidden" name="group_id" value="<?= 
			(isset($groupId)) ? $groupId : 'null'; ?>">
		<input type="hidden" name="on_profile" value="<?= (isset($onProfile)) ? $onProfile : 0; ?>">
		<textarea name="content"></textarea>
		<small class="error-msg"></small>
		<button type="submit">Submit</button>
	</form>
</template>