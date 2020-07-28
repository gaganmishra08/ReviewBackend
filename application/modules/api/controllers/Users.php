<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Demo Controller with Swagger annotations
 * Reference: https://github.com/zircote/swagger-php/
 */
class Users extends API_Controller
{
    public function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('MyModel');
        $this->load->model('UserAPIModel');
        $this->load->model('ReviewAPIModel');
    }
    /**
     * @SWG\POST(
     * 	path="/users/login",
     * 	tags={"user"},
     * 	summary="Login API",
     * 	@SWG\Parameter(
     * 		in="header",
     * 		name="Client-Service",
     * 		description="Client-Service",
     * 		required=true,
     * 		type="string"
     * 	),
     * 	@SWG\Parameter(
     * 		in="header",
     * 		name="Auth-Key",
     * 		description="Auth-Key",
     * 		required=true,
     * 		type="string"
     * 	),
     * 	@SWG\Parameter(
     * 		in="body",
     * 		name="body",
     * 		description="Username and Password",
     * 		required=true,
     * 		@SWG\Schema(ref="#/definitions/JSON")
     * 	),
     * 	@SWG\Response(
     * 		response="200",
     * 		description="Successful operation"
     * 	)
     * )
     */
    public function login_post()
    {
        if ($this->check_authentication(false)) {
            $check_auth_client = $this->MyModel->check_auth_client();
            if ($check_auth_client == true) {
                //$params = json_decode(file_get_contents('php://input'), TRUE);
                $params = json_decode(file_get_contents('php://input'));
                $a = array('username','sign_in_type');

                if ($this->varify_params($params, array('username','sign_in_type'))) {
                    if ($params->sign_in_type === 1) {
                        $response = $this->MyModel->login($params->username, $params->password, $params->gcm_token);
                        $this->response($response);
                    } else {
                        $check_account = $this->UserAPIModel->checkAccountExist($params->username, null);
                        if ($check_account) {
                            $response = $this->MyModel->loginSocialAccount($params->username, $params->gcm_token);
                            $this->response($response);
                        } else {
                            // do signup
                            //$this->create_post();
                            $this->response(['status' => true, 'signed_up' => true, "message"=>'Invalid login. Check your username and password']);
                        }
                    }
                }
            } else {
                $this->response(['status' => false, "message"=>'Invalid login. Check your username and password']);
            }
        }
    }
    /**
     * @SWG\POST(
     * 	path="/users/logout",
     * 	tags={"user"},
     * 	summary="Logout",
     * 	@SWG\Parameter(
     * 		in="header",
     * 		name="Client-Service",
     * 		description="Client-Service",
     * 		required=true,
     * 		type="string"
     * 	),
     * 	@SWG\Parameter(
     * 		in="header",
     * 		name="Auth-Key",
     * 		description="Auth-Key",
     * 		required=true,
     * 		type="string"
     * 	),
     * 	@SWG\Parameter(
     * 		in="header",
     * 		name="User-ID",
     * 		description="User-ID",
     * 		required=true,
     * 		type="integer"
     * 	),
     * 	@SWG\Parameter(
     * 		in="header",
     * 		name="Authorization",
     * 		description="Authorization Key",
     * 		required=true,
     * 		type="string"
     * 	),
     * 	@SWG\Response(
     * 		response="200",
     * 		description="Successful operation"
     * 	)
     * )
     */
    public function logout_post()
    {
        if ($this->check_authentication(false)) {
            $response = $this->MyModel->logout();
            $this->response($response);
        }
    }
    /**
     * @SWG\POST(
     * 	path="/users/create",
     * 	tags={"user"},
     * 	summary="Create New Account",
     * 	@SWG\Parameter(
     * 		in="header",
     * 		name="Client-Service",
     * 		description="Client-Service",
     * 		required=true,
     * 		type="string"
     * 	),
     * 	@SWG\Parameter(
     * 		in="header",
     * 		name="Auth-Key",
     * 		description="Auth-Key",
     * 		required=true,
     * 		type="string"
     * 	),
     * 	@SWG\Parameter(
     * 		in="body",
     * 		name="body",
     * 		description="JSON Payload",
     * 		required=true,
     * 		@SWG\Schema(ref="#/definitions/JSON")
     * 	),
     * 	@SWG\Response(
     * 		response="200",
     * 		description="Successful operation"
     * 	)
     * )
     */
    public function create_post()
    {
        if ($this->check_authentication(false)) {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('username','email','password','sign_in_type','user_type','last_name','first_name','user_type_array'))) {
                $check_account = $this->UserAPIModel->checkAccountExist($params->username, $params->email);
                if ($check_account) {
                    $this->response(['status' => false, "message"=>"Account already exists. Please login."]);
                } else {
                    $this->load->library('ion_auth');
                    // passed validation
                    $activation_code = substr(md5(rand()), 0, 20);

                    $additional_data = array(
                        'first_name'	=> $params->first_name,
                        'last_name'		=> $params->last_name,
                        'sign_in_type'  => $params->sign_in_type,
                        'user_type'  => $params->user_type,
                        'phone' => $params->phone,
                        'collage_name' => $params->collage_name,
                        'collage_year' => $params->collage_year,
                        'activation_code' => $activation_code,
                        'gcm_token'  => $params->gcm_token,
                        'title'  => $params->title,
                    );

                    if (isset($params->company)) {
                        $additional_data['company'] = $params->company;
                    }
                    if (isset($params->profile_url)) {
                        $additional_data['profile_url'] = $params->profile_url;
                    }
                    // $groups = [$params->user_type_array];
                    // if(isset($params->user_type_array)){
                    // 	$additional_data['profile_url'] = $params->profile_url;
                    // }
                    $groups = $params->user_type_array;

                    // [IMPORTANT] override database tables to update Frontend Users instead of Admin Users
                    $this->ion_auth_model->tables = array(
                        'users'				=> 'users',
                        'groups'			=> 'groups',
                        'users_groups'		=> 'users_groups',
                        'login_attempts'	=> 'login_attempts',
                    );

                    // proceed to create user
                    $user_id = $this->ion_auth->register($params->username, $params->password, $params->email, $additional_data, $groups);
                    if ($user_id) {
                        $response = $this->MyModel->generatetoken($user_id, $params->gcm_token);
                        $response['signed_up'] = true;
                        // $this->sendAccountVerificationEmail($params->email, $activation_code);
                        // $response['verified_email'] = "Thank you for joining ReviewIt. Please check your email account to verify your email address.";
                        $this->response($response);
                    } else {
                        $this->response(['status' => false, "message"=>"Failed to signup. Check your details and Try again."]);
                    }
                }
            }
        }
    }

    public function sendAccountVerificationEmail($email_address, $activation_code)
    {
        // passed validation
        $this->load->helper('url');
        $url = base_url().'api/users/verifyemail/'.$activation_code;
        $this->load->library('email');

        $config['protocol']    = 'smtp';
        $config['smtp_host']    = 'mail.reviewit.site';
        $config['smtp_port']    = '25';
        $config['smtp_timeout'] = '2';
        $config['smtp_user']    = 'noreply@reviewit.site';
        $config['smtp_pass']    = 'IaE6WkfdLwh=';
        $config['charset']    = 'utf-8';

        $config['newline']    = "\r\n";

        $config['mailtype'] = 'html'; // or html

        $config['validation'] = true; // bool whether to validate email or not

        $this->email->initialize($config);

        $this->email->from('noreply@reviewit.site', 'NoReply ReviewIt');
        $this->email->to($email_address);

        $this->email->subject('ReviewIt account verification');

        $this->email->message("Hello!,<br/>Welcome to ReviewIt. In order to active your account please confirm your email address <a href='".$url."'> by clicking on this link</a>. <br/><br/>Thank you, <br/>Team ReviewIt");
        if ($this->email->send()) {
            // $this->response(['status' => TRUE, "message"=>"Email send for the accoount verification.".$this->email->print_debugger()],
            // 	REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            // $this->response(['status' => FALSE, "message"=>"Fail to send verification email.".$this->email->print_debugger()],
            // 	REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
    }
    public function verifyemail_get($code)
    {
        $this->UserAPIModel->verifyemailaddress($code);
        print_r('Email address verification complete.');
    }

    public function searchcollage_post()
    {
        $params = json_decode(file_get_contents('php://input'));
        $name = $params->search;
        $this->response(
            ['status' => true, "message"=>"","response"=>$this->UserAPIModel->searchCollage($name)],
                                REST_Controller::HTTP_OK
        ); // OK (200) being the HTTP response code
    }

    public function saveFeedback_post()
    {
        $params = json_decode(file_get_contents('php://input'));
        $this->UserAPIModel->savefeedback($params->user_id, $params->feedback, $params->rate);
        $this->response(
            ['status' => true, "message"=>"Thank you for sharing your feedback with us."],
                                REST_Controller::HTTP_OK
        ); // OK (200) being the HTTP response code
    }

    public function updateprofileimage_post()
    {
        //if($this->check_authentication(TRUE))
        {

            $id = $this->input->post('id');
            $image="";
            if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
                $target_path = "uploads/users/";

                $name1 = $_FILES["image"]["name"];
                $ext = pathinfo($_FILES["image"]["name"])['extension'];
                $image= time()."_".".". $ext;

                $target_path = $target_path . $image;
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_path) === true) {
                    $image=$image;
                }
                $image_url = 'uploads/users/'.$image;

                $user_array = array('profile_url'=>$image_url);

                $status = $this->UserAPIModel->updateUserDetail($id, $user_array);
                if ($status) {
                    $this->response(
                        ['status' => true, "message"=>"Profile updated successfuly.","response"=>$user_array],
                                REST_Controller::HTTP_OK
                    ); // OK (200) being the HTTP response code
                } else {
                    $this->response(
                        ['status' => false, "message"=>"Failed to update profile. Try again later."],
                                REST_Controller::HTTP_OK
                    ); // OK (200) being the HTTP response code
                }
            } else {
                $this->response(
                    ['status' => false, "message"=>"File not received."],
                                REST_Controller::HTTP_OK
                ); // OK (200) being the HTTP response code
            }

        }
    }
    /**
     * @SWG\POST(
     * 	path="/users/getUser",
     * 	tags={"user"},
     * 	summary="Get User",
     * 	@SWG\Parameter(
     * 		in="header",
     * 		name="Client-Service",
     * 		description="Client-Service",
     * 		required=true,
     * 		type="string"
     * 	),
     * 	@SWG\Parameter(
     * 		in="header",
     * 		name="Auth-Key",
     * 		description="Auth-Key",
     * 		required=true,
     * 		type="string"
     * 	),
     * 	@SWG\Parameter(
     * 		in="body",
     * 		name="body",
     * 		description="JSON Payload",
     * 		required=true,
     * 		@SWG\Schema(ref="#/definitions/JSON")
     * 	),
     * 	@SWG\Parameter(
     * 		in="header",
     * 		name="User-ID",
     * 		description="User-ID",
     * 		required=true,
     * 		type="integer"
     * 	),
     * 	@SWG\Parameter(
     * 		in="header",
     * 		name="Authorization",
     * 		description="Authorization Key",
     * 		required=true,
     * 		type="string"
     * 	),
     * 	@SWG\Response(
     * 		response="200",
     * 		description="Successful operation"
     * 	)
     * )
     */
    public function getUser_post()
    {
        //if ($this->check_authentication(true))
        {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('id'))) {
                if (isset($params->user_id)) {
                    $has_blocked = $this->UserAPIModel->checkHasBlockedUser($params->user_id, $params->id);
                    if ($has_blocked) {
                        $this->response(array('status' => true,'blocked' => true, 'message' => "You have blocked this account. Unblock to continue."));
                    }
                }
                {
                    $results = $this->UserAPIModel->getFullUserInfo($params->id);
                    $certificates = $this->UserAPIModel->getusercertificates($params->id);
                    $skills = $this->UserAPIModel->getUserSkills($params->id);
                    if ($results->user_type == 3) {
                        // get company review
                        $review = $this->ReviewAPIModel->getcompanyreview($results->id);
                        // $results->review = $review;
                        $results->avg_rating = $review->avg_rating;
                        $results->total_rating = $review->total_rating;
                    }
                    if (isset($params->check_reviewed)) {
                        $review = $this->ReviewAPIModel->hasReviewedCompany($params->user_id, $params->id);
                        if ($review) {
                            $this->response(array('status' => true,'response' => $results, 'certificates' => $certificates, 'review' => $review));
                        } else {
                            $this->response(array('status' => true,'response' => $results, 'certificates' => $certificates));
                        }
                    } else {
                        $this->response(array('status' => true,'response' => $results, 'certificates'=>$certificates, 'skills'=>$skills));
                    }
                }
            }
        }
    }
    /**
     * @SWG\POST(
     * 	path="/users/changepassword",
     * 	tags={"user"},
     * 	summary="Change Password",
     * 	@SWG\Parameter(
     * 		in="header",
     * 		name="Client-Service",
     * 		description="Client-Service",
     * 		required=true,
     * 		type="string"
     * 	),
     * 	@SWG\Parameter(
     * 		in="header",
     * 		name="Auth-Key",
     * 		description="Auth-Key",
     * 		required=true,
     * 		type="string"
     * 	),
     * 	@SWG\Parameter(
     * 		in="body",
     * 		name="body",
     * 		description="JSON Payload",
     * 		required=true,
     * 		@SWG\Schema(ref="#/definitions/JSON")
     * 	),
     * 	@SWG\Parameter(
     * 		in="header",
     * 		name="User-ID",
     * 		description="User-ID",
     * 		required=true,
     * 		type="integer"
     * 	),
     * 	@SWG\Parameter(
     * 		in="header",
     * 		name="Authorization",
     * 		description="Authorization Key",
     * 		required=true,
     * 		type="string"
     * 	),
     * 	@SWG\Response(
     * 		response="200",
     * 		description="Successful operation"
     * 	)
     * )
     */
    public function changepassword_post()
    {
        if ($this->check_authentication(true)) {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('id','old_password','new_password'))) {
                $results = $this->ion_auth_model->change_password_by_ID($params->id, $params->old_password, $params->new_password);
                $this->response($results);
            }
        }
    }
    /**
     * @SWG\POST(
     * 	path="/users/forgotpassword",
     * 	tags={"user"},
     * 	summary="Forgot password",
     * 	@SWG\Parameter(
     * 		in="header",
     * 		name="Client-Service",
     * 		description="Client-Service",
     * 		required=true,
     * 		type="string"
     * 	),
     * 	@SWG\Parameter(
     * 		in="header",
     * 		name="Auth-Key",
     * 		description="Auth-Key",
     * 		required=true,
     * 		type="string"
     * 	),
     * 	@SWG\Parameter(
     * 		in="body",
     * 		name="body",
     * 		description="JSON Payload",
     * 		required=true,
     * 		@SWG\Schema(ref="#/definitions/JSON")
     * 	),
     * 	@SWG\Response(
     * 		response="200",
     * 		description="Successful operation"
     * 	)
     * )
     */
    public function forgotpassword_post()
    {
        if ($this->check_authentication(false)) {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('email'))) {
                $results = $this->UserAPIModel->checkAccountExist(null, $params->email);
                if ($results) {
                    if ($results->sign_in_type == 1) {
                        $this->response(['status' => true, "message"=>"Forgot password link has been sent to your email address. Check you email account."]);
                    } else {
                        $this->response(['status' => false, "message"=>"You have not sign-up using email address. Try to logn with socail account."]);
                    }
                } else {
                    $this->response(['status' => false, "message"=>"Email address not found."]);
                }
            }
        }
    }


    // advance user functions
    public function getcertificate_post()
    {
        if ($this->check_authentication(false)) {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('email'))) {
                $results = $this->UserAPIModel->checkAccountExist(null, $params->email);
                $this->response(['status' => false, "message"=>"Email address not found."]);
            }
        }
    }

    public function updateUserGroup_post()
    {
        if ($this->check_authentication(true)) {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('id', 'user_groups_id','current_groups_id', 'user_type'))) {
                $this->ion_auth_model->add_to_group($params->user_groups_id, $params->id);
                $this->ion_auth_model->remove_from_group($params->current_groups_id, $params->id);

                $user_array = array('user_type'=>$params->user_type);
                $status = $this->UserAPIModel->updateUserDetail($params->id, $user_array);
                $this->response(['status' => true, "message"=>"Group updated successfuly."]);
            }
        }
    }

    public function updateUserInfo_post()
    {
        //if ($this->check_authentication(true)) 
        {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('id','info'))) {
                $info = $params->info;
                $info_array = json_decode(json_encode($info), true);
                $status = $this->UserAPIModel->updateUserDetail($params->id, $info_array);
                $this->response(['status' => true, "message"=>"Profile updated successfuly."]);
            }
        }
    }


    public function searchUsers_post()
    {
        //if($this->check_authentication(TRUE))
        {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('search'))) {
                //$event_id = $this->input->post('search');
                $company_only = false;
                $users_only = false;
                if (isset($params->company_only)) {
                    $company_only = true;
                }
                if (isset($params->users_only)) {
                    $users_only = true;
                }
                $users = $this->UserAPIModel->searchUser($params->search, $company_only, $users_only);
                $this->response(['status' => true, "response"=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
        }
    }

    public function getCertificatesList_post()
    {
        //if ($this->check_authentication(true))
        {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('user_id'))) {
                $certificates = $this->UserAPIModel->getusercertificates($params->user_id);
                $this->response(['status' => true, "response"=>$certificates], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
        }
    }

    public function addCertificate_post()
    {
        //if ($this->check_authentication(true))
        {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('user_id', 'name', 'description', 'year'))) {
                $payload = json_decode(json_encode($params), true);
                $id = $this->UserAPIModel->addcertificate($payload);
                if ($id) {
                    $this->response(['status' => true, "id"=>$id, "message"=>"Certificate added successfully."], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                } else {
                    $this->response(['status' => false, "message"=>"Fail to add certificate. Please try again."], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                }
            }
        }
    }

    public function editCertificate_post()
    {
        //if ($this->check_authentication(true))
        {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('id','info'))) {
                $info = $params->info;
                $info_array = json_decode(json_encode($info), true);
                $status = $this->UserAPIModel->updateusercertificate($params->id, $info_array);
                $this->response(['status' => true, "message"=>"Certificate updated successfuly."]);
            }
        }
    }

    public function deleteCertificate_post()
    {
        //if ($this->check_authentication(true))
        {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('id'))) {
                $status = $this->UserAPIModel->deleteusercertificate($params->id);
                $this->response(['status' => true, "message"=>"Certificate deleted successfuly."]);
            }
        }
    }

    // experience
    public function getExperienceList_post()
    {
        $params = json_decode(file_get_contents('php://input'));
            $certificates = $this->UserAPIModel->getuserExperiences($params->user_id);
                $this->response(['status' => true, "response"=>$certificates], REST_Controller::HTTP_OK);
    }

    public function addExperience_post()
    {
        //if ($this->check_authentication(true))
        {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('data'))) {
                $payload = json_decode(json_encode($params->data), true);
                $id = $this->UserAPIModel->addExperience($payload);
                if ($id) {
                    $this->response(['status' => true, "id"=>$id, "message"=>"Experience added successfully."], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                } else {
                    $this->response(['status' => false, "message"=>"Fail to add Experience. Please try again."], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                }
            }
        }
    }

    public function editExperience_post()
    {
        //if ($this->check_authentication(true))
        {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('id','data'))) {
                $info_array = json_decode(json_encode($params->data), true);
                $status = $this->UserAPIModel->updateuserExperience($params->id, $info_array);
                $this->response(['status' => true, "message"=>"Experience updated successfuly."]);
            }
        }
    }

    public function deleteExperience_post()
    {
        //if ($this->check_authentication(true))
        {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('id'))) {
                $status = $this->UserAPIModel->deleteuserExperience($params->id);
                $this->response(['status' => true, "message"=>"Experience deleted successfuly."]);
            }
        }
    }
    //end

    // messages
    public function getMessagesConversation_post()
    {
        
        {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('id'))) {
                $results = $this->UserAPIModel->getMessagesConversation($params->id);
                $this->response(['status' => true, "response"=>$results]);
            }
        }
    }

    public function getMessagesList_post()
    {
        //if ($this->check_authentication(true))
        {
            $params = json_decode(file_get_contents('php://input'));
            $results = [];
            if (isset($params->group_id)) {
                $results = $this->UserAPIModel->getMessages(null, null, $params->group_id);
                $group = $this->UserAPIModel->getMessageGroupStatus($params->to_id, $params->group_id);
                if ($group) {
                    $this->response(['status' => true, "response"=>$results, "group_status"=>$group]);
                } else {
                    // owner of the group, not required.
                    $this->response(['status' => true, "response"=>$results]);
                }
            } else {
                $results = $this->UserAPIModel->getMessages($params->from_id, $params->to_id, null);
                $this->response(['status' => true, "response"=>$results]);
            }
        }
    }

    public function sendMessage_post()
    {
        if ($this->check_authentication(true)) {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('payload'))) {
                $message_data = json_decode(json_encode($params->payload), true);
                $results = $this->UserAPIModel->saveMessage($message_data);
                if ($results) {
                    if (isset($params->payload->group_id)) {
                        // send notification to group
                    } else {
                        // send personal notification
                        $user_info = $this->UserAPIModel->getUserToken($params->payload->to_id);
                        if ($user_info && $user_info->gcm_token) {
                            // print_r($user_info);
                            $this->sendMessageNotification('message', $params->name, $params->payload->from_id, "New message from ".$params->name, $params->payload->message, $user_info->gcm_token);
                        }
                    }
                    $this->response(['status' => true, "id"=>$results, "message"=>"Message sent."]);
                } else {
                    $this->response(['status' => false, "message"=>"Request failed. Please try again later."]);
                }
            }
        }
    }

    public function sendMessageNotification($n_type, $name, $from_id, $title, $message, $registrationId)
    {
        $this->load->library('Gcm');
        $this->gcm->setMessage($message);

        // add recepient or few
        $this->gcm->addRecepient($registrationId);
        //$this->gcm->setRecepients($registrationIds);

        // set additional data
        $this->gcm->setData(array(
            'title' => $title,
            'from_id' => $from_id,
            'name' => $name,
            'n_type' => $n_type
        ));

        // also you can add time to live
        //$this->gcm->setTtl(500);
        // and unset in further
        $this->gcm->setTtl(false);

        // set group for messages if needed
        //$this->gcm->setGroup('Test');
        // or set to default
        $this->gcm->setGroup(false);

        // then send
        $this->gcm->send();
        //echo 'Success for all messages';


        // print_r($this->gcm->status);
        // print_r($this->gcm->messagesStatuses);
    }

    public function getUserGroups_post()
    {
        {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('id'))) {
                $results = $this->UserAPIModel->getUserGroups($params->id);
                $my_groups = $this->UserAPIModel->getMyGroups($params->id);
                $this->response(['status' => true, "response"=>$results, 'my_groups'=>$my_groups]);
            }
        }
    }

    public function getGroupMembers_post()
    {
        if ($this->check_authentication(true)) {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('group_id'))) {
                $results = $this->UserAPIModel->getGroupMembers($params->group_id);
                $this->response(['status' => true, "response"=>$results]);
            }
        }
    }

    public function updateGroupStatus_post()
    {
        $params = json_decode(file_get_contents('php://input'));
        if ($this->varify_params($params, array('group_id'))) {
            $results = $this->UserAPIModel->updateGroupStatus($params->user_id, $params->group_id, $params->status);
            $this->response(['status' => true, "response"=>'Request accepted.']);
        }
    }
    public function getTrendingUsers_post()
    {
        $users = $this->UserAPIModel->getTrendingUsers();
        foreach ($users as $index => $user) {
            # code...
            $skills = [];
            $skills_result = $this->UserAPIModel->getUserSkillsByName($user['id']);
            foreach ($skills_result as $i => $skill) {
                array_push($skills, ' '.$skill['name']);
            }
            $users[$index]['skills'] = $skills;
        }

        $this->response(['status' => true, "response"=>$users]);
    }
    public function removeGroupMember_post()
    {
        if ($this->check_authentication(true)) {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('group_id','user_id'))) {
                $results = $this->UserAPIModel->removeGroupMember($params->group_id, $params->user_id);
                $this->response(['status' => true, "message"=>"Member removed successfuly."]);
            }
        }
    }

    // end

    // skills

    public function getskills_post()
    {
        $results = $this->UserAPIModel->getAllSkills();
        $this->response(['status' => true, "response"=>$results], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function findskills_post()
    {
        $params = json_decode(file_get_contents('php://input'));
        if ($this->varify_params($params, array('search'))) {
            //$event_id = $this->input->post('search');
            $users = $this->UserAPIModel->findskills($params->search);
            $this->response(['status' => true, "response"=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
    }

    public function getUserSkills_post()
    {
        if ($this->check_authentication(true)) {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('user_id'))) {
                //$event_id = $this->input->post('search');
                $users = $this->UserAPIModel->getUserSkills($params->user_id);
                $this->response(['status' => true, "response"=>$users], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
        }
    }

    public function removeUserSkill_post()
    {
        if ($this->check_authentication(true)) {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('user_id', 'skill_id'))) {
                //$event_id = $this->input->post('search');
                $users = $this->UserAPIModel->removeUserSkill($params->user_id, $params->skill_id);
                $this->response(['status' => true, "message"=>"Skill removed successfully."], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
        }
    }

    // master_table
    public function insertItem_post()
    {
        //if($this->check_authentication(TRUE))
        {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('table_name', 'payload'))) {
                $results = [];
                if (isset($params->avoid_duplicate)) {
                    $results = $this->UserAPIModel->checkDataExist($params->table_name, $params->payload);
                    if ($results) {
                        $this->response(['status' => true, "message"=>"Successful"]);
                    } else {
                        $results = $this->UserAPIModel->insertItem($params->table_name, $params->payload);
                    }
                } else {
                    $results = $this->UserAPIModel->insertItem($params->table_name, $params->payload);
                }

                $this->response(['status' => true, "message"=>"Successful"]);
            }
        }
    }

    public function updateItem_post()
    {
        if ($this->check_authentication(true)) {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('table_name','payload', 'id'))) {
                $info_array = json_decode(json_encode($params->payload), true);
                $status = $this->UserAPIModel->updateItem($params->table_name, $info_array);
                $this->response(['status' => true, "message"=>"Updated successfuly."]);
            }
        }
    }

    public function deleteItem_post()
    {
        if ($this->check_authentication(true)) {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('id', 'table_name'))) {
                $status = $this->UserAPIModel->deleteItem($params->table_name, $params->id);
                $this->response(['status' => true, "message"=>"Deleted successfuly."]);
            }
        }
    }
    // end

    public function getTermsPrivacy_post()
    {
        $this->response(['status' => true, "response"=>$this->UserAPIModel->getSettings()]);
    }

    // blocked
    public function blockedUser_post()
    {
        //if ($this->check_authentication(true))
        {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('user_id', 'to_user_id'))) {
                $status = $this->UserAPIModel->blockedUser($params->user_id, $params->to_user_id);
                $this->response(['status' => true, "message"=>"User blocked successfully."]);
            }
        }
    }
    public function unblockedUser_post()
    {
        //if ($this->check_authentication(true))
        {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('user_id', 'to_user_id'))) {
                $status = $this->UserAPIModel->unblockedUser($params->user_id, $params->to_user_id);
                $this->response(['status' => true, "message"=>"User unblocked successfully."]);
            }
        }
    }

    public function getBlockedUserList_post()
    {
        //if ($this->check_authentication(true))
        {
            $params = json_decode(file_get_contents('php://input'));
            if ($this->varify_params($params, array('user_id'))) {
                $results = $this->UserAPIModel->getBlockedUsers($params->user_id);
                $users = [];
                for ($i=0; $i<count($results);$i++) {
                    array_push($users, $this->UserAPIModel->getShortUserInfo($results[$i]['to_user_id']));
                }
                $this->response(['status' => true, "message"=>"No blocked users.", "response"=>$users]);
            }
        }
    }

    // validate required params
    public function varify_params($params, $required_params)
    {
        if ($params==null) {
            $this->response([
                    'status' => false,
                    'message' => 'Missing request payload.'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        } else {
            for ($i=0; $i<count($required_params);$i++) {
                $name = $required_params[$i];
                if (!isset($params->$name)) {
                    $this->response([
                            'status' => false,
                            'message' => 'param missing '.$required_params[$i]
                    ]); // NOT_FOUND (404) being the HTTP response code
                }
            }
            return true;
        }
    }


    public function check_authentication($check_token = false)
    {
        return TRUE;
    }
}
