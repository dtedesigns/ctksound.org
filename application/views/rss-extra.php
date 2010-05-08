<?php
define('DIRECTORY', '/var/www/ctk');
$dir = glob(DIRECTORY."/*.mp3");
rsort($dir);

require_once('/var/www/sound/getid3/getid3.php');

$items = array();

foreach ($dir as $file) {
	$filename = basename($file);
	$category = (strpos($file,'ACE')) ? 'Aces' : 'Sermons';
	$size = filesize($file);

	$getID3 = new getID3;
	$id3 = $getID3->analyze($file);

	$date = date('r',strtotime($id3['tags_html']['id3v2']['recording_time'][0]));
	$short_date = date('n/d',strtotime($id3['tags_html']['id3v2']['recording_time'][0]));

	$dbo = Doctrine_Query::create()
		->from('Sermons')
		->where('date = ? and type = ?', array(date("Y-m-d H:i:s", strtotime($date)),$category))
		->execute()
		->getFirst();

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
	$scripture = $dbo['scripture'];

	//$artist = $id3['id3v1']['artist'];
	$artist = $dbo['preacher'];
	$lastname = array_pop(split(" ",$artist));

	$playtime = $id3['playtime_string'];
	$readtime = $id3['tags_html']['id3v2']['text'][1];

	$url = htmlentities("http://".$_SERVER['SERVER_NAME']."/ctk/".$filename);
	$url = preg_replace('/\s/','%20',$url);

	$description = $short_date . ", ";
	$description .= $lastname . ", ";
	if($sermon)$description .= $scripture . ", ";
	$description .= $title . "<br />";
	$description .= $playtime . " Total<br />";
	if ($sermon) $description .= $readtime . " Scripture Reading";
	//$description .= '<pre>'.$readtime.'</pre>';
	//$description .= "{$id3['id3v1']['comment']}";

	$items[] = array(
		'title' => $full_title,
		'description' => '<![CDATA['.$description.'}]]>',
		'category' => $category,
		'enclosure' => '',
		'guid' => $url,
		'pubDate' => $date,
	);
	//$rss .= "		<item>
			//<title>{$full_title}</title>
			//<description><![CDATA[{$description}]]></description>
			//<category>{$category}</category>
			//<enclosure url=\"{$url}\" length='$size' type='audio/mpeg'></enclosure>
			//<guid>{$url}</guid>
			//<pubDate>{$date}</pubDate>
		//</item>
//";
}

$temp = feed::create(
	array(
		'title' => 'Christ the King Released Sound Files - '.htmlentities($_SERVER['SERVER_NAME']),
		'link' => "http://{$_SERVER['SERVER_NAME']}/ctk/",
		'description' => "This is the place to get all recently released Christ the King sound files",
		'language' => 'en-us',
		'copyright' => 'Copyright 2009 Christ the King Church',
		'managingEditor' => 'kgust@pobox.com (Kevin Gustavson)',
		'webMaster' => 'kgust@pobox.com (Kevin Gustavson)',
		'generator' => 'Brain Power',
		'ttl' => '5',
		'lastBuildDate' => date('r',filemtime(DIRECTORY)),
	),
	$items,
	'rss2'
);

?>