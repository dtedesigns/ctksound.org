<?php
// vim: set noet fenc= ff=unix sts=0 sw=4 ts=4 : 
/* SVN FILE: $Id$ */
//$disabled['id3'] = (count($labels) > 0 && count($mp3s) > 0) ? '' : 'disabled';
$disabled['id3'] = 'disabled';
$disabled['cue'] = (count($labels) > 0 && $dates[0] != NULL) ? '' : 'disabled';
?>

	<div id="label_info">
	<?php if($types) foreach($types as $collection) {
		if(key_exists('label_info', $collection)) { echo $collection['label_info']; }
	} ?>
	</div>
	<!--<a id="send_mail" href="mailto:kgust@pobox.com">Send Email</a>-->

	<div id="filelist">
		<h3>Files (click to download):</h3>
		<?php if($types) foreach($types as $collection) {
			$filetype = substr(strrchr($collection['mp3'], '.'), 1);
			if($collection['data']) {
				$record_type = $collection['data']->getTable()->getTableName();
				$data = $collection['data']->toArray();
			}
			else {
				$record_type = 'null';
				$data[] = null;
			}
			$published = ($data['published']) ? "published" : "unpublished";
			$title = ($data['published']) ? "Double click to unpublish" : "Double click to publish";
			$display_name = preg_split('/,/',basename($collection['mp3'],'.mp3'));
			$display = implode(',',array_slice($display_name,0,2));
			array_shift($display_name); array_shift($display_name);
			$display .= '<br/>'.implode(',',$display_name);
			$display = str_replace('+', ':', $display);
		?>
		<div id="<?= $record_type."_".$data[id] ?>" class="recording <?= $published ?>" title="<?= $title ?>"><span class="name"><?= $display ?></span>
			<?php foreach($collection as $type => $file) {
				if($type === 'data') continue;
				$filetype = substr(strrchr($file, '.'), 1);
				if($type == 'label_info') continue; ?>
        </div>
				<a href='<?= url::base() . $file ?>' class="<?= $filetype ?>" title="<?= array_pop(split('/', $file)) ?>"><?= $filetype ?></a>
			<?php } ?>
			<?php if($collection['label_info']) { ?>
				<a href="<?= url::base() ?>data/gencue/<?= $dates[0] ?>" class="cue" title="<?= $dates[0] ?>.cue">cue</a>
			<?php } ?>
			</div>
			<script type="text/javascript">
				$("#<?= $record_type."_".$data[id] ?>").dblclick(function() {
					$.post("/data/publish",
						{
							record: "<?= ucfirst($record_type) ?>",
							number: "<?= $data["id"] ?>"
						},
						function(response) {
							$("#<?= $record_type."_".$data[id] ?>").css("background-image","url(/css/images/recording_bg_"+response+".png)");
							if("active" == response) $('#<?= $record_type.'_'.$data['id'] ?>').attr('title','Double click to unpublish');
							if("inactive" == response) $('#<?= $record_type.'_'.$data['id'] ?>').attr('title','Double click to publish');
						},
						"json"
					);
				});
			 </script>
		<?php } ?>
	</div>

	<div id="filelist2" style="display:none;">
		<h3>Files:</h3>
		<?php if($types) foreach($types as $collection) {
			$filetype = substr(strrchr($collection['mp3'], '.'), 1);
			if($collection['data']) {
				$record_type = $collection['data']->getTable()->getTableName();
				$data = $collection['data']->toArray();
			}
			else {
				$record_type = 'null';
				$data[] = null;
			}
			$published = ($data['published']) ? "published" : "unpublished";
			$title = ($data['published']) ? "Double click to unpublish" : "Double click to publish";
			$display_name = preg_split('/,/',basename($collection['mp3'],'.mp3'));
			$display = implode(',',array_slice($display_name,0,2));
			array_shift($display_name); array_shift($display_name);
			$display .= '<br/>'.implode(',',$display_name);
			$display = str_replace('+', ':', $display);
		?>
		<div id="<?= $record_type."_".$data[id] ?>" class="recording <?= $published ?>" title="<?= $title ?>"><span class="name"><?= $display ?></span>
			<?php foreach($collection as $type => $file) {
				if($type === 'data') continue;
				$filetype = substr(strrchr($file, '.'), 1);
				if($type == 'label_info') continue; ?>
				<a href='<?= url::base() . $file ?>' class="<?= $filetype ?>" title="<?= array_pop(split('/', $file)) ?>"><?= $filetype ?></a>
			<?php } ?>
			<?php if($collection['label_info']) { ?>
				<a href="<?= url::base() ?>data/gencue/<?= $dates[0] ?>" class="cue" title="<?= $dates[0] ?>.cue">cue</a>
			<?php } ?>
			</div>
			<script type="text/javascript">
				//$('.recording').tooltip({position: "top left"});
				$("#<?= $record_type."_".$data[id] ?>").dblclick(function() {
					$.post("/data/publish",
						{
							record: "<?= ucfirst($record_type) ?>",
							number: "<?= $data["id"] ?>"
						},
						function(response) {
							$("#<?= $record_type."_".$data[id] ?>").css("background-image","url(/css/images/recording_bg_"+response+".png)");
							if("active" == response) $('#<?= $record_type.'_'.$data['id'] ?>').attr('title','Double click to unpublish');
							if("inactive" == response) $('#<?= $record_type.'_'.$data['id'] ?>').attr('title','Double click to publish');
						},
						"json"
					);
				});
			 </script>
		<?php } ?>
	</div>

	<?php
	if (count($types) === 0) { ?>
		<p id="nofiles">No files were found for this date.</p>
	<?php } ?>

	<!--
	<p>
		<button <?= $disabled['id3'] ?> >Write ID3 tags</button>
		<button <?= $disabled['cue'] ?> onclick="window.location = '<?= url::base() ?>data/gencue/<?= $dates[0] ?>'">Download Cue File</button>
	</p>
	-->
