<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_builder');
        $this->load->model('AdminModel');
    }

    // Frontend User CRUD
    public function index()
    {
        // $this->add_script('https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js', true, 'head');
        // $this->add_script('https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js', true, 'head');
        //
        // $this->add_stylesheet('http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
        // $this->add_stylesheet('https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css');
        //
        // $data = $this->AdminModel->getUsersList();
        // $this->mViewData['data'] = $data;
        //
        // $this->mPageTitle = "Users";
        // $this->render('user/userslist');
        $crud = $this->generate_crud('users');
        $crud->columns('groups', 'username', 'email', 'first_name', 'last_name', 'active');
        $crud->where('user_type', '1');
        $crud->or_where('user_type', '2');

        $this->unset_crud_fields('ip_address', 'last_login');

        // only webmaster and admin can change member groups
        if ($crud->getState()=='list' || $this->ion_auth->in_group(array('webmaster', 'admin'))) {
            $crud->set_relation_n_n('groups', 'users_groups', 'groups', 'user_id', 'group_id', 'name');
        }

        // only webmaster and admin can reset user password
        if ($this->ion_auth->in_group(array('webmaster', 'admin'))) {
            $crud->add_action('Reset Password', '', 'admin/user/reset_password', 'fa fa-repeat');
        }

        // disable direct create / delete Frontend User
        $crud->unset_add();
        $crud->unset_delete();

        $this->mPageTitle = 'Users';
        $this->render_crud();
    }
    public function feedbacks()
    {
        $this->add_script('https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js', true, 'head');
        $this->add_script('https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js', true, 'head');

        $this->add_stylesheet('http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
        $this->add_stylesheet('https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css');//

        // $this->load->model('user_model', 'users');
        // $this->mViewData['count'] = array(
        //     'users' => $this->users->count_all(),
        // );
        // $this->render('home');
        $data = $this->AdminModel->getFeedbacks();
        $this->mViewData['data'] = $data;

        $this->mPageTitle = "Feedbacks";
        $this->render('feedback_list');
    }
    public function feedback_read($id)
    {
        $this->AdminModel->markfeedbackread($id);
        redirect('admin/user/feedbacks');
    }
    // Create Frontend User
    public function create()
    {
        $form = $this->form_builder->create_form();

        if ($form->validate()) {
            // passed validation
            $username = $this->input->post('username');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $identity = empty($username) ? $email : $username;
            $additional_data = array(
                'first_name'	=> $this->input->post('first_name'),
                'last_name'		=> $this->input->post('last_name'),
            );
            $groups = $this->input->post('groups');

            // [IMPORTANT] override database tables to update Frontend Users instead of Admin Users
            $this->ion_auth_model->tables = array(
                'users'				=> 'users',
                'groups'			=> 'groups',
                'users_groups'		=> 'users_groups',
                'login_attempts'	=> 'login_attempts',
            );

            // proceed to create user
            $user_id = $this->ion_auth->register($identity, $password, $email, $additional_data, $groups);
            if ($user_id) {
                // success
                $messages = $this->ion_auth->messages();
                $this->system_message->set_success($messages);

                // directly activate user
                $this->ion_auth->activate($user_id);
            } else {
                // failed
                $errors = $this->ion_auth->errors();
                $this->system_message->set_error($errors);
            }
            refresh();
        }

        // get list of Frontend user groups
        $this->load->model('group_model', 'groups');
        $this->mViewData['groups'] = $this->groups->get_all();
        $this->mPageTitle = 'Create User';

        $this->mViewData['form'] = $form;
        $this->render('user/create');
    }

    // User Groups CRUD
    public function group()
    {
        $crud = $this->generate_crud('groups');
        $this->mPageTitle = 'User Groups';
        $this->render_crud();
    }

    // Frontend User Reset Password
    public function reset_password($user_id)
    {
        // only top-level users can reset user passwords
        $this->verify_auth(array('webmaster', 'admin'));

        $form = $this->form_builder->create_form();
        if ($form->validate()) {
            // pass validation
            $data = array('password' => $this->input->post('new_password'));

            // [IMPORTANT] override database tables to update Frontend Users instead of Admin Users
            $this->ion_auth_model->tables = array(
                'users'				=> 'users',
                'groups'			=> 'groups',
                'users_groups'		=> 'users_groups',
                'login_attempts'	=> 'login_attempts',
            );

            // proceed to change user password
            if ($this->ion_auth->update($user_id, $data)) {
                $messages = $this->ion_auth->messages();
                $this->system_message->set_success($messages);
            } else {
                $errors = $this->ion_auth->errors();
                $this->system_message->set_error($errors);
            }
            refresh();
        }

        $this->load->model('user_model', 'users');
        $target = $this->users->get($user_id);
        $this->mViewData['target'] = $target;

        $this->mViewData['form'] = $form;
        $this->mPageTitle = 'Reset User Password';
        $this->render('user/reset_password');
    }
}
