<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 *  Command Line Interface Controller
 */
class Cli_Controller extends Template_Controller {

	// Set the name of the template to use
	public $template = 'template';

	public function index() {
		$this->auto_render = FALSE;

		echo "This cannot be called directly.\n";
	}

	public function sermon($date = NULL) {
		$this->auto_render = FALSE;

		if($date) $date = date('Y-m-d', strtotime($date));
		else $date = Sound::last_sunday();

		$sermon = Doctrine_Query::create()
			->from('Sermons')
			->where("date = ? AND type= ?", array($date, 'sermon'))
			->execute()
			->getFirst();

		echo json_encode($sermon->toArray());
	}

	public function ace($date = NULL) {
		$this->auto_render = FALSE;

		if($date) $date = date('Y-m-d', strtotime($date));
		else $date = Sound::last_sunday();

		$sermon = Doctrine_Query::create()
			->from('Sermons')
			->where("date = ? AND type= ?", array($date, 'ace'))
			->execute()
			->getFirst();

		echo json_encode($sermon->toArray());
	}

	public function portrait($date = NULL) {
		$this->auto_render = FALSE;

		if($date) $date = date('Y-m-d', strtotime($date));
		else $date = Sound::last_sunday();

		$sermon = Doctrine_Query::create()
			->from('Sermons')
			->where("date = ? AND type= ?", array($date, 'portrait'))
			->execute()
			->getFirst();

		echo json_encode($sermon->toArray());
	}

	public function dedication($date = NULL) {
		$this->auto_render = FALSE;

		if($date) $date = date('Y-m-d', strtotime($date));
		else $date = Sound::last_sunday();

		$sermon = Doctrine_Query::create()
			->from('Sermons')
			->where("date = ? AND type= ?", array($date, 'dedication'))
			->execute()
			->getFirst();

		echo json_encode($sermon->toArray());
	}
}
