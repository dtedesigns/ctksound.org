<?php
/* vim: set noet fenc= ff=unix sts=0 sw=4 ts=4 : */
/* SVN FILE: $Id: php 135 2009-06-10 02:20:13Z kevin $ */

class Rss_Controller extends Template_Controller {
	public function index() {
		$this->template = new View('rss');
	}

}
