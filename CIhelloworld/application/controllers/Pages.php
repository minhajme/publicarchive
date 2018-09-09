<?php

class Pages extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('sample_model');//now you get $this->sample_model
    }

    public function view($page = 'home')
    {
        $data['title'] = ucfirst($page);
        $this->load->view('templates/header', $data);
        $this->load->view('pages/home', $data);
        $this->load->view('templates/footer', $data);
    }
}