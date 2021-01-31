<div class="ratings">
	<div class="ratings__count"></div>

	<form post="POST">
		<input type="hidden" name="rating_id" value="1">
		<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
		<button type="submit" title="Like">👍</button>
	</form>
	<form post="POST">
		<input type="hidden" name="rating_id" value="2">
		<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
		<button type="submit" title="Dislike">👎</button>
	</form>
	<form post="POST">
		<input type="hidden" name="rating_id" value="3">
		<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
		<button type="submit" title="Funny">😂</button>
	</form>
	<form post="POST">
		<input type="hidden" name="rating_id" value="4">
		<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
		<button type="submit" title="Smile">😊</button>
	</form>
	<form post="POST">
		<input type="hidden" name="rating_id" value="5">
		<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
		<button type="submit" title="Crazy">😜</button>
	</form>
	<form post="POST">
		<input type="hidden" name="rating_id" value="6">
		<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
		<button type="submit" title="Sad">😟</button>
	</form>
	<form post="POST">
		<input type="hidden" name="rating_id" value="7">
		<input type="hidden" name="<?= $token->name; ?>" value="<?= $token->value; ?>">
		<button type="submit" title="Angry">😡</button>
	</form>
</div>