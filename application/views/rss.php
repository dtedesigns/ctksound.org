<?php
// vim: set noet fenc= ff=unix sts=0 sw=4 ts=4 : 
/* SVN FILE: $Id$ */
header('Content-Type: application/rss+xml');
//define('DIRECTORY', '../../webroot/recordings/');
define('DIRECTORY', '/var/www/sound/webroot/recordings');
$dir = glob(DIRECTORY."/*.mp3");
rsort($dir);

require_once('/var/www/sound/application/vendor/getid3/getid3.php');

$rss = "<?xml version='1.0' encoding='UTF-8'?>
<rss version='2.0' xmlns:atom='http://www.w3.org/2005/Atom'>
	<channel>
		<title>Christ the King Released Sound Files - ".htmlentities($_SERVER['SERVER_NAME'])."</title>
		<link>http://{$_SERVER['SERVER_NAME']}/</link>
		<description>This is the place to get all recently released Christ the King sound files</description>
		<language>en-us</language>
		<copyright>Copyright 2001-2011 Christ the King Church</copyright>
		<image>
			<url>http://{$_SERVER['SERVER_NAME']}/ctk.gif</url>
			<title>Christ the King Released Sound Files - ".htmlentities($_SERVER['SERVER_NAME'])."</title>
			<link>http://{$_SERVER['SERVER_NAME']}/ctk/</link>
		</image>
		<managingEditor>kgust@pobox.com (Kevin Gustavson)</managingEditor>
		<webMaster>kgust@pobox.com (Kevin Gustavson)</webMaster>
		<generator>Brain Power</generator>
		<ttl>5</ttl>
		<lastBuildDate>".date('r',filemtime(DIRECTORY))."</lastBuildDate>
		<atom:link href='http://{$_SERVER['SERVER_NAME']}/rss/' rel='self' type='application/rss+xml' />";

foreach ($dir as $file) {
	$filename = basename($file);
	$category = (strpos($file,'ACE')) ? 'Aces' : 'Sermons';
	$size = filesize($file);

	$getID3 = new getID3;
	$id3 = $getID3->analyze($file);

	$dbo = Doctrine_Query::create()
		->from($category)
		->where('date = ?', substr($filename,0,10))
		->execute()
		->getFirst();

	// Ignore unpublished entries
	if ($dbo['published'] === NULL) continue;

	//$date = date('r',strtotime($id3['tags_html']['id3v2']['recording_time'][0]));
	$date = date('r',strtotime($dbo['published']));

	$short_date = date('n/d',strtotime($id3['tags_html']['id3v2']['recording_time'][0]));

	if (isset($id3['tags_html']['id3v2']['text'][1])) $sermon = TRUE;
	else $sermon = FALSE;

	$record_time = $id3['tags_html']['id3v2']['recording_time'][0];

	$full_title = $id3['tags_html']['id3v2']['title'][0];
	//$title = array_shift(split(", ",$full_title));
	if($sermon) {
		$title = explode(", ", $id3['tags_html']['id3v2']['title'][0]);
		array_pop($title);
		$title = implode(", ", $title);
	} else {
		$title = $id3['tags_html']['id3v2']['title'][0];
	}

	//$scripture = $id3['tags_html']['id3v1']['comment'][0];
	//$scripture = $dbo['scripture'];
	$scripture = isset($dbo['scripture']) ? $dbo['scripture'] : $dbo['comment'];

	//$artist = $id3['id3v1']['artist'];
	$artist = isset($dbo['teacher']) ? $dbo['teacher'] : $dbo['preacher'];
	$lastname = array_pop(split(" ",$artist));

	$playtime = $id3['playtime_string'];
	$readtime = $id3['tags_html']['id3v2']['text'][1];

	$url = htmlentities("http://".$_SERVER['SERVER_NAME']."/recordings/".$filename);
	$url = preg_replace('/\s/',' ',$url);
	$url = preg_replace('/\[/','%91',$url);
	$url = preg_replace('/\]/','%93',$url);

	$description = $short_date . ", ";
	$description .= $lastname . ", ";
	if($sermon)$description .= $scripture . ", ";
	$description .= $title . "<br />";
	$description .= $playtime . " Total<br />";
	if ($sermon) $description .= $readtime . " Scripture Reading";
	//$description .= '<pre>'.$readtime.'</pre>';
	//$description .= "{$id3['id3v1']['comment']}";

	$rss .= "		<item>
			<title>{$full_title}</title>
			<description><![CDATA[{$description}]]></description>
			<category>{$category}</category>
			<enclosure url=\"{$url}\" length='$size' type='audio/mpeg'></enclosure>
			<guid>{$url}</guid>
			<pubDate>{$date}</pubDate>
		</item>
";
}
$rss .= "	</channel>
</rss>
";
echo $rss;
?>
