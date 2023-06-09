<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends BD_Controller {
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('UsersModel');
        $this->load->model('BusinessModel');
        $this->load->library('SetResponse',NULL,'setresponse');
        $this->load->library('Uuid',NULL,'uuid');
        $this->auth();
    }

   

    public function index()
    {
        $user_id        = $this->input->get("user_id");
        $is_active      = $this->input->get("is_active");
        $where_userinfo = empty($user_id) ? array('users.is_active' => $is_active) :  array('users.user_id' => $user_id); //For where query condition
        $user_info      = $this->UsersModel->get_user_info($where_userinfo);
        $output         = empty($user_id) ? $user_info->result() : $user_info->row();
        $response       = $this->setresponse->jsonResponse($output,"Success",false);
        $this->set_response($response, REST_Controller::HTTP_OK); //This is the respon if success
    }

    public function profile()
    {
        $output         = array();
        $user_id        = $this->input->get("user_id");
        $is_active      = $this->input->get("is_active");
        
        $where_userinfo = empty($user_id) ? array('users.is_active' => $is_active) :  array('users.user_id' => $user_id); //For where query condition
        $user_info      = $this->UsersModel->get_user_info($where_userinfo);

        // Business Data
        $where_business_info    = array('business.owner_id' => $user_   id);
        $business_info           = $this->BusinessModel->get_info($where_business_info);

        $output['user_info']         = empty($user_id) ? $user_info->result() : $user_info->row();

        if($business_info->num_rows() > 0){
            $output['business'] = $business_info->row();
        }
       
        
        $response       = $this->setresponse->jsonResponse($output,"Success",false);
        $this->set_response($response, REST_Controller::HTTP_OK); //This is the respon if success
    }

    public function new(){

        $uuid           = $this->uuid->guidv4('u-'.date("YmdHis"));
        $username       = $this->input->post("username");
        $password       = $this->input->post("password");
        $user_type      = $this->input->post("user_type");
        $role           = $this->input->post("role");
        $lastname       = $this->input->post("lastname");
        $middlename     = $this->input->post("middlename");
        $firstname      = $this->input->post("firstname");
        $mobile         = $this->input->post("mobile");
        $email          = $this->input->post("email");
        $q              = array('username' => $username);

        if(empty($username)){
            $response = $this->setresponse->jsonResponse([],"Empty Username",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($password)){
            $response = $this->setresponse->jsonResponse([],"Empty Password",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($user_type)){
            $response = $this->setresponse->jsonResponse([],"Empty User-type",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($role)){
            $response = $this->setresponse->jsonResponse([],"Empty User-role",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($lastname)){
            $response = $this->setresponse->jsonResponse([],"Empty Lastname",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($middlename)){
            $response = $this->setresponse->jsonResponse([],"Empty Middlename",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($firstname)){
            $response = $this->setresponse->jsonResponse([],"Empty Firstname",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($mobile)){
            $response = $this->setresponse->jsonResponse([],"Empty Mobile #",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($email)){
            $response = $this->setresponse->jsonResponse([],"Empty Username",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if($this->UsersModel->authenticate($q)->num_rows() == 1){
            $response = $this->setresponse->jsonResponse([],"Username already taken",true);
            $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
        }
        else{
            try{

                $payloadUser = array(
                    'user' => array(
                        "user_id"       => $uuid,
                        "username"      => $username,
                        "password"      => sha1($password),
                        "user_type"     => $user_type,
                        "role"          => $role,
                    ),
                    'user_info' => array(
                        "user_id"       => $uuid,
                        "lastname"      => $lastname,
                        "middlename"    => $middlename,
                        "firstname"     => $firstname,
                        "mobile"        => $mobile,
                        "email"         => $email,
                    )
                );

                $data = $this->UsersModel->register_user($payloadUser);

                if($data){
                    $response = $this->setresponse->jsonResponse($payloadUser,"Successfuly add user",false);
                    $this->set_response($response, REST_Controller::HTTP_OK); //This is the respon if success
                }else{
                    $response = $this->setresponse->jsonResponse([],"Add user Error",true);
                    $this->set_response($response, REST_Controller::HTTP_NOT_FOUND); //This is the respon if failed
                }
            }catch(Exception $e){
                $response = $this->setresponse->jsonResponse([],$e,true);
                $this->set_response($response, REST_Controller::HTTP_NOT_FOUND); //This is the respon if failed
            }
        }
    }

    public function update(){

        $user_id        = $this->input->post("user_id");
        $user_type      = $this->input->post("user_type");
        $role           = $this->input->post("role");
        $lastname       = $this->input->post("lastname");
        $middlename     = $this->input->post("middlename");
        $firstname      = $this->input->post("firstname");
        $mobile         = $this->input->post("mobile");
        $email          = $this->input->post("email");
       
        if(empty($user_id)){
            $response = $this->setresponse->jsonResponse([],"Empty User id",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($user_type)){
            $response = $this->setresponse->jsonResponse([],"Empty User-type",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($role)){
            $response = $this->setresponse->jsonResponse([],"Empty User-role",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($lastname)){
            $response = $this->setresponse->jsonResponse([],"Empty Lastname",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($middlename)){
            $response = $this->setresponse->jsonResponse([],"Empty Middlename",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($firstname)){
            $response = $this->setresponse->jsonResponse([],"Empty Firstname",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($mobile)){
            $response = $this->setresponse->jsonResponse([],"Empty Mobile #",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($email)){
            $response = $this->setresponse->jsonResponse([],"Empty Username",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }
        else{
            try{

                $where = array("user_id" => $user_id);
                $payloadUser = array(
                    'user' => array(
                        "user_type"     => $user_type,
                        "role"          => $role,
                    ),
                    'user_info' => array(
                        "lastname"      => $lastname,
                        "middlename"    => $middlename,
                        "firstname"     => $firstname,
                        "mobile"        => $mobile,
                        "email"         => $email,
                    )
                );

                $data = $this->UsersModel->update_user($payloadUser,$where);

                if($data){
                    $response = $this->setresponse->jsonResponse($payloadUser,"Successfuly update user",false);
                    $this->set_response($response, REST_Controller::HTTP_OK); //This is the respon if success
                }else{
                    $response = $this->setresponse->jsonResponse([],"Update user Error",true);
                    $this->set_response($response, REST_Controller::HTTP_NOT_FOUND); //This is the respon if failed
                }
            }catch(Exception $e){
                $response = $this->setresponse->jsonResponse([],$e,true);
                $this->set_response($response, REST_Controller::HTTP_NOT_FOUND); //This is the respon if failed
            }
        }
    }

    public function void(){

        $user_id        = $this->input->post("user_id");
        if(empty($user_id)){
            $response = $this->setresponse->jsonResponse([],"Empty User id",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }
        else{
            try{

                $where = array("user_id" => $user_id);
                $payloadUser = array(
                    'user' => array(
                        'is_active' => 0
                    )
                );

                $data = $this->UsersModel->update_user($payloadUser,$where);

                if($data){
                    $response = $this->setresponse->jsonResponse($payloadUser,"Successfuly void user",false);
                    $this->set_response($response, REST_Controller::HTTP_OK); //This is the respon if success
                }else{
                    $response = $this->setresponse->jsonResponse([],"Void user Error",true);
                    $this->set_response($response, REST_Controller::HTTP_NOT_FOUND); //This is the respon if failed
                }
            }catch(Exception $e){
                $response = $this->setresponse->jsonResponse([],$e,true);
                $this->set_response($response, REST_Controller::HTTP_NOT_FOUND); //This is the respon if failed
            }
        }
    }

}
