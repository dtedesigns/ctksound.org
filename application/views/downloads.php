<?php
// vim: set noet fenc= ff=unix sts=0 sw=4 ts=4 : 
/* SVN FILE: $Id$ */
$disabled[1] = ($dates[1] == NULL) ? 'disabled' : '';
$disabled[2] = ($dates[2] == NULL) ? 'disabled' : '';
?>
	<button id='prev' <?= $disabled[1] ?> onclick="load_date('<?= $dates[1] ?>')">&lt;&lt; Prev</button>
	<button id='next' <?= $disabled[2] ?> onclick="load_date('<?= $dates[2] ?>')">Next &gt;&gt;</button>
	<h2><?= $dates[0] ?></h2>

	Files: (click to download)
	<?php foreach($mp3s as $mp3) { ?>
		<p class='file'>
			<!--
			<a href='download.php?f=<?= $mp3 ?>' class='filename'><?= array_pop(split('/', $mp3)) ?></a>
			-->
			<a href='<?= url::base() ?>recordings/<?= array_pop(split('/', $mp3)) ?>' class='filename'><?= array_pop(split('/', $mp3)) ?></a>
		</p>

	<?php
		$labels .= Sound::label_info($mp3);
	}
	if (count($mp3s) === 0) { ?>
		<p id="nofiles">No files were found for this date.</p>
	<?php }
	echo $labels;
	?>
		
<script type='text/javascript'>
function load_date(date) {
	$('#downloads').load('<?= url::base() ?>dash/downloads/'+date);
}
</script>
