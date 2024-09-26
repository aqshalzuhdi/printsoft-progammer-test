<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_ajax extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model("AuthModel");
        $this->Foglobal->CleanParamSqlInjection();
    }
    
    public function index() {
        $act = $this->input->post("act");
        $this->req  = $this->input->post("req");
        $this->form = $this->input->post("form");
        print_r($this->$act());
    }

    private function login() {
        $Request = $this->AuthModel->LoginProses($this->form);
        return $Request;
    }

    private function register() {
        $Request = $this->AuthModel->RegisterProses($this->form);
        return $Request;
    }

    private function emptysession() {
        session_start(); 
        session_destroy();
        unset($_SESSION);
        session_regenerate_id(true);
    }
}
