<?php
defined('BASEPATH') or exit('No direct script access allowed');
//require APPPATH . '/libraries/REST_Controller.php';
class Auth extends API_Controller
{
    public function login_post()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method != 'POST') {
            json_output(400, array('status' => 400,'message' => 'Bad request.'));
        } else {
            $this->load->model('MyModel');
            $check_auth_client = $this->MyModel->check_auth_client();
            if ($check_auth_client == true) {
                //$params = json_decode(file_get_contents('php://input'), TRUE);
                $params = json_decode(file_get_contents('php://input'));
                $username = $params->username;
                $password = $params->password;

                $response = $this->MyModel->login($username, $password);
                $this->response(['status' => true, "response"=>$response]);
            }
        }
    }

    public function logout_post()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method != 'POST') {
            json_output(400, array('status' => 400,'message' => 'Bad request.'));
        } else {
            $this->load->model('MyModel');
            $check_auth_client = $this->MyModel->check_auth_client();
            if ($check_auth_client == true) {
                $response = $this->MyModel->logout();
                $this->response(['status' => true, "response"=>$response]);
            }
        }
    }
}
