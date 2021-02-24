<?php $this->startSection('head'); ?>
<script type="module" src="<?= JS_DIR; ?>app/pages/groups/index.js"></script>
<?php $this->endSection(); ?>

<?php $this->startSection('body'); ?>

<a href="<?= route('groups_create_page'); ?>">
	Create group
</a>

<?php foreach ($groups as $group): ?>
	<div class="group">
		<div class="group__content">
			<h3 class="group__name">
				<a href="<?= route('groups_show_page', ['id' => $group->getId()]); ?>">
					<?= $group->name; ?>
				</a>
			</h3>
			<p class="group__description">
				<?= $group->description; ?>
			</p>
		</div>
	</div>
<?php endforeach; ?>

<?php $this->endSection('body'); ?>