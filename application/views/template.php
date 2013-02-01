<?php
/* vim: set noet fenc= ff=unix sts=0 sw=4 ts=4 : */
/* SVN FILE: $Id: php 135 2009-06-10 02:20:13Z kevin $ */

//require_once('sound.php');
$last_sunday = extras::last_sunday();

$snd = new Sound;
$dbo = $snd->retrieve_last_record();

$labels = glob("/var/www/sound/labels/".$dbo['date']."*.labels");
$mp3s = glob('/var/www/ctk/'.$dbo['date'].'*.mp3');

?><!DOCTYPE html>
<html>
<head>
	<title>Christ the King Sound Team</title>
	<meta name="viewport" content="width=480, initial-scale=1.0, maximum-scale=2.0, user-scalable=yes" />
	<script type="text/javascript" src="<?= url::base() ?>js/jquery.tools.min.js"> </script>
	<script type="text/javascript" src="<?= url::base() ?>js/jquery-autocomplete/jquery.autocomplete.min.js"> </script>
	<script type="text/javascript" src="<?= url::base() ?>js/jquery.form.js"> </script>
	<script type="text/javascript" src="<?= url::base() ?>js/functions.js"> </script>

	<link href="<?= url::base() ?>rss/" title="CtK Released Sound Files" type="application/rss+xml" rel="alternate"/>

	<link title="Tabbed Interface" media="screen" rel="stylesheet" type="text/css" href="<?= url::base() ?>css/global.css" />
	<link rel="stylesheet" type="text/css" href="<?= url::base() ?>css/tabs.css" />
	<link title="Alternate Interface" media="screen" type="text/css" href="<?= url::base() ?>css/alternate.css" rel="stylesheet"/>
	<link media="handheld, only screen and (max-width: 480px), only screen and (max-device-width: 480px)" href="<?= url::base() ?>css/mobile.css" type="text/css" rel="stylesheet" />
	<link media="screen" rel="stylesheet" type="text/css" href="<?= url::base() ?>js/jquery-autocomplete/jquery.autocomplete.css" />

	<!--[if IEMobile]>
	<link rel="stylesheet" type="text/css" href="<?= url::base() ?>css/mobile.css" media="screen" />
	<![endif]-->

	<!-- Stylesheet Tips 
	Persistent: no title
	<link rel="stylesheet" type="text/css" href="paul.css" />

	Preferred: title
	<link rel="stylesheet" type="text/css" href="paul.css" title="bog standard" />

	Alternate: title, different rel
	<link rel="alternate stylesheet" type="text/css" href="paul.css" title="wacky" />
	-->
	
	<script type='text/javascript'>
	$(document).ready(function() {
		$('ul.tabs').tabs("div.panes > div");
		//$('#switcher').themeswitcher();
	});
	</script>
</head>
<body>
<h1>Christ the King Sound Team</h1>
<div id="switcher"> </div>

<div id="myTabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
	<div id="dates">
	<?= $dates ?>
	</div>
	<!--
	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
	-->
	<ul class="tabs">
		<li class="ui-state-default ui-corner-top"><a href="#schedule">Schedule</a></li>
		<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#database">Database</a></li>
		<li class="ui-state-default ui-corner-top"><a href="#files">Files</a></li>
		<li class="ui-state-default ui-corner-top"><a href="#docs">Documents</a></li>
		<!--
		<li class="ui-state-default ui-corner-top"><a href="#uploads">Upload</a></li>
		-->
	</ul>

	<div class="panes">
		<div id="schedule" class="ui-tabs-panel ui-widget-content ui-corner-bottom">
			<?= $schedule ?>
			<?= $content ?>
		</div>

		<div id="database" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
			<?= $database ?>
		</div>

		<div id="files" class="ui-tabs-panel ui-widget-content ui-corner-bottom">
			<?= $files ?>
		</div>

		<div id="docs" class="ui-tabs-panel ui-widget-content ui-corner-bottom">
			<?= $documents ?>
		</div>
	</div>


</div>
</body>
</html>
