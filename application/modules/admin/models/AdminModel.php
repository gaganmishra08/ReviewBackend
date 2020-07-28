<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AdminModel extends CI_Model
{
  public function getUserCounts(){
    return $this->db->select('count(*) as user_count')->get_where('users', array('active'=>1))->row_array();
  }
  public function getCompaniesCount(){
    return $this->db->select('count(*) as company_count')->get_where('users', array('active'=>2))->row_array();
  }
    public function getSettings()
    {
        return $this->db->select('*')->get_where('app_settings', array( 'id' => 1 ))->row();
    }

    public function getUsersList(){
      return $this->db->select('id, username, first_name, last_name, company, email')->get_where('users', array('active'=>1))->result_array();
    }

    public function saveSettings($data)
    {
        $this->db->where('id', "1");
        return $this->db->update('app_settings', $data);
    }
    public function insertCompany($data){
      $company = $this->db->select('id')->get_where('users', array('company'=>$data['company']))->row();
      if($company){

      }else{
        $this->db->insert("users", $data);
        $id = $this->db->insert_id();
        $this->db->insert("users_groups", array('user_id' => $id, 'group_id'=>'3'));
      }

    }
    public function insertCollage($collage)
    {
        $sql = $this->db->insert_string('collages_list', $collage) . ' ON DUPLICATE KEY UPDATE Institution_ID=LAST_INSERT_ID(Institution_ID)';
        $this->db->query($sql);
        $id = $this->db->insert_id();
    }
    public function saveSkill($name)
    {
        $data = array('name'=>$name, 'description'=>'');
        $this->db->insert('skills', $data);
    }
    public function getFeedbacks()
    {
        return $this->db->select('*')->get_where('users_feedback', array( 'reviewed' => 0 ))->result_array();
    }
    public function markfeedbackread($id)
    {
        $this->db->where('id', "1");
        return $this->db->update('users_feedback', array('reviewed'=>1));
    }
}
