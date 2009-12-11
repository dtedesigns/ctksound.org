<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div class="box">
	<p>This is the default Kohana index page. You may also access this page as <code><?php echo html::anchor('welcome/index', 'welcome/index') ?></code>.</p>

	<p>
		To change what gets displayed for this page, edit <code>application/controllers/welcome.php</code>.<br />
		To change this text, edit <code>application/views/welcome_content.php</code>.
	</p>
</div>

<ul>
<?php foreach ($links as $title => $url): ?>
	<li><?php echo ($title === 'License') ? html::file_anchor($url, html::specialchars($title)) : html::anchor($url, html::specialchars($title)) ?></li>
<?php endforeach ?>
</ul>

<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
	try{
		var pageTracker = _gat._getTracker("UA-3341219-4");
		pageTracker._trackPageview();
	} catch(err) {}
</script>

