<?php
// vim: set noet fenc= ff=unix sts=0 sw=4 ts=4 : 
/* SVN FILE: $Id$ */
$disabled[1] = ($dates[1] == NULL) ? 'disabled' : '';
$disabled[2] = ($dates[2] == NULL) ? 'disabled' : '';
//$disabled['id3'] = (count($labels) > 0 && count($mp3s) > 0) ? '' : 'disabled';
$disabled['id3'] = 'disabled';
$disabled['cue'] = (count($labels) > 0 && $dates[0] != NULL) ? '' : 'disabled';
?>
	<button id='prev' <?= $disabled[1] ?> onclick="load_date('<?= $dates[1] ?>')">&lt;&lt; Prev</button>
	<button id='next' <?= $disabled[2] ?> onclick="load_date('<?= $dates[2] ?>')">Next &gt;&gt;</button>
	<h2><?= $dates[0] ?></h2>

	<h3>Files (click to download):</h3>
	<?php foreach($mp3s as $mp3) { ?>
		<a href='<?= url::base() ?>recordings/<?= array_pop(split('/', $mp3)) ?>' class='filename'><?= array_pop(split('/', $mp3)) ?></a>
		<br/>
	<?php } ?>

	<?php foreach($originals as $file) { ?>
		<a href="<?= url::base() ?>Originals/<?= array_pop(split('/', $file)) ?>" class="filename"><?= array_pop(split('/', $file)) ?></a>
		<br/>
	<?php } ?>

	<?php foreach($labels as $file) { ?>
		<a href="<?= url::base() ?>Originals/<?= array_pop(split('/', $file)) ?>" class="filename"><?= array_pop(split('/', $file)) ?></a>
		<br/>
	<?php } ?>

	<?php if(count($mp3s) > 0) { ?>
		<a href="<?= url::base() ?>data/gencue/<?= $dates[0] ?>" class="filename"><?= $dates[0] ?>.cue</a>
		<br/>
	<?php } ?>

	<?= $labelInfo ?>

	<?php
	if (count($mp3s) === 0) { ?>
		<p id="nofiles">No files were found for this date.</p>
	<?php } ?>

	<!--
	<p>
		<button <?= $disabled['id3'] ?> >Write ID3 tags</button>
		<button <?= $disabled['cue'] ?> onclick="window.location = '<?= url::base() ?>data/gencue/<?= $dates[0] ?>'">Download Cue File</button>
	</p>
	-->

		
<script type='text/javascript'>
function load_date(date) {
	$('#downloads').load('<?= url::base() ?>dash/downloads/'+date);
}
</script>
