<?php
/* vim: set noet fenc= ff=unix sts=0 sw=4 ts=4 : */
/* SVN FILE: $Id$ */
?>
<!--
<pre>
6/06, Holwerda, Psalm 32, The Blessing of Forgiveness
32:58 Total
02:06 Scripture Reading
</pre>
-->
	<h3>Label Info:</h3><span class="label_info">
	<?php

		echo date('n/d', strtotime($date)) . ', ';

		$name = array_pop(split(' ', $preacher));
		echo $name . ', ';

		echo $scripture . ', ';
		echo $title . '<br />';

		echo $total_time . ' Total<br />';
		echo $reading_time . ' Scripture Reading<br />';

	?>
	</span>
