<?php
/* vim: set noet fenc= ff=unix sts=0 sw=4 ts=4 : */
/* SVN FILE: $Id: php 135 2009-06-10 02:20:13Z kevin $ */
?>

<h3>Service Schedule</h3>
<table id='schedule'>
<tbody>
	<?php foreach($schedule as $date) { ?>
		<tr><th><?= date("m-d", strtotime($date['date'])) ?></th>
			<td><?= $date['name'] ?></td>
			<td><?= $date['alert_sent'] ? "Reminded" : "Not Yet" ?></td>
		</tr>
	<?php } ?>
</tbody>
</table>
