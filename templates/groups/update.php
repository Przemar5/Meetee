<?php $this->startSection('head'); ?>
<script type="module" src="<?= JS_DIR; ?>app/pages/groups/update.js"></script>
<?php $this->endSection(); ?>

<?php $this->startSection('body'); ?>

<?php $this->include('groups/forms/save', [
	'header' => 'Update group',
	'route' => route('groups_update_page', ['id' => $group->getId()]),
	'token' => $token,
]); ?>

<?php $this->endSection('body'); ?>