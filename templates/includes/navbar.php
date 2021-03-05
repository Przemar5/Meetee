<?php 
	$generalToken = token('csrf_token');
	printf('<input type="hidden" name="%s" value="%s" id="%s">',
		$generalToken->name, $generalToken->value, 'generalCsrfToken'); 
?>

<nav>
	<?php if ($this->isGranted('VERIFIED')): ?>
		<a href="<?= route('login_page'); ?>"></a>
		<div>
			<a href="<?= route('main_page'); ?>">Main</a>
			<a href="<?= route('profiles_show_page', ['id' => user()->getId()]); ?>">Profile</a>
			<a href="<?= route('settings_index_page'); ?>">Settings</a>
			<a href="<?= route('logout'); ?>">Logout</a>
		</div>
		<div>
			<a href="<?= route('groups_index_page'); ?>">Groups</a>
			<button type="button" class="navbar__button" 
					id="navbarRequestsBtn" 
					data-href="<?= route('relations_queue_page'); ?>">
				Requests
			</button>
			<div class="navbar__relation-requests display-none">
				<?php 
					$relations = getRelationRequests(user()); 
					if ($relations): 
						foreach ($relations as $request): 
				?>
					<div class="navbar__relation-request">
						<a href="<?= route('profiles_show_page', [
							'id' => $request['sender_id']
						]); ?>">
							<?= $request['login']; ?>	
						</a>
						wants to be your friend!
						<form action="<?= route('relations_accept_process', [
									'userId' => $request['sender_id'],
									'relationId' => $request['relation_id'],
								]); ?>" 
								method="POST" 
								class="form-relation--accept">
							<input type="hidden" 
									name="<?= $generalToken->name; ?>" 
									value="<?= $generalToken->value; ?>">
							<button type="submit">Accept</button>
						</form>
						<form action="<?= route('relations_discard_process', [
									'userId' => $request['sender_id'],
									'relationId' => $request['relation_id'],
								]); ?>" 
								method="POST" 
								class="form-relation--discard">
							<input type="hidden" 
									name="<?= $generalToken->name; ?>" 
									value="<?= $generalToken->value; ?>">
							<button type="submit">Discard</button>
						</form>
					</div>
				<?php 
						endforeach; 
					else:
				?>
					<p>No results found.</p>
				<?php endif; ?>
			</div>
		</div>
	<?php else: ?>
		<a href="<?= route('registration_page'); ?>">Register</a>
		<a href="<?= route('login_page'); ?>">Login</a>
	<?php endif; ?>
</nav>