<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Demo Controller with Swagger annotations
 * Reference: https://github.com/zircote/swagger-php/
 */
class Jobs extends API_Controller
{
    public function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('MyModel');
        $this->load->model('UserAPIModel');
        $this->load->model('JobModel');
        $this->load->model('ReviewAPIModel');
    }

    public function create_post()
    {
        //if ($this->check_authentication(true)) 
        {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('payload'))) {
                $data = json_decode(json_encode($params->payload), true);
                $results = $this->JobModel->create($data);
                if ($results) {
                    $this->response(['status' => true, "id"=>$results, "message"=>"Job posted successfully."]);
                } else {
                    $this->response(['status' => false, "message"=>"Request failed. Please try again later."]);
                }
            }
        }
    }

    public function updateJob_post()
    {
        //if($this->check_authentication(TRUE))
        {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('id','info'))) {
                $info = $params->info;
                $info_array = json_decode(json_encode($info), true);
                $status = $this->JobModel->updateJobs($params->id, $info_array);
                $this->response(['status' => true, "message"=>"Job updated successfully."]);
            }
        }
    }

    public function getJobDetail_post()
    {
        //if($this->check_authentication(TRUE))
        {
            $params = json_decode(file_get_contents('php://input'));

            $this->load->model('ReviewAPIModel');

            $job_data = $this->JobModel->getJobDetail($params->user_id, $params->job_id);

            $user_info = $this->UserAPIModel->getShortUserInfo($job_data->user_id);
            $review = $this->ReviewAPIModel->getcompanyreview($user_info->id);
            $user_info->avg_rating = $review->avg_rating;
            $user_info->total_rating = $review->total_rating;

            $job_status = $this->JobModel->getJobApplicationDetail($params->user_id, $params->job_id);
            if ($job_status) {
                $job_status = $job_status->status;
            } else {
                $job_status = "0";
            }

            $applications = $this->JobModel->getJobsApplications($params->job_id);
            $results = array('job_data' => $job_data, 'user_info' => $user_info, 'status'=>$job_status, 'applications' => $applications);
            if ($results) {
                $this->response(['status' => true, "response"=>$results]);
            } else {
                $this->response(['status' => false, "message"=>"Job has been archived or cancelled."]);
            }
        }
    }

    public function getJobsCompanyNearLocation_post()
    {
        $params = json_decode(file_get_contents('php://input'));
        $lat = $params->lat;
        $long = $params->long;
        $user_id = $params->user_id;
        $radius = $params->radius;

        $jobs = $this->JobModel->getJobNearLocation($lat, $long, $user_id, $radius);
        $companies = $this->UserAPIModel->getCompaniesNearLocation($user_id, $lat, $long, $radius);
        for ($i=0; $i<COUNT($companies); $i++) {
            $review = $this->ReviewAPIModel->getAverageReviewForCompany($companies[$i]['id']);
            $companies[$i]['avg_rating'] = $review->avg_rating;
            $companies[$i]['total_rating'] = $review->total_rating;
        }
        $this->response(['status' => true, "companies"=>$companies, "jobs"=>$jobs]);
    }

    public function getJobsApplications_post()
    {
        //if($this->check_authentication(TRUE))
        {
            $params = json_decode(file_get_contents('php://input'));
            $results = $this->JobModel->getJobs($params->company_id, $params->user_id);

            $jobs = [];

            for ($i=0; $i<count($results); $i++) {
                $jobs[$i]['job'] = $results[$i];
                $jobs[$i]['applications'] = $this->JobModel->getJobsApplications($results[$i]['id']);
            }

            $this->response(['status' => true, "response"=>$jobs]);
        }
    }

    public function getJobs_post()
    {
        //if($this->check_authentication(TRUE))
        {
            $params = json_decode(file_get_contents('php://input'));
            if (isset($params->company_id)) {
                $results = $this->JobModel->getJobs($params->company_id, $params->user_id);
                $this->response(['status' => true, "response"=>$results]);
            } else {
                $results = $this->JobModel->getJobs(null, $params->user_id);
                $this->response(['status' => true, "response"=>$results]);
            }
        }
    }

    public function findJobs_post()
    {
        if ($this->check_authentication(true)) {
            $params = json_decode(file_get_contents('php://input'));
            $results = $this->JobModel->findJobs($params->search, $params->user_id);
            $this->response(['status' => true, "response"=>$results]);
        }
    }

    // save jobs
    public function saveJob_post()
    {
        if ($this->check_authentication(true)) {
            $params = json_decode(file_get_contents('php://input'));
            $results = $this->JobModel->saveJob($params->user_id, $params->job_id);
            $this->response(['status' => true, "message"=>"Job saved successfully."]);
        }
    }

    public function getSavedJobs_post()
    {
        if ($this->check_authentication(true)) {
            $params = json_decode(file_get_contents('php://input'));
            $results = $this->JobModel->getSavedJobs($params->user_id);
            $this->response(['status' => true, "response"=>$results]);
        }
    }

    public function removeSavedJob_post()
    {
        if ($this->check_authentication(true)) {
            $params = json_decode(file_get_contents('php://input'));
            $results = $this->JobModel->removeSavedJob($params->user_id, $params->job_id);
            $this->response(['status' => true, "message"=>"Job removed successfully."]);
        }
    }

    // applied jobs

    public function applyJobs_post()
    {
        if ($this->check_authentication(true)) {
            $params = json_decode(file_get_contents('php://input'));
            $results = $this->JobModel->applyJobs($params->user_id, $params->job_id);
            $this->response(['status' => true, "message"=>"Application submitted successfully."]);
        }
    }

    public function applyJobsAttachment_post()
    {
        $job_id = $this->input->post('job_id');
        $user_id = $this->input->post('user_id');

        $file="";
        if (isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {
            // do_upload
            $target_path = "uploads/users/";
            $name1 = $_FILES["file"]["name"];
            $ext = end((explode(".", $name1))); # extra () to prevent notice
            $file= time()."_".".". $ext;

            $target_path = $target_path . $file;
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_path) === true) {
                $file = $file;
            }
            $file_url = 'uploads/users/'.$file;

            $results = $this->JobModel->applyJobs($user_id, $job_id, $file_url);
            if ($results) {
                $this->response(['status' => true, "message"=>"Application submitted successfully."], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
                $this->response(['status' => false, "message"=>"Failed to submit application. Try again later."], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
        } else {
            $this->response(['status' => false, "message"=>"File not received."], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
    }

    public function getMyJobs_post()
    {
        //if($this->check_authentication(TRUE))
        {
            $params = json_decode(file_get_contents('php://input'));
            $results = $this->JobModel->getMyJobs($params->user_id);
            $this->response(['status' => true, "response"=>$results]);
        }
    }

    public function jobActions_post()
    {
        if ($this->check_authentication(true)) {
            $params = json_decode(file_get_contents('php://input'));
            if(isset($params->job_id) && isset($params->user_id)){
                $results = $this->JobModel->cancelJobApplication($params->user_id, $params->job_id);
                $this->response(['status' => true, "response"=>"Request submitted."]);    
            }else{
                $results = $this->JobModel->jobActions($params->action);
                $this->response(['status' => true, "response"=>"Request submitted."]);    
            }
            
        }
    }

    // validate required params
    public function varify_params($params, $required_params)
    {
        return true;
    }

    public function check_authentication($check_token = false)
    {
        return TRUE;
    }
}
