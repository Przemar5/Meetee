<?php $this->startSection('head'); ?>
<script type="module" src="<?= JS_DIR; ?>app/pages/groups/show.js"></script>
<?php $this->endSection(); ?>

<?php $this->startSection('body'); ?>

<?php $this->include('groups/forms/create', [
	'route' => route('groups_create_page'),
	'token' => $token,
]); ?>

<?php $this->endSection('body'); ?>