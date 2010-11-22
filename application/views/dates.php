<?php
$disabled[1] = ($dates[1] == NULL) ? 'disabled' : '';
$disabled[2] = ($dates[2] == NULL) ? 'disabled' : '';
?>
		<button id='prev' <?= $disabled[1] ?> onclick="load_date('<?= $dates[1] ?>')">&lt;&lt; Prev</button>
		<button id='next' <?= $disabled[2] ?> onclick="load_date('<?= $dates[2] ?>')">Next &gt;&gt;</button>
		<h2><?= $dates[0] ?></h2>

