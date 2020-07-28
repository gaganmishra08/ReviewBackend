<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Demo Controller with Swagger annotations
 * Reference: https://github.com/zircote/swagger-php/
 */
class Account extends API_Controller
{
    public function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('MyModel');
        $this->load->model('UserAPIModel');
        $this->load->model('ReviewAPIModel');
        $this->load->library('system_message');
        $this->load->library('email_client');
    }

    public function requestForgotPassword_post()
    {
        $this->load->library('ion_auth');

        $params = json_decode(file_get_contents('php://input'));
        if (isset($params->email)) {
            $identity = $params->email;
            $user = $this->ion_auth->forgotten_password($identity);
            if ($user) {
                // if ($this->config->item('use_ci_email', 'ion_auth')) {
                //     // send email using Email Client library
                //     $subject = $this->lang->line('email_forgotten_password_subject');
                //     $email_view = $this->config->item('email_templates', 'ion_auth').$this->config->item('email_forgot_password', 'ion_auth');
                //     $this->email_client->send($identity, $subject, $email_view, $user);
                // }
                // success
                $this->response(['status' => true, "message"=>"Forgot password instruction has been sent to your email address."]);
            } else {
                $this->response(['status' => false, "message"=>"Email address not found."]);
            }
        }
    }
}
