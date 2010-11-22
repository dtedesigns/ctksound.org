<?php
// vim: set noet fenc= ff=unix sts=0 sw=4 ts=4 : 

require_once 'MDB2.php';
require_once 'getID3.php';
//require_once '../vendor/getid3/getid3.php';

class Sound {
	var $filename;
	var $date;
	var $db;
	var $mode;
	var $record;
	var $scripture_reading;

	function __construct($filename = NULL, $date = NULL) {
		if ($filename) $this->parse_filename($filename);
		if ($date) $this->date = $date;

		$this->connect_to_db();
	}

	private function connect_to_db() {
		$dsn = array(
			'phptype'  => 'mysql',
			'username' => 'sermons',
			'password' => 'sermons',
			'hostspec' => '172.22.11.20',
			'database' => 'sermons'
		);

		// Only echo if run from command line
		if ($this->filename) echo "Connecting to database...\n";

		$this->db =& MDB2::connect($dsn);
		if (PEAR::isError($this->db)) {
			return "Couldn't access database: " . $this->db->getMessage();
		}
	}

	private function parse_filename($filename) {
		$this->filename = $filename;

		$parts = split(', ',$filename);
		$this->date = array_shift($parts);
		$this->mode = array_shift($parts);
	}

	public function retrieve_record($date = NULL) {
		if($date == NULL) $date = $this->date;

		if(isset($this->filename)) echo "Retrieving record...\n";

		if($this->mode === "ACE")
			$result = $this->db->query("SELECT * from sermons where date='{$date}' AND type='Aces' limit 1;");
		else
			$result = $this->db->query("SELECT * from sermons where date='{$date}' AND type='Sermons' limit 1;");

		if (PEAR::isError($result)) {
			return "Couldn't execute SELECT: " . $result->getMessage();
		}

		$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);

		if (PEAR::isError($row)) {
			return "Couldn't execute fetchRow: " . $row->getMessage();
		}

