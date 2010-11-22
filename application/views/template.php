<?php
/* vim: set noet fenc= ff=unix sts=0 sw=4 ts=4 : */
/* SVN FILE: $Id: php 135 2009-06-10 02:20:13Z kevin $ */

//require_once('sound.php');
$last_sunday = extras::last_sunday();

$snd = new Sound;
$dbo = $snd->retrieve_last_record();

$labels = glob("/var/www/sound/labels/".$dbo['date']."*.labels");
$mp3s = glob('/var/www/ctk/'.$dbo['date'].'*.mp3');

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>Christ the King Sound Team</title>
	<script type="text/javascript" src="<?= url::base() ?>js/jquery.tools.min.js"> </script>
	<script type="text/javascript" src="<?= url::base() ?>js/jquery.autocomplete.min.js"> </script>
	<script type="text/javascript" src="<?= url::base() ?>js/jquery.form.js"> </script>
<!--
	<script type="text/javascript" src="<?= url::base() ?>js/uploadify/jquery.uploadify.js"> </script>
	<script type="text/javascript" src="<?= url::base() ?>js/jqUploader/jquery.jqUploader.js"> </script>
	<script type="text/javascript" src="<?= url::base() ?>js/jquery-ui/jquery-ui-personalized-1.6rc6.js"> </script>
	<script type="text/javascript" src="http://ui.jquery.com/themeroller/themeswitchertool/"> </script>
-->
	<script type="text/javascript" src="<?= url::base() ?>js/functions.js"> </script>

	<link href="<?= url::base() ?>rss/" title="CtK Released Sound Files" type="application/rss+xml" rel="alternate"/>

	<link title="Tabbed Interface" rel="stylesheet" type="text/css" href="<?= url::base() ?>css/global.css" />
	<link rel="stylesheet" type="text/css" href="<?= url::base() ?>css/tabs.css" />
	<link title="Alternate Interface" media="stylesheet" type="text/css" href="<?= url::base() ?>css/alternate.css" rel="stylesheet"/>
	<link title="Handheld" media="handheld" type="text/css" href="<?= url::base() ?>css/handheld.css" rel="stylesheet"/>

	<link rel="stylesheet" type="text/css" href="<?= url::base() ?>css/jquery.autocomplete.css" />
	<!--
	<link rel="stylesheet" type="text/css" href="<?= url::base() ?>js/uploadify/uploadify.css" />
	<link type="text/css" rel="stylesheet" href="<?= url::base() ?>js/jquery-ui/theme/ui.all.css" />
	-->

	<!-- <link media="Screen" type="text/css" href="<?= url::base() ?>js/jquery-ui/theme/base/ui.base.css" rel="stylesheet"/> -->
	<!-- <link media="Screen" type="text/css" href="<?= url::base() ?>css/themeroller/css/parseTheme.css.php?ctl=themeroller" rel="stylesheet"/> -->

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
		<li class="ui-state-default ui-corner-top"><a href="#downloads">Download</a></li>
		<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#database">Database</a></li>
		<?php // <li class="ui-state-default ui-corner-top"><a href="#filelist">F</a></li> ?>
	</ul>

	<div class="panes">
		<div id="schedule" class="ui-tabs-panel ui-widget-content ui-corner-bottom">
			<?= $schedule ?>
		</div>

		<div id="downloads" class="ui-tabs-panel ui-widget-content ui-corner-bottom">
			<?= $downloads ?>
		</div>

		<div id="database" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
			<?= $database ?>
		</div>
	</div>

	<!--
	<div id="filelist" class="ui-tabs-panel ui-widget-content ui-corner-bottom">
		<?= $filelist ?>
	</div>
	-->

</div>
</body>
</html>
