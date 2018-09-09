<?php

class Auth extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    function login()
    {
        $this->load->helper('form');
        $data = [
            'page_title' => 'Login',
            'title' => 'Login'
        ];
        $this->load->view('templates/login_page', $data);
    }
}