<?php
defined('BASEPATH') or exit('No direct script access allowed');

class JobModel extends CI_Model
{
    public function create($data)
    {
        $this->db->insert('jobs', $data);
        return $this->db->insert_id();
    }

    public function getJobDetail($user_id, $job_id)
    {
        $this->db->select('IF(job_saved.job_id IS NOT NULL , TRUE , FALSE) as is_saved, jobs.*, users.first_name, users.company, users.profile_url', false);
        $this->db->join('users', 'jobs.user_id = users.id', 'left');
        $this->db->join('job_saved', 'job_saved.job_id = jobs.id AND job_saved.user_id ='.$user_id, 'left');
        $this->db->where("jobs.active", "1");
        $this->db->where("jobs.id", $job_id);
        return $this->db->get('jobs')->row();
    }
    public function updateJobs($id, $update)
    {
        $this->db->where('id', $id);
        return $this->db->update('jobs', $update);
    }
    public function getJobApplicationDetail($user_id, $job_id)
    {
        return $this->db->select('jobs_applications.status')
            ->get_where('jobs_applications', array('user_id'=>$user_id, 'job_id'=>$job_id))->row();
    }

    public function getJobsApplications($job_id)
    {
        $this->db->select('jobs_applications.created_on, jobs_applications.document_url, users.id, users.email, users.company, users.profile_url, users.website, users.user_type, users.first_name');
        $this->db->join('users', 'jobs_applications.user_id = users.id', 'left');
        $this->db->where('jobs_applications.job_id', $job_id);
        return $this->db->get('jobs_applications')->result_array();
    }

    public function getJobs($company_id = null, $user_id)
    {
        $this->db->select('IF(job_saved.job_id IS NOT NULL , TRUE , FALSE) as is_saved, jobs.*, users.first_name, users.company, users.profile_url', false);
        $this->db->join('users', 'jobs.user_id = users.id', 'left');
        $this->db->join('job_saved', 'job_saved.job_id = jobs.id AND job_saved.user_id ='.$user_id, 'left');

        $this->db->where("jobs.active", "1");
        $this->db->where("jobs.active", "1");
        $this->db->order_by("jobs.created_at", "desc");
        if ($company_id) {
            $this->db->where("jobs.user_id", $company_id);
        } else {
            $this->db->join('users_blocked', 'users_blocked.user_id ='.$user_id.' AND users_blocked.to_user_id=users.id', 'left');
            $this->db->where('users_blocked.id IS NULL', null, false);
        }
        return $this->db->get('jobs')->result_array();
    }

    public function getJobNearLocation($lat, $long, $user_id, $radius)
    {
        $distance_filter = ',(3959 * acos(cos(radians('.$lat.')) * cos(radians(jobs.latitude)) * cos( radians(jobs.longitude) - radians('.$long.')) + sin(radians('.$lat.')) * sin(radians(jobs.latitude)))) AS distance';
        $this->db->select('IF(job_saved.job_id IS NOT NULL , TRUE , FALSE) as is_saved, jobs.*, users.first_name, users.company, users.profile_url'.$distance_filter, false);
        $this->db->join('users', 'jobs.user_id = users.id', 'left');
        $this->db->join('job_saved', 'job_saved.job_id = jobs.id AND job_saved.user_id ='.$user_id, 'left');
        $this->db->where("jobs.active", "1");
        $this->db->having("distance < ".$radius);

        $this->db->join('users_blocked', 'users_blocked.user_id ='.$user_id.' AND users_blocked.to_user_id=users.id', 'left');
        $this->db->where('users_blocked.id IS NULL', null, false);

        return $this->db->get('jobs')->result_array();
    }
    public function findJobs($search, $user_id)
    {
        $this->db->select('IF(job_saved.job_id IS NOT NULL , TRUE , FALSE) as is_saved, jobs.*, users.first_name, users.company, users.profile_url', false);
        $this->db->join('users', 'jobs.user_id = users.id', 'left');
        $this->db->join('job_saved', 'job_saved.job_id = jobs.id AND job_saved.user_id ='.$user_id, 'left');
        $this->db->where("jobs.active", "1");
        $this->db->like(array('jobs.title' => $search));
        $this->db->or_like(array('jobs.description' => $search));

        $this->db->join('users_blocked', 'users_blocked.user_id ='.$user_id.' AND users_blocked.to_user_id=users.id', 'left');
        $this->db->where('users_blocked.id IS NULL', null, false);

        return $this->db->get('jobs')->result_array();
    }

    // save jobs
    public function getSavedJobs($user_id)
    {
        $this->db->select('1 as is_saved, jobs.*, users.first_name, users.company, users.profile_url', false);
        $this->db->join('jobs', 'jobs.id = job_saved.job_id', 'left');
        $this->db->join('users', 'jobs.user_id = users.id', 'left');
        $this->db->where("jobs.active", "1");
        $this->db->where("job_saved.user_id", $user_id);
        return $this->db->get('job_saved')->result_array();
    }

    public function removeSavedJob($user_id, $job_id)
    {
        return $this->db->delete('job_saved', array('user_id'=>$user_id, 'job_id'=>$job_id));
    }

    public function saveJob($user_id, $job_id)
    {
        return $this->db->insert('job_saved', array('user_id'=>$user_id, 'job_id'=>$job_id));
    }

    // applied jobs
    public function getMyJobs($user_id)
    {
        $this->db->select('IF(job_saved.job_id IS NOT NULL , TRUE , FALSE) as is_saved, jobs.*, users.first_name, users.company, users.profile_url', false);
        $this->db->join('jobs', 'jobs.id = jobs_applications.job_id', 'left');
        $this->db->join('users', 'jobs.user_id = users.id', 'left');
        $this->db->join('job_saved', 'job_saved.job_id = jobs.id AND job_saved.user_id ='.$user_id, 'left');
        $this->db->where("jobs_applications.user_id", $user_id);
        return $this->db->get('jobs_applications')->result_array();
    }

    public function jobActions($status)
    {
        return $this->db->delete('jobs_applications', array('status'=>$status));
    }
    public function cancelJobApplication($user_id, $job_id){
        $this->db->where('user_id', $user_id);
        $this->db->where('job_id', $job_id);
        return $this->db->delete('jobs_applications');   
    }

    public function applyJobs($user_id, $job_id, $file_url = "")
    {
        return $this->db->insert('jobs_applications', array('user_id'=>$user_id, 'job_id'=>$job_id, 'document_url' => $file_url));
    }
}
