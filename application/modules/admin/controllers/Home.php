<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Admin_Controller {

	public function index()
	{
		$this->load->model('user_model', 'users');

		$this->load->model('AdminModel');
		$this->mViewData['user_count'] = $this->AdminModel->getUserCounts();

		$this->mViewData['company_count'] = $this->AdminModel->getCompaniesCount();
		$this->render('home');
	}
}
