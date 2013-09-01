<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 *  User Controller
 */
class User_Controller extends Template_Controller {

    // Set the name of the template to use
    public $template = 'welcome_content';

    public function __construct()
    {
        parent::__construct();
        //var_dump($_REQUEST);
        //var_dump($_SESSION);
    }

    public function index($role = "")
    {
        //$this->force_login('admin');
        $this->login($role);
    }

    public function login($role = "")
    {
        $form = $_POST;

        if ($_POST)
        {
            // Load the user
            $users = Kohana::config('auth.users');
            Auth::instance()->login($form['username'], $form['password'],true);
            //Auth::instance()->force_login($form['username']);

            //$this->templates->message('Invalid Credentials.');
        }

        if (Auth::instance()->logged_in($role))
        {
            // return to page where login was called
            //if ($this->session->get('requested_url')) {
            //  url::redirect($this->session->get('requested_url'));
            //} else {
                url::redirect('/dash/');
            //}
        }
        else
        {
            if (Auth::instance()->logged_in()) {
                $this->template->title = "No Access";
                $this->template->content = new View('/user/noaccess');
            } else {
                $this->template->title = "Please Login";
                $this->template->content = new View('/user/login');
            }
        }

    }

    public function logout()
    {
        Auth::instance()->logout();
        url::redirect('dash');
    }
}
