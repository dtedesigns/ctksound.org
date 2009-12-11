<?php
/* vim: set noet fenc= ff=unix sts=0 sw=4 ts=4 : */
/* SVN FILE: $Id: php 135 2009-06-10 02:20:13Z kevin $ */

class Sound_Controller extends Controller
{
	public function index() {
		url::redirect('/dashboard/');
		//return Dashboard_Controller::index();
	}

	public function __call($method, $arguments) {
		Dashboard_Controller::__call($method, $arguments);
	}
}
