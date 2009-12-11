<?php
/* vim: set noet fenc= ff=unix sts=0 sw=4 ts=4 : */
/* SVN FILE: $Id: php 135 2009-06-10 02:20:13Z kevin $ */
?>BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//ctksound//NONSGML v1.0//EN
<?php foreach($dates as $date) { ?>BEGIN:VEVENT
DTSTART:<?= date("Ymd\T074500",$date['date']) /*19970714T170000Z*/ ?>
DTEND:<?= date("Ymd\T074500",$date['date']) /*19970715T035959Z*/ ?>
SUMMARY:<?= $date['name']?> on Sound Mixer
END:VEVENT<?php } ?>
END:VCALENDAR
