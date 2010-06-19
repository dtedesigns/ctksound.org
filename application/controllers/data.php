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
		$filenames = glob('/var/www/sound_demo/webroot/recordings/'.$date.'*.mp3');
		$snd->filename = $filenames[0];

		$filenames = glob('/var/www/sound_demo/webroot/labels/'.$date.'*.labels');
		if($as_file) {
			header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
			header("Content-Type: text/plain; charset: utf-8");
			header("Content-Disposition: attachment; filename=$date.cue");
		}
		echo $snd->gen_cue($filenames[0]);
	}

}
?>
