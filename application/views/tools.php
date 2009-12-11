<?php
// vim: set noet fenc= ff=unix sts=0 sw=4 ts=4 : 
/* SVN FILE: $Id$ */
$disabled['id3'] = (count($labels) > 0 && count($mp3s) > 0) ? '' : 'disabled';
$disabled['cue'] = (count($labels) > 0 && $dates[0] != NULL) ? '' : 'disabled';
?>
	<form enctype="multipart/form-data" action="uploader.php" method="POST">
		<?php if(false) { ?>
		Choose a file to upload: <input name="uploadedfile" type="file" /><br />
		<input type="submit" value="Upload File" />
		<script type='text/javascript'>
		//$(document).ready(function() {
			//$('form input[type=file]').jqUploader({ background: "FFFFDF", barColor: "FF00FF" });
		//});
		</script>
		<?php } ?>
	</form>

	<br />Current label file:
	<ul>
	<?php foreach($labels as $file) {
		echo "<li>" . array_pop(split('/',$file)) . "</li>";
	} ?>
	</ul>

	Current sound files:
	<ul>
	<?php foreach($mp3s as $file) {
		echo '<li>' . array_pop(split('/',$file)) . '</li>';
	} ?>
	</ul>

	<p>
		<button <?= $disabled['id3'] ?> >Write ID3 tags</button>
		<button <?= $disabled['cue'] ?> onclick="window.location = '/dash/gencue/<?= $dates[0] ?>'">Download Cue File</button>
	</p>

