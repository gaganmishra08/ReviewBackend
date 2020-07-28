<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UserAPIModel extends CI_Model
{
    public function getSettings()
    {
        return $this->db->select('*')
            ->get_where('app_settings', array('id'=>1))->row();
    }

    public function create_account($data)
    {
        $this->db->trans_start();
        $this->db->insert('users', $data);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function checkAccountExist($username, $email)
    {
        $this->db->select('id, sign_in_type');
        $this->db->from('users');
        if ($username) {
            $this->db->where('username', $username);
        }
        if ($email) {
            $this->db->or_where('email', $email);
        }
        $q = $this->db->get();
        return $q->row();
    }
    public function getMessageGroupStatus($user_id, $group_id)
    {
        return $this->db->get_where('messages_group_members', array('user_id'=>$user_id, 'group_id'=>$group_id))->row();
    }
    public function searchUser($search, $company_only, $users_only = false)
    {
        $this->db->select('id, email, company, profile_url, website, user_type, first_name, last_name');
        $this->db->from('users');

        if ($company_only) {
            $this->db->where('user_type', '3');
            $this->db->where("(first_name LIKE '%".$search."%' OR company LIKE '".$search."%')", null, false);
        } elseif ($users_only) {
            $this->db->where("(user_type = 1 OR user_type = 2)", null, false);
            $this->db->where("(first_name LIKE '%".$search."%' OR company LIKE '".$search."%')", null, false);
        } else {
            $this->db->or_like(array('first_name' => $search, 'company' => $search));
        }
        $this->db->limit('100', 0);

        $query = $this->db->get();
        return $query->result_array();
        print_r($this->db->last_query());
    }

    public function searchCollage($search)
    {
        $this->db->select('collages_list.Institution_Name');
        $this->db->from('collages_list');
        $this->db->group_by('Institution_Name');
        $this->db->or_like(array('Institution_Name' => $search, 'Campus_Name' => $search));
        $query = $this->db->get();
        return $query->result_array();
    }

    public function searchCompany($search)
    {
        $this->db->select('id, email, company, profile_url, website, user_type, first_name, last_name, title');
        $this->db->from('users');
        $this->db->or_like(array('first_name' => $search, 'company' => $search));
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getShortUserInfo($user_id)
    {
        return $this->db->select('id, title, username, email, company, profile_url, website, user_type, first_name, last_name, sign_in_type, phone, about, city, state, country, zip, address, location, activation_code')
            ->get_where('users', array('id'=>$user_id))->row();
    }
    public function getFullUserInfo($user_id)
    {
        return $this->db->select('id, title, collage_name, collage_year, username, email, company, profile_url, website, user_type, first_name, last_name, sign_in_type, phone, about, city, state, country, zip, address, location, activation_code')
            ->get_where('users', array('id'=>$user_id))->row();
    }
    public function getCompaniesNearLocation($user_id, $lat, $long, $radius)
    {
        $distance_filter = ',(3959 * acos(cos(radians('.$lat.')) * cos(radians(latitude)) * cos( radians(longitude) - radians('.$long.')) + sin(radians('.$lat.')) * sin(radians(latitude)))) AS distance';
        $this->db->select('id, latitude, longitude, username, email, company, profile_url, website, user_type, first_name, last_name, title, sign_in_type, phone, about, city, state, country, zip, address, location'.$distance_filter, false);
        //$this->db->where("active", "1");
        $this->db->having("distance < ".$radius);
        // $this->db->join('users_blocked', 'users_blocked.user_id ='.$user_id.' AND users_blocked.to_user_id=users.id', 'left');
        // $this->db->where('users_blocked.id IS NULL', null, false);
        return $this->db->get_where('users', array('user_type'=>'3', 'active'=>1))->result_array();
    }
    public function verifyemailaddress($code)
    {
        $this->db->where('activation_code', $code);
        return $this->db->update('users', array('activation_code' => ''));
    }

    public function getUserToken($user_id)
    {
        return $this->db->select('id, gcm_token')
            ->get_where('users', array('id'=>$user_id))->row();
    }

    public function updateUserDetail($id, $update)
    {
        $this->db->where('id', $id);
        return $this->db->update('users', $update);
    }

    // certificate
    public function addcertificate($data)
    {
        $this->db->insert("users_certificates", $data);
        return $this->db->insert_id();
    }
    public function getusercertificates($user_id)
    {
        return $this->db->get_where('users_certificates', array('user_id'=>$user_id))->result_array();
    }
    public function updateusercertificate($id, $update)
    {
        $this->db->where('id', $id);
        return $this->db->update('users_certificates', $update);
    }
    public function deleteusercertificate($id)
    {
        return $this->db->delete('users_certificates', array('id'=>$id));
    }

    // experience
    public function addExperience($data)
    {
        $this->db->insert("users_experience", $data);
        return $this->db->insert_id();
    }
    public function getuserExperiences($user_id)
    {
        return $this->db->get_where('users_experience', array('user_id'=>$user_id))->result_array();
    }
    public function updateuserExperience($id, $update)
    {
        $this->db->where('id', $id);
        return $this->db->update('users_experience', $update);
    }
    public function deleteuserExperience($id)
    {
        return $this->db->delete('users_experience', array('id'=>$id));
    }

    // messages
    public function getMessagesConversation($user_id)
    {
        $q = "SELECT messages.*, users.first_name, users.last_name, users.company, users.profile_url, users.user_type FROM messages
    INNER JOIN (SELECT from_id, max(Id) as maxId FROM messages WHERE `to_id` = ".$user_id." GROUP BY from_id)T
    ON messages.Id = T.maxId
    LEFT JOIN users ON users.id = messages.from_id";
        return $this->db->query($q)->result_array();
    }
    public function getGroupMessagesConversation($user_id)
    {
        $q = "SELECT messages.*, users.first_name,users.last_name, users.company, users.profile_url,  users.user_type FROM messages
    INNER JOIN (SELECT from_id, max(Id) as maxId FROM messages WHERE `to_id` = ".$user_id." GROUP BY from_id)T
    ON messages.Id = T.maxId
    LEFT JOIN users ON users.id = messages.from_id WHERE NOT messages.group_id = 0";
        return $this->db->query($q)->result_array();
    }

    public function getUserGroups($user_id)
    {
        $this->db->select('messages_group.*, messages_group_members.status, users.first_name, users.company, users.profile_url', false);
        $this->db->join('messages_group', 'messages_group_members.group_id = messages_group.id', 'left');
        $this->db->join('users', 'messages_group.user_id = users.id', 'left');
        $this->db->where("messages_group_members.user_id", $user_id);
        //$this->db->or_where("messages_group.user_id", $user_id);
        return $this->db->get('messages_group_members')->result_array();
    }

    public function addGroupMember($data)
    {
    }

    public function getMyGroups($user_id)
    {
        $this->db->select('messages_group.*, users.first_name, users.title, users.company, users.profile_url', false);
        $this->db->join('users', 'messages_group.user_id = users.id', 'left');
        $this->db->where("messages_group.user_id", $user_id);
        //$this->db->or_where("messages_group.user_id", $user_id);
        return $this->db->get('messages_group')->result_array();
        //return $this->db->get_where('messages_group', array('user_id'=>$user_id))->result_array();
    }

    public function updateGroupStatus($user_id, $group_id, $status)
    {
        $this->db->where(array('user_id'=>$user_id, 'group_id'=>$group_id));
        $this->db->update('messages_group_members', array('status'=>$status));
    }
    public function getTrendingUsers()
    {
        $this->db->select('id, last_login, title, collage_year, email, profile_url, website, user_type, first_name, last_name, collage_name, city, location');

        $this->db->from('users');
        $this->db->where('user_type !=', 3);
        $this->db->order_by('last_login');
        return $this->db->get()->result_array();
    }
    public function getGroupMembers($group_id)
    {
        $this->db->select('messages_group_members.user_id as user_id, users.id, users.user_type, users.title, users.first_name, users.last_name, users.company, users.profile_url', false);
        $this->db->join('users', 'messages_group_members.user_id = users.id', 'left');
        $this->db->where("messages_group_members.group_id", $group_id);
        //$this->db->or_where("messages_group.user_id", $user_id);
        return $this->db->get('messages_group_members')->result_array();
    }

    public function removeGroupMember($group_id, $user_id)
    {
        return $this->db->delete('messages_group_members', array('group_id'=>$group_id, 'user_id'=>$user_id));
    }

    public function getMessages($from_id, $to_id, $group_id = null)
    {
        $this->db->select('messages.*, users.first_name, users.last_name', false);
        if ($group_id) {
            $this->db->where('group_id', $group_id);
        } else {
            $this->db->where("(from_id =".$from_id." AND to_id=".$to_id.") OR (from_id = ".$to_id." AND to_id=".$from_id.")", null, false);
        }
        $this->db->join('users', 'messages.from_id = users.id', 'left');
        return $this->db->get('messages')->result_array();
        //return $this->db->get_where('messages', array('from_id'=>$from_id, 'to_id'=>$to_id))->result_array();
    }

    public function saveMessage($data)
    {
        $this->db->insert('messages', $data);
        return $this->db->insert_id();
    }

    //skills
    public function getAllSkills()
    {
        return $this->db->get('skills')->result_array();
    }
    public function getUserSkills($user_id)
    {
        $this->db->select('skills.*');
        $this->db->join('skills', 'users_skills.skill_id = skills.id', 'left');
        $this->db->from('users_skills');
        $this->db->where(array('users_skills.user_id' => $user_id));
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getUserSkillsByName($user_id)
    {
        $this->db->select('skills.name');
        $this->db->join('skills', 'users_skills.skill_id = skills.id', 'left');
        $this->db->from('users_skills');
        $this->db->where(array('users_skills.user_id' => $user_id));
        $query = $this->db->get();
        return $query->result_array();
    }
    public function findskills($search)
    {
        $this->db->select('skills.*');
        $this->db->from('skills');
        // $this->db->where('name like=', $search.'%');
        $this->db->like('name', $search, 'after');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function removeUserSkill($user_id, $skill_id)
    {
        return $this->db->delete('users_skills', array('user_id'=>$user_id, 'skill_id'=>$skill_id));
    }

    // master table API

    public function insertItem($table_name, $data)
    {
        $this->db->insert($table_name, $data);
        return $this->db->insert_id();
    }

    public function checkDataExist($table_name, $data)
    {
        return $this->db->get_where($table_name, json_decode(json_encode($data), true))->result_array();
    }

    public function updateItem($table_name, $data)
    {
        return $this->db->update($table_name, $data);
    }

    public function deleteItem($table_name, $id)
    {
        return $this->db->delete($table_name, array('id'=>$id));
    }

    public function getItemList($table_name, $id=null, $where=null)
    {
        $this->db->select('*');
        $this->db->from($table_name);
        //$this->db->or_like(array('first_name' => $search, 'company' => $search));
        $query = $this->db->get();
        return $query->result_array();
    }

    // feedback
    public function savefeedback($user_id, $feedback, $rate)
    {
        $data = array('user_id' => $user_id, 'feedback' => $feedback, 'rated'=> $rate);
        $this->db->insert('users_feedback', $data);
    }

    // user blocked
    public function blockedUser($user_id, $to_user_id)
    {
        $has_blocked = $this->db->get_where("users_blocked", array("user_id"=>$user_id, "to_user_id"=>$to_user_id))->row();
        if ($has_blocked) {
            return true;
        } else {
            $data = array("user_id"=>$user_id, "to_user_id"=>$to_user_id);
            $this->db->insert('users_blocked', $data);
            return true;
        }
    }
    public function checkHasBlockedUser($user_id, $to_user_id)
    {
        $has_blocked = $this->db->get_where("users_blocked", array("user_id"=>$user_id, "to_user_id"=>$to_user_id))->row();
        if ($has_blocked) {
            return true;
        } else {
            return false;
        }
    }
    public function unblockedUser($user_id, $to_user_id)
    {
        $this->db->delete("users_blocked", array("user_id"=>$user_id, "to_user_id"=>$to_user_id));
    }
    public function getBlockedUsers($user_id)
    {
        return $this->db->get_where("users_blocked", array("user_id"=>$user_id))->result_array();
    }
}
