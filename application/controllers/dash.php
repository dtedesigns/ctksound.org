<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 *  Dashboard Controller
 */
class Dash_Controller extends Template_Controller {

	// Set the name of the template to use
	public $template = 'template';

	public function index()
	{
		// In Kohana, all views are loaded and treated as objects.
		$this->template->content = new View('dashboard');
		$this->template->schedule = self::schedule();
		$this->template->files = self::files();
		$this->template->database = self::database();
		$this->template->uploads = self::uploads();
		$this->template->filelist = self::filelist();
		$this->template->dates = self::dates();
	}

	public function dates($date = NULL) {
		if(request::is_ajax()) $this->template = new View('ajax');
		$v = new View('dates');
		$snd = new Sound;
		$v->dates = $snd->retrieve_dates($date);

		if(request::is_ajax())
			$this->template->content = $v;
		else
			return $v->render();
	}

	public function schedule($date = NULL) {
		if(request::is_ajax()) $this->template = new View('ajax');
		$v =  new View('schedule');

		$snd = new Sound;
		$dates = $snd->retrieve_dates($date);
		$v->dates = $dates;

		//$v->schedule = Doctrine::getTable('Schedule')->findAll()->toArray();
		$v->schedule = Doctrine_Query::create()
			->from('Schedule')
			->where("date >= ?",Sound::last_sunday())
			->limit(8)
			->orderBy('date')
			->execute()
			->toArray();

		if(request::is_ajax()) 
			$this->template->content = $v;
		else
			return $v->render();
	}

	public function files($date = NULL) {
		if(request::is_ajax()) $this->template = new View('ajax');
		$v = new View('files');

		$snd = new Sound;
		$dates = $snd->retrieve_dates($date);
		$v->dates = $dates;

		// For each of the recording types...
		// 1. query the database for the approriate entries
		// 2. glob for that file and add it to the array
		$files = array_merge(glob('recordings/'.$dates[0].'*.mp3'), glob('recordings/Older/'.$dates[0].'*.mp3'));
		FB::log('recordings/'.$dates[0].'*.mp3');
		foreach($files as $file) {
			$collection = array();
			$collection['mp3'] = $file;
			if(file_exists('labels/' . basename($file,'mp3') . 'labels')) {
				$collection['labels'] = 'labels/' . basename($file,'mp3') . 'labels';
			}
			if(file_exists('Originals/' . basename($file,'mp3') . 'flac')) {
				$collection['flac'] = 'Originals/' . basename($file,'mp3') . 'flac';
			}
			if(file_exists('labels/' . basename($file,'mp3') . 'labels')) {
				$collection['labels'] = 'labels/' . basename($file,'mp3') . 'labels';
			}
			if(count($collection)>1) $collection['label_info'] = Data_Controller::label($file);
			$collection['data'] = Data_Controller::db_entry($file);

			$types[] = $collection ? $collection : array();
		}
		$v->types = $types;

		$v->mp3s = glob('recordings/'.$dates[0].'*.mp3');
		$v->mp3s = array_merge($v->mp3s, glob('recordings/Older/'.$dates[0].'*.mp3'));
		$v->labels = glob("labels/".$dates[0]."*.labels");
		$v->originals = glob("Originals/".$dates[0]."*.flac");

		foreach($v->mp3s as $file) {
			$v->labelInfo = Data_Controller::label($file);
		}

		if(request::is_ajax()) 
			$this->template->content = $v;
		else
			return $v->render();
	}

	public function database($date = NULL) {
		if(request::is_ajax()) $this->template = new View('ajax');

		$snd = new Sound;
		$dates = $snd->retrieve_dates($date);
		$v->dates = $dates;

		$v = new View('database');
		$v->sunday = $dates[0];
		$v->sermon = Doctrine_Query::create()
			->from('Sermons')
			->where("date = ? AND type= ?", array($v->sunday, 'Sermons'))
			->execute()
			->getFirst();

		$v->ace = Doctrine_Query::create()
			->from('Aces')
			->where("date = ?", $v->sunday)
			->execute()
			->getFirst();

		$v->portrait = Doctrine_Query::create()
			->from('Portraits')
			->where("date = ?", $v->sunday)
			->execute()
			->getFirst();

		$v->dedication = Doctrine_Query::create()
			->from('Dedications')
			->where("date = ?", $v->sunday)
			->execute()
			->getFirst();

		if(request::is_ajax()) 
			$this->template->content = $v;
		else
			return $v->render();
	}

	public function uploads($date = null) {
		if(request::is_ajax()) $this->template = new View('ajax');
		$v = new View('uploads');

		$snd = new Sound;
		$dates = $snd->retrieve_dates($date);

		$v->dates = $dates;
		$v->mp3s = glob('/var/www/sound/webroot/recordings/'.$dates[0].'*.mp3');
		$v->mp3s = array_merge($v->mp3s, glob('/var/www//sound/webroot/recordings/Older/'.$dates[0].'*.mp3'));
		$v->labels = glob("/var/www/sound/webroot/labels/".$dates[0]."*.labels");
		$v->originals = glob("/var/www/sound/webroot/Originals/".$dates[0]."*.flac");

		if(request::is_ajax()) 
			$this->template->content = $v;
		else
			return $v->render();
	}