		return $this->record = $row;

	}

	public function retrieve_last_record($date = NULL) {
		//if($date == NULL) $date = $this->date;

		//echo "Retrieving record...\n";

		if($this->mode === "ACE")
			//$result = $this->db->query("SELECT * from sermons where date='{$date}' AND type='Aces' limit 1;");
			$result = $this->db->query("SELECT * FROM sermons WHERE type='Aces' ORDER BY date desc LIMIT 1;");
		else
			//$result = $this->db->query("SELECT * FROM sermons WHERE date='{$date}' AND type='Sermons' limit 1;");
			$result = $this->db->query("SELECT * FROM sermons WHERE type='Sermons' ORDER BY date desc LIMIT 1;");

		if (PEAR::isError($result)) {
			return "Couldn't execute SELECT: " . $result->getMessage();
		}

		$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);

		if (PEAR::isError($row)) {
			return "Couldn't execute fetchRow: " . $row->getMessage();
		}

		return $this->record = $row;
	}

	public function retrieve_dates($date = NULL) {
		if($date == NULL or $date == last_sunday()) {
			$current_date = last_sunday();
			$next_date = NULL;
		}

		else {
			/* Next Date */
			$result = $this->db->query("SELECT DISTINCT date FROM sermons WHERE date > '$date' ORDER BY date asc LIMIT 1;");
			if (PEAR::isError($result)) return "Couldn't execute SELECT: " . $result->getMessage();

			$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
			if (PEAR::isError($row)) return "Couldn't execute fetchRow: " . $row->getMessage();

			$next_date = $row['date'];


			/* Current Date */
			$result = $this->db->query("SELECT DISTINCT date FROM sermons WHERE date <= '$date' ORDER BY date desc LIMIT 1;");
			if (PEAR::isError($result)) return "Couldn't execute SELECT: " . $result->getMessage();

			$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
			if (PEAR::isError($row)) return "Couldn't execute fetchRow: " . $row->getMessage();

			$current_date = $row['date'];
		}

		/* Previous Date */
		$result = $this->db->query("SELECT DISTINCT date FROM sermons WHERE date < '$current_date' ORDER BY date desc LIMIT 1;");
		if (PEAR::isError($result)) return "Couldn't execute SELECT: " . $result->getMessage();

		$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
		if (PEAR::isError($row)) return "Couldn't execute fetchRow: " . $row->getMessage();

		$previous_date = $row['date'];


		if($next_date == NULL and $current_date != last_sunday()) $next_date = last_sunday();
		return array($current_date, $previous_date, $next_date);
	}

	public function populate_sermon_id3() {
		// Remove any existing id3 tags
		exec("eyeD3 --remove-all \"".$this->filename."\"");

		extract($this->record);

		// populate other values
		//$track=date("W",strtotime($this->record['date']));
		$publisher="Christ the King Church";
		$copyright="(c){$this->record['year']} Christ the King Church";
		$image_info="/home/kevin/Pictures/ctk.gif:ILLUSTRATION:Christ the King Church
336 McKee Street
Batavia, IL 60510
www.ctkfoxvalley.org";

/* ID3v1 Removed 9/12/09 KG
		// Add id3v1 tags
		$id3v1 = 'eyeD3';
		$id3v1 .= ' --v1';
		//$id3v1 .= ' --verbose';
		$id3v1 .= " --artist='{$preacher}'";
		$id3v1 .= " --album='{$copyright}'";
		$id3v1 .= " --title=\"{$title}\"";
		$id3v1 .= " --track='{$track}'";
		$id3v1 .= " --genre=Speech";
		$id3v1 .= " --year='{$year}'";
		$id3v1 .= " --publisher='{$publisher}'";
		$id3v1 .= " --comment='::{$scripture}'";
		$id3v1 .= " --set-text-frame=TPE2:'{$series}'";
		//$id3v1 .= " --set-text-frame=TCOP:'{$copyright}'";
		$id3v1 .= " --set-text-frame=TPOS:'{$disk}'";
		$id3v1 .= " --set-text-frame=TDRC:'{$date}'";
		$id3v1 .= " \"{$this->filename}\"";
		exec($id3v1);
*/

		// Add id3v2 tags
		$id3v2 = 'eyeD3';
		$id3v2 .= ' --v2';
		//$id3v2 .= ' --verbose';
		$id3v2 .= " --artist='{$preacher}'";
		$id3v2 .= " --album='{$copyright}'";
		$id3v2 .= " --title=\"{$title}, {$scripture}\"";
		$id3v2 .= " --track='{$track}'";
		$id3v2 .= " --genre='Sermon'";
		$id3v2 .= " --year='{$year}'";
		$id3v2 .= " --publisher='{$publisher}'";
		//$id3v2 .= " --comment='::{$copyright}'";
		$id3v2 .= " --add-image='{$image_info}'";
		$id3v2 .= " --set-text-frame=TPE2:'{$series}'";
		$id3v2 .= " --set-text-frame=TCOP:'{$copyright}'";
		$id3v2 .= " --set-text-frame=TPOS:'{$disk}'";
		$id3v2 .= " --set-text-frame=TDRC:'{$date}'";
		$id3v2 .= " \"{$this->filename}\"";
		exec($id3v2);
	}

	public function populate_ace_id3() {
		// Remove any existing id3 tags
		exec("eyeD3 --remove-all \"".$this->filename."\"");

		extract($this->record);

		// populate other values
		//$track=date("W",strtotime($this->record['date']));
		$publisher="Christ the King Church";
		$copyright="(c){$this->record['year']} Christ the King Church";
		$image_info="/home/kevin/Pictures/ctk.gif:ILLUSTRATION:Christ the King Church
336 McKee Street
Batavia, IL 60510
www.ctkfoxvalley.org";

/* ID3v1 Removed 9/12/09 By KG
		// Add id3v1 tags
		$id3v1 = 'eyeD3';
		$id3v1 .= ' --v1';
		//$id3v1 .= ' --verbose';
		$id3v1 .= " --artist='{$preacher}'";
		$id3v1 .= " --album='Adult Christian Education'";
		$id3v1 .= " --title=\"{$title}\"";
		$id3v1 .= " --track='{$track}'";
		$id3v1 .= " --genre=Speech";
		$id3v1 .= " --year='{$year}'";
		$id3v1 .= " --publisher='{$publisher}'";
		$id3v1 .= " --comment='::{$scripture}'";
		$id3v1 .= " --set-text-frame=TPE2:'{$series}'";
		//$id3v1 .= " --set-text-frame=TCOP:'{$copyright}'";
		$id3v1 .= " --set-text-frame=TPOS:'{$disk}'";
		$id3v1 .= " --set-text-frame=TDRC:'{$date}'";
		$id3v1 .= " \"{$this->filename}\"";
		exec($id3v1);
*/

		// Add id3v2 tags
		$id3v2 = 'eyeD3';
		$id3v2 .= ' --v2';
		//$id3v2 .= ' --verbose';
		$id3v2 .= " --artist='{$preacher}'";
		$id3v2 .= " --album='Adult Christian Education'";
		$id3v2 .= " --title=\"{$title}\"";
		$id3v2 .= " --track='{$track}'";
		$id3v2 .= " --genre='Speech'";
		$id3v2 .= " --year='{$year}'";
		$id3v2 .= " --publisher='{$publisher}'";
		$id3v2 .= " --comment='::{$scripture}'";
		$id3v2 .= " --add-image='{$image_info}'";
		$id3v2 .= " --set-text-frame=TPE2:'{$series}'";
		$id3v2 .= " --set-text-frame=TCOP:'{$copyright}'";
		$id3v2 .= " --set-text-frame=TPOS:'{$disk}'";
		$id3v2 .= " --set-text-frame=TDRC:'{$date}'";
		$id3v2 .= " \"{$this->filename}\"";
		exec($id3v2);
	}

	public function write_tags() {
		require_once 'getid3/write.php';
		if(isset($_POST['filename'])) {
			$getID3 = new getID3;
			$getID3->setOption(array('encoding'=>'UTF-8'));

		//$id3v1 .= " --album='Adult Christian Education'";
		//$id3v1 .= " --set-text-frame=TPE2:'{$series}'";
		//  $id3v1 .= " --set-text-frame=TCOP:'{$copyright}'";
		//$id3v1 .= " --set-text-frame=TPOS:'{$disk}'";
		//$id3v1 .= " --set-text-frame=TDRC:'{$date}'";
		//$id3v1 .= " \"{$this->filename}\"";
		//exec($id3v1);

			$tagwriter = new getid_writetags;
			$tagwriter->filename = $_POST['filename'];
			$tagwriter->tagformats = array('id3v1', 'id3v2.3');
			$tagwriter->overwrite_tags = true;
			$tagwriter->tag_encoding('UTF-8');
			$tagwriter->remove_other_tags = true;

			$tags['artist'] = $preacher;
			$tags['album'] = $mode_title;
			$tags['title'] = $title;
			$tags['track'] = $track.'/52';
			$tags['genre'] = 'Speech';
			$tags['year'] = $year;
			$tags['publisher'] = $publisher;
			$tags['comment'] = $scripture;

			$tags['attached_picture'][0]['data'] = $apic_data;
			$tags['attached_picture'][0]['picturetypeid'] = $apic_picture_type;
			$tags['attached_picture'][0]['description'] = $apic_picture_description;
			$tags['attached_picture'][0]['mime'] = $apic_picture_mime;

			$tagwriter->tag_data = $tags;

			if($tagwriter->WriteTags()) {
				echo 'Success!';
				if(!empty($tagwriter->warnings)) echo 'Warnings: '.implode('<br/>',$tagwriter->warnings);
			} else {
				echo 'Failed to write tags!<br/>'.implode('<br/>', $tagwriter->errors);
			}
		}
	}
	public function gen_cue($filename = 'labels.txt') {
		extract($this->record);

		$mp3file = array_pop(split('/',$this->filename));

		$cuefile = "REM Cue file generated by gen_cue().\n\n";
		$cuefile .= "PERFORMER \"$series\"\n";
		$cuefile .= "TITLE \"$title\"\n";
		$cuefile .= "FILE \"{$mp3file}\" MP3\n";

		$track = 1;
		foreach (file($filename) as $line)
		{
			$val = split("\t",$line);

			if($track == 1) {
				$this->scripture_reading = calc_time($val[1],FALSE);
				$performer = $reader;
			} else {
				$performer = $preacher;
			}

			$cuefile .= "  TRACK ". sprintf("%02d",$track++) . " AUDIO\n";
			$cuefile .= '    PERFORMER "' . $performer . "\"\n";
			$cuefile .= "    TITLE \"" . trim($val[2]) . "\"\n";
			$cuefile .= "    INDEX 01 " . calc_time($val[0]) . "\n";

		}
		$cuefile .= "\nREM Running Time = " . calc_time($val[1]);

		return $cuefile;
	}
	public function write_text_tag($desc,$value) {
		print("eyeD3 --set-user-text-frame='{$desc}:{$value}' \"{$this->filename}\"");
		exec("eyeD3 --set-user-text-frame='{$desc}:{$value}' \"{$this->filename}\"");
	}
	public function write_record() {
		// includes series, title, preacher, scripture, reader, type, date
		extract($_REQUEST);

		$track = date('W',strtotime($date));
		$year = date('Y',strtotime($date));
		$disk = 8;

		if (isset($reader)) {
			$result = $this->db->prepare("INSERT INTO sermons (series,title,preacher,scripture,reader,date,track,year,disk,type,created,modified) VALUES (?,?,?,?,?,?,?,?,?,?,now(),now())");
			$result->execute(array($series,$title,$preacher,$scripture,$reader,$date,$track,$year,$disk,$type));
		} else {
			$result = $this->db->prepare("INSERT INTO sermons (series,title,preacher,scripture,date,track,year,disk,type,created,modified) VALUES (?,?,?,?,?,?,?,?,?,now(),now())");
			$result->execute(array($series,$title,$preacher,$scripture,$date,$track,$year,$disk,$type));
		}

		if (PEAR::isError($result)) {
			return "Couldn't execute INSERT: " . $result->getMessage();
		}

		$result->free();
		return "Write successful!";
	}
	public function label_info($file) {
		// FIXME Cleanup these variables
		$filename = basename($file);
		//$category = (strpos($file,', ACE')) ? 'Sunday School' : 'Sermon';
		$category = (strpos($file,', ACE')) ? 'Aces' : 'Sermons';
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
		//else $sermon = FALSE;
		else return false;

		$record_time = $id3['tags_html']['id3v2']['recording_time'][0];
		if($sermon) {
			$title = explode(", ", $id3['tags_html']['id3v2']['title'][0]);
			array_pop($title);
			$title = implode(", ", $title);
		} else {
			$title = $id3['tags_html']['id3v2']['title'][0];
		}

		//$title = array_shift(split(", ",$full_title));

		//$scripture = $id3['tags_html']['id3v1']['comment'][0];
		$scripture = $dbo['scripture'];

		//$artist = $id3['id3v1']['artist'];
		$artist = $dbo['preacher'];
		$lastname = array_pop(split(" ",$artist));

		$playtime = $id3['playtime_string'];
		$readtime = $id3['tags_html']['id3v2']['text'][1];

		$description = $short_date . ", ";
		$description .= $lastname . ", ";
		if($sermon)$description .= $scripture . ", ";
		//$description .= ($sermon) ? $title . "<br />" : $full_title . "<br />";
		$description .=  $title . "<br />";
		$description .= $playtime . " Total<br />";
		if ($sermon) $description .= $readtime . " Scripture Reading";

		$labelInfo = "<br />Label Info:";
		$labelInfo .= ($dbo[published] === NULL) ? " (Unpublished)" : "";
		$labelInfo .= "<br /><span>". $description . '</span>';

		return $labelInfo;
	}
