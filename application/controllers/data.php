<?php
/* vim: set noet fenc= ff=unix sts=0 sw=4 ts=4 : */
/* SVN FILE: $Id: php 135 2009-06-10 02:20:13Z kevin $ */
/**
 *  Data Controller
 *  Provide data records in json format for ID3 tagging
 *    /data/sermons/2009-11-29
 */
class Data_Controller extends Template_Controller {

	// Set the name of the template to use
	public $template = 'template';

	public function sermons($date) {
		self::record('Sermons', $date);
	}

	public function aces($date) {
		self::record('Aces', $date);
	}

	public function portraits($date) {
		self::record('Portraits', $date);
	}

	public function dedications($date) {
		self::record('Dedications', $date);
	}

	public function record($type, $date = null) {
		$this->template = new View('ajax');

		if($date == null) $date = Sound::last_sunday();

		//$v->data = Doctrine::getTable('Sermons')->findOneByDql("date = ? AND type= ?", array($v->last_sunday, 'sermon'));
		$record = Doctrine_Query::create()
			->from($type)
			->where("date = ?", $date)
			->execute()
			->getFirst();

		if($record) $this->template->content = json_encode($record->toArray());
		else echo 'null';
	}

	public function gencue($date,$as_file = TRUE) {
		$this->template = null;
		$this->auto_render = FALSE;

		$snd = new Sound;
		$snd->date = $date;
		$snd->mode = "Sermon";
		$snd->retrieve_record();
		$filenames = glob('/var/www/sound/webroot/recordings/'.$date.'*.mp3');
		$snd->filename = $filenames[0];

		$filenames = glob('/var/www/sound/webroot/labels/'.$date.'*.labels');
		if($as_file) {
			header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
			header("Content-Type: text/plain; charset: utf-8");
			header("Content-Disposition: attachment; filename=$date.cue");
		}
		echo $snd->gen_cue($filenames[0]);
	}

	public function label($file) {
		$filename = '/var/www/sound/webroot/recordings/' . basename($file);
		if(!file_exists($filename)) $filename = '/var/www/sound/webroot/recordings/Older/' . basename($file);
		if(!file_exists($filename)) return false;

		$category = 'Sermons';
		//if(strpos($file,', ACE')) $category = 'Aces';
		//if(strpos($file,', Dedication')) $category = 'Dedications';
		//if(strpos($file,', Baptism')) $category = 'Dedications';
		//if(strpos($file,', Portrait')) $category = 'Portraits';
		if(strpos($file,', ACE')) return false;
		if(strpos($file,', Dedication')) return false;
		if(strpos($file,', Baptism')) return false;
		if(strpos($file,', Portrait')) return false;
		$size = filesize($filename);

		$getID3 = new getID3;
		$id3 = $getID3->analyze($filename);

		$date = date('r',strtotime($id3['tags_html']['id3v2']['recording_time'][0]));
		//$short_date = date('n/d',strtotime($id3['tags_html']['id3v2']['recording_time'][0]));

		// FIXME this query should not use type!!
		$dbo = Doctrine_Query::create()
			->from('Sermons')
			->where('date = ?', date("Y-m-d H:i:s", strtotime($date)))
			->execute()
			->getFirst();

		$playtime = $id3['playtime_string'];
		$readtime = $id3['tags_html']['id3v2']['text'][1];

		$v = new View('label_info');
		$v->date = $date;
		$v->preacher = $dbo['preacher'];
		$v->scripture = $dbo['scripture'];
		$v->title = $dbo['title'];
		$v->total_time = $playtime;
		$v->reading_time = $readtime;

		return $v->render();
	}

	public function db_entry($file) {
		$filename = '/var/www/sound/webroot/recordings/' . basename($file);
		if(!file_exists($filename)) $filename = '/var/www/sound/webroot/recordings/Older/' . basename($file);
		if(!file_exists($filename)) return false;

		$category = 'Sermons';
		if(strpos($file,', ACE')) $category = 'Aces';
		if(strpos($file,', Dedication')) $category = 'Dedications';
		if(strpos($file,', Baptism')) $category = 'Dedications';
		if(strpos($file,', Portrait')) $category = 'Portraits';

		$date = substr($file, 11, 10);

		$dbo = Doctrine::getTable($category)->findOneByDate(date("Y-m-d", strtotime($date)));

		return $dbo;
	}

	public function publish() {
		if(!request::is_ajax()) return false;
		$this->template = new View('ajax');

		$date = $_POST['date'];
		$category = $_POST['record'];
		$this->template = new View('ajax');
		$dbo = Doctrine::getTable($category)->findOneById($_POST['number']);
		if($dbo->published) $dbo->set('published', null);
		else $dbo->published = date("Y-m-d H:i:s");
		$dbo->save();
		$this->template->content = json_encode($dbo->published ? "active" : "inactive");
	}
}
?>
