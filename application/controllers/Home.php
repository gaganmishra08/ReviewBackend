<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Home page
 */
class Home extends MY_Controller
{
    public function index()
    {
        $this->load->view('home');
    }
}
