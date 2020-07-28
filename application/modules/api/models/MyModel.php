<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MyModel extends CI_Model
{
    public $client_service = "client_service";
    //var $auth_key       = "secret_key";
    public $auth_key       = "dRJn703yFq0q65ngD5PMziK7j4wZEOQHvpTsdKOI";

    public function check_auth_client()
    {
        $client_service = $this->input->get_request_header('Client-Service', true);
        $auth_key  = $this->input->get_request_header('Auth-Key', true);
        if ($client_service == $this->client_service && $auth_key == $this->auth_key) {
            return true;
        } else {
            return false;
        }
    }

    public function login($username, $password)
    {
        $q  = $this->db->select('password,id, active')->from('users')->where('email', $username)->get()->row();
        if ($q == "") {
            return array('status' => false,'message' => 'Invalid login credentials. Check your login details and Try again.');
        } elseif ($q->active == 0) {
            return array('status' => false,'message' => 'Your account has been blocked by administrator for Terms and Privacy violence. Contact support for more info.', 'email'=>"");
        } else {
            $hashed_password = $q->password;
            $id              = $q->id;
            if (hash_equals($hashed_password, crypt($password, $hashed_password))) {
                return $this->generatetoken($id);
            } else {
                return array('status' => false,'message' => 'Incorrect Password. Check your password and Try again.');
            }
        }
    }
    public function loginSocialAccount($username)
    {
        $q  = $this->db->select('password,id, active')->from('users')->where('username', $username)->get()->row();
        if ($q == "") {
            return array('status' => false,'message' => 'Username not found.');
        } elseif ($q->active == 0) {
            return array('status' => false,'message' => 'Your account has been blocked by administrator for Terms and Privacy violence. Contact support for more info.', 'email'=>"");
        } else {
            $id              = $q->id;
            return $this->generatetoken($id);
        }
    }
    public function generatetoken($id)
    {
        $last_login = date('Y-m-d H:i:s');
        $token = substr(md5(rand()), 0, 20);
        $expired_at = date("Y-m-d H:i:s", strtotime('+12 hours'));
        $this->db->trans_start();
        $this->db->where('id', $id)->update('users', array('last_login' => $last_login));
        $this->db->insert('users_authentication', array('users_id' => $id,'token' => $token,'expired_at' => $expired_at));
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return array('status' => false,'message' => 'Internal server error.');
        } else {
            $this->db->trans_commit();
            return array('status' => true,'message' => 'Successfully login.','id' => $id, 'token' => $token, 'expired_at' => $expired_at);
        }
    }

    public function logout()
    {
        $users_id  = $this->input->get_request_header('User-ID', true);
        $token     = $this->input->get_request_header('Token', true);
        $status = $this->db->where('users_id', $users_id)->where('token', $token)->delete('users_authentication');
        return array('status' => true,'message' => 'Successfully logout.');
    }

    public function auth()
    {
        $users_id  = $this->input->get_request_header('User-ID', true);
        $token     = $this->input->get_request_header('Token', true);
        $q  = $this->db->select('users_authentication.expired_at, users.active')->from('users_authentication')
        ->join('users', 'users_authentication.users_id = users.id', 'left')
        ->where('users_authentication.users_id', $users_id)
        ->where('users_authentication.token', $token)->get()->row();
        if ($q == "") {
            return false;
        } else {
            if ($q->expired_at < date('Y-m-d H:i:s')) {
                return array('status' => false,'message' => 'Your session has been expired.');
            }
            if ($q->active == 0) {
                return array('status' => false,'message' => 'Your account has been blocked by administrator for Terms and Privacy violence. Contact support for more info.', 'email'=>"");
            } else {
                $updated_at = date('Y-m-d H:i:s');
                $expired_at = date("Y-m-d H:i:s", strtotime('+12 hours'));
                $this->db->where('users_id', $users_id)->where('token', $token)->update('users_authentication', array('expired_at' => $expired_at));

                return array('status' => true,'message' => 'Authorized.');
            }
        }
    }

    public function book_all_data()
    {
        return $this->db->select('id,title,author')->from('books')->order_by('id', 'desc')->get()->result();
    }

    public function book_detail_data($id)
    {
        return $this->db->select('id,title,author')->from('books')->where('id', $id)->order_by('id', 'desc')->get()->row();
    }

    public function book_create_data($data)
    {
        $this->db->insert('books', $data);
        return array('status' => 201,'message' => 'Data has been created.');
    }

    public function book_update_data($id, $data)
    {
        $this->db->where('id', $id)->update('books', $data);
        return array('status' => 200,'message' => 'Data has been updated.');
    }

    public function book_delete_data($id)
    {
        $this->db->where('id', $id)->delete('books');
        return array('status' => 200,'message' => 'Data has been deleted.');
    }
}
