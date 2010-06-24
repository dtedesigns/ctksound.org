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
	<script type="text/javascript" src="<?= url::base() ?>js/jquery-1.3.1.js"> </script>
	<script type="text/javascript" src="<?= url::base() ?>js/jquery.autocomplete.min.js"> </script>
	<script type="text/javascript" src="<?= url::base() ?>js/jquery.form.js"> </script>
	<!-- <script type="text/javascript" src="<?= url::base() ?>js/uploadify/jquery.uploadify.js"> </script> -->
	<script type="text/javascript" src="<?= url::base() ?>js/jqUploader/jquery.jqUploader.js"> </script>
	<script type="text/javascript" src="<?= url::base() ?>js/jquery-ui/jquery-ui-personalized-1.6rc6.js"> </script>
	<!--
	<script type="text/javascript" src="http://ui.jquery.com/themeroller/themeswitchertool/"> </script>
	-->
	<link href="<?= url::base() ?>rss/" title="CtK Released Sound Files" type="application/rss+xml" rel="alternate"/>

	<link title="Tabbed Interface" rel="stylesheet" type="text/css" href="<?= url::base() ?>css/global.css" />
	<link title="Alternate Interface" media="stylesheet" type="text/css" href="<?= url::base() ?>css/alternate.css" rel="stylesheet"/>
	<link title="Handheld" media="handheld" type="text/css" href="<?= url::base() ?>css/handheld.css" rel="stylesheet"/>

	<link rel="stylesheet" type="text/css" href="<?= url::base() ?>css/jquery.autocomplete.css" />
	<link rel="stylesheet" type="text/css" href="<?= url::base() ?>js/uploadify/uploadify.css" />
	<link type="text/css" rel="stylesheet" href="<?= url::base() ?>js/jquery-ui/theme/ui.all.css" />

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
		$('#tabs').tabs();
		//$('#switcher').themeswitcher();
	});
	</script>
</head>
<body>
<h1>Christ the King<br/>Sound Team</h1>
<div id='switcher'> </div>

<div id='tabs' class='ui-tabs ui-widget ui-widget-content ui-corner-all'>
	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active">
			<a href="#welcome">Welcome</a>
		</li>
	</ul>

	<div id="welcome" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
		<h1>This is a demo site.</h1>
		<p>Feel free to change values, add input, etc.</p>
		<p>This is both a work in progress and my testing ground for new technologies.  Expect the interface is a little rough around the edges.  Some functionality has been disabled until it is complete.</p>
		<p>This was built with Kohana 2.x, PHPDoctrine 1.x, jQuery, and jQuery UI.</p>
		<p>With these caveats, welcome! Please enter <a href="<?= url::base() ?>demo/dash/">CTKSound</a>.</p>
	</div>
</div>

<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
	try {
		var pageTracker = _gat._getTracker("UA-3341219-4");
		pageTracker._trackPageview();
	} catch(err) {}
</script>

</body>
</html>
