<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Accomodation extends BD_Controller {
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('AccomodationModel');
        $this->load->model('UsersModel');
        $this->load->library('SetResponse',NULL,'setresponse');
        $this->load->library('Uuid',NULL,'uuid');
        $this->auth();
    }

   

    public function index()
    {
        $id                     = $this->input->get("id");
        $listing_type_id        = $this->input->get("listing_type_id");
        $is_active              = $this->input->get("is_active");
        $where_info    = array();
        if(isset($accomodation_id)){
            $where_info['accomodation.id']   = $id;
        }
        if(isset($user_id)){
            $where_info['accomodation.listing_type_id']     = $listing_type_id;
        }
        if(isset($is_active)){
            $where_info['accomodation.is_active']    = $is_active;
        }
        
        $info          = $this->AccomodationModel->get_info($where_info);
        $output        = !empty($accomodation_id) || !empty($user_id) ? $info->row() : $info->result();
        $response      = $this->setresponse->jsonResponse($output,"Success",false);
        $this->set_response($response, REST_Controller::HTTP_OK); //This is the respon if success
    }

    public function create(){

        
        // $this->user_data = $decoded;

        $tokenData          = $this->setresponse->getTokenData();

        $uuid               = $this->uuid->guidv4('=c-'.date("YmdHis"));
        $business_name      = $this->input->post("business_name");
        $business_address   = $this->input->post("business_address");
        $business_email     = $this->input->post("business_email");
        $business_phone     = $this->input->post("business_phone");
        $business_image     = $this->input->post("business_image");
        $business_type      = $this->input->post("business_type");
        $owner_id           = $tokenData->user_id;

        $q                  = array('business_name' => $business_name);

        if(empty($business_name)){
            $response = $this->setresponse->jsonResponse([],"=business name is required",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($business_type)){
            $response = $this->setresponse->jsonResponse([],"business type is required",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }
        else if(empty($business_address)){
            $response = $this->setresponse->jsonResponse([],"business address is required",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($business_email)){
            $response = $this->setresponse->jsonResponse([],"business email is required",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($owner_id)){
            $response = $this->setresponse->jsonResponse([],"Owner is requierd",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if($this->BusinessModel->checifExist($q)->num_rows() == 1){
            $response = $this->setresponse->jsonResponse([],"business name already exist",true);
            $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
        }
        else{
            try{

                $payloadbusiness = array(
                    "business_id"       => $uuid,
                    "business_name"     => $business_name,
                    "business_address"  => $business_address,
                    "business_email"    => $business_email,
                    "business_phone"    => $business_phone,
                    "owner_id"          => $owner_id,
                    "business_type"     => $business_type
                );

                $data = $this->BusinessModel->create($payloadbusiness);

                if($data){
                    $response = $this->setresponse->jsonResponse($payloadbusiness,"Successfuly create business",false);
                    $this->set_response($response, REST_Controller::HTTP_OK); //This is the respon if success
                }else{
                    $response = $this->setresponse->jsonResponse([],"Error creating business",true);
                    $this->set_response($response, REST_Controller::HTTP_NOT_FOUND); //This is the respon if failed
                }
            }catch(Exception $e){
                $response = $this->setresponse->jsonResponse([],$e,true);
                $this->set_response($response, REST_Controller::HTTP_NOT_FOUND); //This is the respon if failed
            }
        }
    }

    public function update(){

        
        // $this->user_data = $decoded;

        $tokenData          = $this->setresponse->getTokenData();

        $business_id         = $this->input->post("business_id");
        $business_name       = $this->input->post("business_name");
        $business_address    = $this->input->post("business_address");
        $business_email      = $this->input->post("business_email");
        $business_phone      = $this->input->post("business_phone");
        $business_image      = $this->input->post("business_image");
        $business_type       = $this->input->post("business_type");
        $owner_id            = $tokenData->user_id;

        $q                   = array('business_name' => $business_name);

        $whereById           = array('business_id' => $business_id);
        $businessData        = $this->BusinessModel->checifExist($whereById);
        
       
        
        if(empty($business_id)){
            $response = $this->setresponse->jsonResponse([],"business id is required",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($business_type)){
            $response = $this->setresponse->jsonResponse([],"business type is required",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($business_name)){
            $response = $this->setresponse->jsonResponse([],"business name is required",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($business_address)){
            $response = $this->setresponse->jsonResponse([],"business address is required",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($business_email)){
            $response = $this->setresponse->jsonResponse([],"business email is required",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($owner_id)){
            $response = $this->setresponse->jsonResponse([],"Owner is requierd",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if($businessData->num_rows() == 0){
            $response = $this->setresponse->jsonResponse([],"No business on this business id $business_id",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if($businessData->row()->business_name != $business_name && $this->BusinessModel->checifExist($q)->num_rows() == 1){
            $response = $this->setresponse->jsonResponse([],"business name already exist",true);
            $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
        }
        else{
            try{    

                $where = array("business_id" => $business_id);
                $payloadbusiness = array(
                    "business_name"     => $business_name,
                    "business_address"  => $business_address,
                    "business_email"    => $business_email,
                    "business_phone"    => $business_phone,
                    "owner_id"          => $owner_id,
                    "business_type"     => $business_type
                );

                $data = $this->BusinessModel->update($payloadbusiness,$where);

                if($data){
                    $response = $this->setresponse->jsonResponse($payloadbusiness,"Successfuly update business",false);
                    $this->set_response($response, REST_Controller::HTTP_OK); //This is the respon if success
                }else{
                    $response = $this->setresponse->jsonResponse([],"Error updating business",true);
                    $this->set_response($response, REST_Controller::HTTP_NOT_FOUND); //This is the respon if failed
                }
            }catch(Exception $e){
                $response = $this->setresponse->jsonResponse([],$e,true);
                $this->set_response($response, REST_Controller::HTTP_NOT_FOUND); //This is the respon if failed
            }
        }
    }

    public function void(){

        $business_id        = $this->input->post("business_id");

        $whereById          = array('business_id' => $business_id);
        $businessData        = $this->BusinessModel->checifExist($whereById);
        
        if(empty($business_id)){
            $response = $this->setresponse->jsonResponse([],"Empty business id",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if($businessData->num_rows() == 0){
            $response = $this->setresponse->jsonResponse([],"No business on this business id $business_id",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }
        else{
            try{

                $where = array("business_id" => $business_id);
                $payloadUser = array(
                    'is_active' => 0
                );

                $data = $this->BusinessModel->update($payloadUser,$where);

                if($data){
                    $response = $this->setresponse->jsonResponse($payloadUser,"Successfuly void business",false);
                    $this->set_response($response, REST_Controller::HTTP_OK); //This is the respon if success
                }else{
                    $response = $this->setresponse->jsonResponse([],"Void business Error",true);
                    $this->set_response($response, REST_Controller::HTTP_NOT_FOUND); //This is the respon if failed
                }
            }catch(Exception $e){
                $response = $this->setresponse->jsonResponse([],$e,true);
                $this->set_response($response, REST_Controller::HTTP_NOT_FOUND); //This is the respon if failed
            }
        }
    }

    public function status(){

        $business_id        = $this->input->post("business_id");
        $status             = $this->input->post("status");

        $whereById          = array('business_id' => $business_id);
        $businessData        = $this->BusinessModel->checifExist($whereById);
        
        if(empty($business_id)){
            $response = $this->setresponse->jsonResponse([],"Business id is required",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($status)){
            $response = $this->setresponse->jsonResponse([],"Status is required",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }
        else if($businessData->num_rows() == 0){
            $response = $this->setresponse->jsonResponse([],"No business on this business id $business_id",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }
        else{
            try{
                $where = array("business_id" => $business_id);
                $payloadUser = array(
                    'status' => $status
                );

                $data = $this->BusinessModel->update($payloadUser,$where);

                if($data){
                    $response = $this->setresponse->jsonResponse($payloadUser,"Successfuly updated business status to $status",false);
                    $this->set_response($response, REST_Controller::HTTP_OK); //This is the respon if success
                }else{
                    $response = $this->setresponse->jsonResponse([],"Errror updating business status",true);
                    $this->set_response($response, REST_Controller::HTTP_NOT_FOUND); //This is the respon if failed
                }
            }catch(Exception $e){
                $response = $this->setresponse->jsonResponse([],$e,true);
                $this->set_response($response, REST_Controller::HTTP_NOT_FOUND); //This is the respon if failed
            }
        }
    }

}
