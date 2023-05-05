<?php

defined('BASEPATH') OR exit('No direct script access allowed');
use \Firebase\JWT\JWT;

class Auth extends BD_Controller {
    

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->model('UsersModel');
        $this->load->library('SetResponse',NULL,'setresponse');
        $this->load->library('Uuid',NULL,'uuid');
    }
    

    

    public function login()
    {
        $u = $this->post('username'); //Username Posted
        $p = sha1($this->post('password')); //Pasword Posted
        $q = array('username' => $u); //For where query condition
        $kunci = $this->config->item('thekey');
        $invalidLogin = 'Invalid Login'; //Respon if login invalid
        $val = $this->UsersModel->authenticate($q)->row(); //Model to get single data row from database base on username
        if($this->UsersModel->authenticate($q)->num_rows() == 0){
            $response = $this->setresponse->jsonResponse([],$invalidLogin,true);
            $this->response($response, REST_Controller::HTTP_OK);
        }

		$match = $val->password;   //Get password for user from database
        
        if($p == $match){  //Condition if password matched


            ///Get user info 
            $where_userinfo     = array('users.user_id' => $val->user_id); //For where query condition
            $user_info          = $this->UsersModel->get_user_info($where_userinfo)->row();

            $date                   = new DateTime();

            $token = array(
                'id'        => $val->id,  //From here
                'user_type' => $val->user_type,
                'role'      => $val->role,
                'user_id'   => $val->user_id,
                'username'  => $u,
                'iat'       => $date->getTimestamp(),
                'exp'       => $date->getTimestamp() + 60*60*5 //To here is to generate token
            );

            $output = array(
                'token'     => JWT::encode($token,$kunci), //This is the output token,
                'exp'       => $token['exp'], //This is the output expiration
                'user_info' => $user_info, ///User info output
            );

            $response = $this->setresponse->jsonResponse($output,"Successfuly Login",false);
            $this->set_response($response, REST_Controller::HTTP_OK); //This is the respon if success
        }
        else {
            $response = $this->setresponse->jsonResponse([],$invalidLogin,true);
            $this->set_response($response, REST_Controller::HTTP_OK); //This is the respon if failed
        }
    }

    public function register()
    {
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
            $this->set_response($response, REST_Controller::HTTP_OK);
        }else if(empty($password)){
            $response = $this->setresponse->jsonResponse([],"Empty Password",true);
            $this->set_response($response, REST_Controller::HTTP_OK);
        }else if(empty($user_type)){
            $response = $this->setresponse->jsonResponse([],"Empty User-type",true);
            $this->set_response($response, REST_Controller::HTTP_OK);
        }else if(empty($role)){
            $response = $this->setresponse->jsonResponse([],"Empty User-role",true);
            $this->set_response($response, REST_Controller::HTTP_OK);
        }else if(empty($lastname)){
            $response = $this->setresponse->jsonResponse([],"Empty Lastname",true);
            $this->set_response($response, REST_Controller::HTTP_OK);
        }else if(empty($middlename)){
            $response = $this->setresponse->jsonResponse([],"Empty Middlename",true);
            $this->set_response($response, REST_Controller::HTTP_OK);
        }else if(empty($firstname)){
            $response = $this->setresponse->jsonResponse([],"Empty Firstname",true);
            $this->set_response($response, REST_Controller::HTTP_OK);
        }else if(empty($mobile)){
            $response = $this->setresponse->jsonResponse([],"Empty Mobile #",true);
            $this->set_response($response, REST_Controller::HTTP_OK);
        }else if(empty($email)){
            $response = $this->setresponse->jsonResponse([],"Empty Username",true);
            $this->set_response($response, REST_Controller::HTTP_OK);
        }else if($this->UsersModel->authenticate($q)->num_rows() == 1){
            $response = $this->setresponse->jsonResponse([],"Username already taken",true);
            $this->response($response, REST_Controller::HTTP_OK);
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
                    $response = $this->setresponse->jsonResponse($payloadUser,"Successfuly Registered",false);
                    $this->set_response($response, REST_Controller::HTTP_OK); //This is the respon if success
                }else{
                    $response = $this->setresponse->jsonResponse([],"Registration Error",true);
                    $this->set_response($response, REST_Controller::HTTP_OK); //This is the respon if failed
                }
            }catch(Exception $e){
                $response = $this->setresponse->jsonResponse([],$e,true);
                $this->set_response($response, REST_Controller::HTTP_OK); //This is the respon if failed
            }
        }
    }

}
