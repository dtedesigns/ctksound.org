<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 *  Dashboard Controller
 */
class Dash_Controller extends Template_Controller {

    // Set the name of the template to use
    public $template = 'template';

    public function __construct() {
        parent::__construct();
        $this->session = Session::instance();
        $auth = new Auth;

        if (! $auth->logged_in()) {
            //var_dump('Not logged in.');
            $this->session->set("requested_url","/".url::current()); // this will redirect from the login page back to this page
            url::redirect('/user/login');
        } else {
            //var_dump('Logged in.');
            $this->user = $auth->get_user(); // now you have user info access
        }
    }
/*
    public function __construct() {
        parent::__construct();
        $this->session = Session::instance();
        //$auth = new Auth;

        if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
            $realm = "Authorization Required";
            header('HTTP/1.1 401 Unauthorized');
            header('WWW-Authenticate: Digest realm="'.$realm.'",qop="auth",nonce="'.uniqid().'",opaque="'.md5($realm).'"');
            exit('You are not authorized to use this service.');
            //$this->session->set("requested_url","/".url::current()); // this will redirect from the login page back to this page
            //url::redirect('/user/login');
        } else {
            //$this->user = $auth->get_user(); // now you have user info access
            if (!($data = $this->http_digest_parse($_SERVER['PHP_AUTH_DIGEST'])) OR
                !isset($users[$data['username']]))
            {
                die("Invalid User Credentials");
            }

            // generate the valid response
            $A1 = md5($data['username'] . ':' . $realm . ':' . $users[$data['username']]);
            $A2 = md5($_SERVER['REQUEST_METHOD'].':'.$data['uri']);
            $valid_response = md5($A1.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);

            if ($data['response'] != $valid_response)
                die('Wrong Credentials!');

            // ok, valid username & password
            echo 'Your are logged in as: ' . $data['username'];

        }
    }
*/

    public function index()
    {
        // In Kohana, all views are loaded and treated as objects.
        $this->template->content = new View('dashboard');
        $this->template->schedule = self::schedule();
        $this->template->files = self::files();
        $this->template->database = self::database();
        $this->template->documents = self::documents();
        //$this->template->uploads = self::uploads();
        //$this->template->filelist = self::filelist();
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

    public function documents($date = NULL) {
        if(request::is_ajax()) $this->template = new View('ajax');

        $v = new View('documents');
        $snd = new Sound;
        $v->dates = $snd->retrieve_dates($date);

        if(request::is_ajax())
            $this->template->content = $v;
        else
            return $v->render();
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
            //      'Tobit', 'Judith',
                    'Esther',
            //      '1 Maccabees', '2 Maccabees',
                    'Job',
                    'Psalms', 'Proverbs', 'Ecclesiastes', 'Song of Songs',
            //      'Wisdom', 'Sirach',
                    'Isaiah', 'Jeremiah', 'Lamentations',
            //      'Baruch',
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
            $record->disk = 11; // TODO year needs to be based on date
        }

        if($_REQUEST['type'] == 'Sermons') {
			if (1 === (int) date('W', strtotime('Jan 1'))) {
				$record->track = date('W',strtotime($_REQUEST['date']));
			} else {
				$record->track = date('W',strtotime($_REQUEST['date'])) - 1;
			}
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

    private function http_digest_parse($txt)
    {
        // protect against missing data
        $needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
        $data = array();
        $keys = implode('|', array_keys($needed_parts));

        preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

        foreach ($matches as $m) {
            $data[$m[1]] = $m[3] ? $m[3] : $m[4];
            unset($needed_parts[$m[1]]);
        }

        return $needed_parts ? false : $data;
    }
}
