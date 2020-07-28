<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Demo Controller with Swagger annotations
 * Reference: https://github.com/zircote/swagger-php/
 */
class ReviewAPI extends API_Controller
{
    public function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('MyModel');
        $this->load->model('UserAPIModel');
        $this->load->model('ReviewAPIModel');
        $this->load->model('JobModel');
    }

    public function getReviewByID_post()
    {
        $params = json_decode(file_get_contents('php://input'));
        if ($this->varify_params($params, array('review_id'))) {
            $results = $this->ReviewAPIModel->getReviewByID($params->review_id);
            $this->response(['status' => true, "response"=>$results], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
    }

    public function searchCompany_post()
    {
        //if($this->check_authentication(TRUE))
        {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('search'))) {
                //$event_id = $this->input->post('search');
                $companies = $this->UserAPIModel->searchUser($params->search, true);
                for ($i=0; $i<COUNT($companies); $i++) {
                    $review = $this->ReviewAPIModel->getAverageReviewForCompany($companies[$i]['id']);
                    $companies[$i]['avg_rating'] = $review->avg_rating;
                    $companies[$i]['total_rating'] = $review->total_rating;
                }
                $this->response(['status' => true, "response"=>$companies], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
        }
    }
    public function getCompaniesNearLocation_post()
    {
        $params = json_decode(file_get_contents('php://input'));
        $lat = $params->lat;
        $long = $params->long;
        $user_id = $params->user_id;
        $radius = $params->radius;

        $companies = $this->UserAPIModel->getCompaniesNearLocation($user_id, $lat, $long, $radius);
        if (count($companies) == 0) {
            $companies = $this->ReviewAPIModel->getTopReviewCompany();
        }
        for ($i=0; $i<COUNT($companies); $i++) {
            $review = $this->ReviewAPIModel->getAverageReviewForCompany($companies[$i]['id']);
            $companies[$i]['avg_rating'] = $review->avg_rating;
            $companies[$i]['total_rating'] = $review->total_rating;
        }

        $this->response(['status' => true, "response"=>$companies]);
    }
    public function getTopReviewCompany_post()
    {
        $results = $this->ReviewAPIModel->getTopReviewCompany();

        for ($i=0; $i<COUNT($results); $i++) {
            $review = $this->ReviewAPIModel->getAverageReviewForCompany($results[$i]['id']);
            $results[$i]['review'] = $review;
        }

        $this->response(['status' => true, "response"=>$results]);
    }

    public function getcompanyreviewlist_post()
    {
        $params = json_decode(file_get_contents('php://input'));
        if ($this->varify_params($params, array('company_id'))) {
            $results = $this->ReviewAPIModel->getcompanyUsersReviews($params->company_id);
            $this->response(['status' => true, "response"=>$results]);
        }
    }
    public function deleteCompanyReview_post()
    {
        $params = json_decode(file_get_contents('php://input'));
        if ($this->varify_params($params, array('id'))) {
            $results = $this->ReviewAPIModel->deleteCompanyReview($params->id);
            $this->response(['status' => true, "message"=>"Your review deleted successfully."]);
        }
    }
    public function saveCompanyReview_post()
    {
        //if($this->check_authentication(TRUE))
        {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('review'))) {
                $state = '';
                $message = '';
                if (isset($params->review_id)) {
                    $state = $params->review_id;
                    $this->ReviewAPIModel->updateReview($params->review_id, $params->review);
                    $message = 'Review updated successfully.';
                } else {
                    $state = $this->ReviewAPIModel->saveReview($params->review);
                    $message = 'Review posted successfully.';
                }

                if ($state) {
                    $this->response(['status' => true, "message" => $message], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                } else {
                    $this->response(['status' => false, "message" => "Please try again later."], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                }
            }
        }
    }
}