	public function filelist($date = null) {
		if(request::is_ajax()) $this->template = new View('ajax');
		$v = new View('filelist');

		//$snd = new Sound;
		//$v->dates = $snd->retrieve_dates($date);
		$v->dates = Doctrine_Query::create()
			->from('Sermons')
			->orderBy('date desc')
			->limit(9)
			->execute();

		if(request::is_ajax()) 
			$this->template->content = $v;
		else
			return $v->render();
	}

	public function upload() {
		var_dump($_POST);
	}

	public function autocomplete() {
		if(request::is_ajax())
			$this->template = new View('ajax');
		else
			die('Invalid request.');

		extract($_REQUEST);

		if($val == 'book') {
			$this->_search_books($q);
			die();
		}

		$values = Doctrine_Query::create()
			->select($val)
			->from($type)
			->where($val.' LIKE ?', $q.'%')
			//->orderBy($val)
			//->limit($limit)
			->execute();

		foreach($values->toArray() as $value) {
			$new[] = $value[$val];
		}

		if(!$new) exit();
		$values = array_unique($new);

		echo implode("\n", $values); exit();
	}

	function _search_books($q) {
			$ot = array(
					'Genesis', 'Exodus', 'Leviticus', 'Numbers', 'Deuteronomy',
					'Joshua', 'Judges', 'Ruth',
					'1 Samuel', '2 Samuel',
					'1 Kings', '2 Kings',
					'1 Chronicles', '2 Chronicles',
					'Ezra', 'Nehemiah',
			//		'Tobit', 'Judith',
					'Esther',
			//		'1 Maccabees', '2 Maccabees',
					'Job',
					'Psalms', 'Proverbs', 'Ecclesiastes', 'Song of Songs',
			//		'Wisdom', 'Sirach',
					'Isaiah', 'Jeremiah', 'Lamentations',
			//		'Baruch',
					'Ezekiel', 'Daniel',
					'Hosea', 'Joel', 'Amos', 'Obadiah',
					'Jonah',
					'Micah', 'Nahum', 'Habakkuk', 'Zephaniah', 'Haggai', 'Zechariah', 'Malachi'
			);

			$nt = array(
					'Matthew', 'Mark', 'Luke', 'John',
					'Acts', 'Romans',
					'1 Corinthians', '2 Corinthians',
					'Galatians', 'Ephesians', 'Philippians', 'Colossians',
					'1 Thessalonians', '2 Thessalonians',
					'1 Timothy', '2 Timothy',
					'Titus',
					'Philemon',
					'Hebrews',
					'James',
					'1 Peter', '2 Peter',
					'1 John', '2 John', '3 John',
					'Jude',
					'Revelation'
			);

		foreach(array_merge($ot,$nt) as $book)
			if(stripos($book,$q) !== FALSE) echo $book."\n";

	}


	public function write_record() {
		if(request::is_ajax()) $this->template = new View('ajax');
		else die('Invalid request.');
		
		in_array($_POST['type'], array('Sermons','Aces','Portraits','Dedications')) or die('Invalid form request.');

		// Field Types
		//   Sermon: series, title, preacher, scripture, reader, date, disk, type
		//   Aces: series, title, teacher, comment, date, disk
		//   Portraits: date, speaker, comment, notice_sent
		//   Dedications: official, child, comment, notice_sent

		$record = Doctrine_Query::create()
			->from($_REQUEST['type'])
			->where('date = ?', $_REQUEST['date'])
			->orderBy('id desc')
			->execute()
			->getFirst();

		if(!$record) {
			$record = new $_REQUEST['type'];
		}

		$record->merge($_REQUEST);
		if(in_array($_REQUEST['type'], array('Sermons','Aces'))) {
			$record->disk = 10; // FIXME year needs to be based on date
		}

		if($_REQUEST['type'] == 'Sermons') {
			$record->track = date('W',strtotime($_REQUEST['date']));
			$record->year = date('Y',strtotime($_REQUEST['date']));

			// Encode the SPL fields
			extract($_REQUEST);
			$hymns_spl = array(array('type'=>$hymns_type1, 'value'=>$hymns_value1),
				array('type'=>$hymns_type2, 'value'=>$hymns_value2));
			$record->hymns_spl = json_encode($hymns_spl);
			$sermon_spl = array(array('type'=>$sermon_type1, 'value'=>$sermon_value1),
				array('type'=>$sermon_type2, 'value'=>$sermon_value2));
			$record->sermon_spl = json_encode($sermon_spl);
		}

		if($_REQUEST['type'] == 'Aces') {
			extract($_REQUEST);
			$spl = array(array('type'=>$spl_type1, 'value'=>$spl_value1),
				array('type'=>$spl_type2, 'value'=>$spl_value2));
			$record->spl = json_encode($spl);
		}

		if($record->trySave())
			$this->template->content = "Write successful!";
		else
			$this->template->content = "Write failed!";
	}
}
