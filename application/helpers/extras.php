<?php
/* vim: set noet fenc= ff=unix sts=0 sw=4 ts=4 : */
/* SVN FILE: $Id: php 135 2009-06-10 02:20:13Z kevin $ */
?>
<?php
// vim: set noet fenc= ff=unix sts=0 sw=4 ts=4 : 

//require_once 'MDB2.php';
//require_once 'getID3.php';
//require_once '../vendor/getid3/getid3.php';

class extras_Core
{
	public function calc_time($time, $include_frames = TRUE) {
		$min = floor($time/60);
		$sec = floor($time - $min*60);
		$frames = ($time - ($min*60 + $sec)) * 75;
		$round = ($frames > 37) ? 1 : 0;

		if ($include_frames) return sprintf("%02d",$min) . ":" . sprintf("%02d",$sec) . ":" . sprintf("%02d",$frames);
		else {
			return sprintf("%02d",$min) . ":" . sprintf("%02d",$sec+$round);
		}

	}

	public function last_sunday($time = NULL) {
		if($time == NULL) $date = time();

		if(date('w', $date) == 0) return date('Y-m-d', strtotime('today'));
		else return date('Y-m-d', strtotime('last sunday'));
	}

	public function execute_command($argv = NULL) {
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
}

?>
