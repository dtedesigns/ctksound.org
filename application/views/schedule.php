<?php
/* vim: set noet fenc= ff=unix sts=0 sw=4 ts=4 : */
/* SVN FILE: $Id: php 135 2009-06-10 02:20:13Z kevin $ */
?>

<!--
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
-->
<iframe style="position: absolute; left: 15px;" src="https://www.google.com/calendar/embed?mode=AGENDA&amp;height=615&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=2ure5nnctu1l60pvdn7vbir5uk%40group.calendar.google.com&amp;color=%23528800&amp;ctz=America%2FChicago" style=" border-width:0 " width="589" height="615" frameborder="0" scrolling="no"></iframe>
