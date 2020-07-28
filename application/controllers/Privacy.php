<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Home page
 */
class Privacy extends MY_Controller
{
    public function index()
    {
	$this->load->model('Admin_user_model');
	$settings = $this->Admin_user_model->getSettings();
	
	$data['settings'] = $settings->privacy;

        $this->load->view('privacy', $data);
    }
}
