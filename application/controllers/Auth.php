<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function index()
	{
		$this->load->view('welcome_message');
	}

    public function login() {
        $this->load->view('login');
    }

    public function logout()
    {
        session_destroy();
		redirect(base_url().'auth/login.html');
    }
}
