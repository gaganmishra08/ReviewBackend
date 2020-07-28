<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ReviewAPIModel extends CI_Model
{
    public function getTopReviewCompany()
    {
        $this->db->select('COUNT(company_review.company_id) as reviews, users.active, users.user_type, users.id, users.email, users.company, users.profile_url, users.website, users.first_name, users.phone, users.about, users.city, users.state, users.address, users.location', false);
        $this->db->where("(users.active = 1 OR users.active = 2) AND users.user_type = 3", null, false);
        $this->db->join('users', 'company_review.company_id = users.id', 'left');
        $this->db->group_by('company_review.company_id');
        $this->db->order_by('reviews', 'desc');
        $this->db->limit('100', 0);
        return $this->db->get('company_review')->result_array();
    }
    public function getReviewByID($id)
    {
        return $this->db->get_where('company_review', array('id'=> $id))->row();
    }
    public function getReviewForCompany($company_id)
    {
        return $this->db->select('company_review.user_id, company_review.rating, company_review.message')
          ->get_where('company_review', array('company_review.company_id'=>$company_id))->result_array();
    }
    public function getAverageReviewForCompany($company_id)
    {
        return $this->db->select('COUNT(*) as total_rating, AVG(rate) as avg_rating')
          ->get_where('company_review', array('company_review.company_id'=>$company_id))->row();
    }

    public function updateReview($id, $review)
    {
        $this->db->where('id', $id);
        $this->db->update('company_review', $review);
    }
    public function saveReview($data)
    {
        $this->db->insert('company_review', $data);
        return $this->db->insert_id();
    }
    public function hasReviewedCompany($user_id, $company_id)
    {
        return $this->db->select('*')
            ->get_where('company_review', array('user_id'=>$user_id, 'company_id' => $company_id))->row();
    }
    public function getcompanyreview($user_id)
    {
        $q = "SELECT AVG(rate) as avg_rating, count(*) as total_rating FROM company_review where company_id =".$user_id;
        return $this->db->query($q)->row();
    }
    public function deleteCompanyReview($id)
    {
        return $this->db->delete('company_review', array('id'=>$id));
    }
    public function getcompanyUsersReviews($company_id)
    {
        $this->db->select('company_review.id as review_id, company_review.rate, company_review.comment,company_review.anonymous, users.id, users.email, users.company, users.profile_url, users.website, users.first_name, users.phone', false);
        $this->db->join('users', 'users.id = company_review.user_id', 'left');
        $this->db->where("company_review.company_id", $company_id);
        return $this->db->get('company_review')->result_array();
    }
}
