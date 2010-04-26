<?php
/* vim: set noet fenc= ff=unix sts=0 sw=4 ts=4 : */
/* SVN FILE: $Id: php 135 2009-06-10 02:20:13Z kevin $ */
?>
<style type="text/javascript">
li { font-size: 75%; }
</style>

<ul>
<?php foreach($dates as $d) { ?>
	<li><?= $d->date ?> <?= $d->title ?></li>
<?php } ?>
</ul>