function last_sunday($time = NULL) {
	if($time == NULL) $date = time();

	if(date('w', $date) == 0) return date('Y-m-d', strtotime('today'));
	else return date('Y-m-d', strtotime('last sunday'));
}

}


function calc_time($time, $include_frames = TRUE) {
	$min = floor($time/60);
	$sec = floor($time - $min*60);
	$frames = ($time - ($min*60 + $sec)) * 75;
	$round = ($frames > 37) ? 1 : 0;

	if ($include_frames) return sprintf("%02d",$min) . ":" . sprintf("%02d",$sec) . ":" . sprintf("%02d",$frames);
	else {
		return sprintf("%02d",$min) . ":" . sprintf("%02d",$sec+$round);
	}

}

function last_sunday($time = NULL) {
	if($time == NULL) $date = time();

	if(date('w', $date) == 0) return date('Y-m-d', strtotime('today'));
	else return date('Y-m-d', strtotime('last sunday'));
}

function execute_command($argv = NULL) {
	// Begin execution
	if (isset($argv)) {
		$action = array_pop(split('/',$argv[0]));
		$snd = new Sound($argv[1]);
	} else {
		$action = array_pop(split('/',$_SERVER['SCRIPT_NAME']));
		$snd = new Sound(null,$_REQUEST['date']);
	}

	switch($action) {
		case 'cuegen':
			$dbo = $snd->retrieve_record();
			$cuefile = $snd->gen_cue();
			echo $cuefile."\n";
			file_put_contents('ctk.cue',$cuefile);
			break;
		case 'id3':
			$dbo = $snd->retrieve_record();
			if ($snd->mode === 'ACE') {
				$snd->populate_ace_id3();
			} else {
				$snd->populate_sermon_id3();
				$cuefile = $snd->gen_cue();
				file_put_contents('ctk.cue',$cuefile);
				$snd->write_text_tag('Cue File',$cuefile);
				$snd->write_text_tag('Scripture',$snd->scripture_reading);
			}
			break;
		case 'id3sermon':
			$dbo = $snd->retrieve_record();
			$snd->populate_sermon_id3();
			$cuefile = $snd->gen_cue();
			file_put_contents('ctk.cue',$cuefile);
			$snd->write_text_tag('Cue File',$cuefile);
			$snd->write_text_tag('Scripture',$snd->scripture_reading);
			break;
		case 'id3ace':
			$dbo = $snd->retrieve_record();
			$snd->populate_ace_id3();
			break;
		case 'write.php':
		case 'write_sermon.php':
		case 'write_ace.php':
			echo $snd->write_record();
			break;
		case 'index.php':
			// do nothing
			break;
		default:
			echo "Invalid command: $action\n";
	}
}

execute_command($argv);

?>
