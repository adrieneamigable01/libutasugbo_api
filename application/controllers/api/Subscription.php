<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Subscription extends BD_Controller {
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('SubscriptionModel');
        $this->load->model('UsersModel');
        $this->load->library('SetResponse',NULL,'setresponse');
        $this->load->library('Uuid',NULL,'uuid');
        $this->auth();
    }

   

    public function index()
    {

        $subscription_id    = $this->input->get("subscription_id");
        $is_active          = $this->input->get("is_active");

        $where_subscription_info    = array();
        
        if(isset($subscription_id)){
            $where_subscription_info['subscription.subscription_id']  = $subscription_id;
        }
        if(isset($is_active)){
            $where_subscription_info['subscription.is_active']        = $is_active;
        }
        
        $business_info           = $this->SubscriptionModel->get_info($where_subscription_info);
        $output                 = !empty($business_id) || !empty($user_id) ? $business_info->row() : $business_info->result();
        $response               = $this->setresponse->jsonResponse($output,"Success",false);
        $this->set_response($response, REST_Controller::HTTP_OK); //This is the respon if success
    }

    public function create(){

        
        // $this->user_data = $decoded;

        $tokenData          = $this->setresponse->getTokenData();

        $subscription_id    = $this->uuid->guidv4('s-'.date("YmdHis"));
        $name               = $this->input->post("name");
        $price              = $this->input->post("price");
        $duration           = $this->input->post("duration");
        $description        = $this->input->post("description");

        $q                  = array('name' => $name);

        if(empty($name)){
            $response = $this->setresponse->jsonResponse([],"Subscription name is required",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($price)){
            $response = $this->setresponse->jsonResponse([],"Subscription price is required",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }
        else if(empty($duration)){
            $response = $this->setresponse->jsonResponse([],"Duration is required",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($description)){
            $response = $this->setresponse->jsonResponse([],"Description is required",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if($this->SubscriptionModel->checifExist($q)->num_rows() == 1){
            $response = $this->setresponse->jsonResponse([],"Subscription name already exist",true);
            $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
        }
        else{
            try{

                $payloadsubscription = array(
                    "subscription_id"   => $subscription_id,
                    "name"              => $name,
                    "price"             => $price,
                    "duration"          => $duration,
                    "created_at"          => date("Y-m-d H:i:s"),
                    "description"       => $description,
                );

                $data = $this->SubscriptionModel->create($payloadsubscription);

                if($data){
                    $response = $this->setresponse->jsonResponse($payloadsubscription,"Successfuly create subscription",false);
                    $this->set_response($response, REST_Controller::HTTP_OK); //This is the respon if success
                }else{
                    $response = $this->setresponse->jsonResponse([],"Error creating subscription",true);
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

        $subscription_id    = $this->input->post("subscription_id");
        $name               = $this->input->post("name");
        $price              = $this->input->post("price");
        $duration           = $this->input->post("duration");
        $description        = $this->input->post("description");

        $q                  = array('name' => $name);

        
        if(empty($subscription_id)){
            $response = $this->setresponse->jsonResponse([],"Subscription id is required",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($name)){
            $response = $this->setresponse->jsonResponse([],"Subscription name is required",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($price)){
            $response = $this->setresponse->jsonResponse([],"Subscription price is required",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($duration)){
            $response = $this->setresponse->jsonResponse([],"Duration is required",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($description)){
            $response = $this->setresponse->jsonResponse([],"Description is required",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }
        // else if($this->SubscriptionModel->checifExist($q)->num_rows() == 1){
        //     $response = $this->setresponse->jsonResponse([],"Subscription name already exist",true);
        //     $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
        // }
        else{
            try{

                $where = array("subscription_id" => $subscription_id);

                $payloadsubscription = array(
                    "name"              => $name,
                    "price"             => $price,
                    "duration"          => $duration,
                    "updated_at"        => date("Y-m-d H:i:s"),
                    "description"       => $description,
                );

                $data = $this->SubscriptionModel->update($payloadsubscription,$where);

                if($data){
                    $response = $this->setresponse->jsonResponse($payloadsubscription,"Successfuly updated subscription",false);
                    $this->set_response($response, REST_Controller::HTTP_OK); //This is the respon if success
                }else{
                    $response = $this->setresponse->jsonResponse([],"Error updating subscription",true);
                    $this->set_response($response, REST_Controller::HTTP_NOT_FOUND); //This is the respon if failed
                }
            }catch(Exception $e){
                $response = $this->setresponse->jsonResponse([],$e,true);
                $this->set_response($response, REST_Controller::HTTP_NOT_FOUND); //This is the respon if failed
            }
        }
    }

    public function void(){

        $subscription_id    = $this->input->post("subscription_id");

        $whereById          = array('subscription_id' => $subscription_id);
        $subscriptionData   = $this->SubscriptionModel->checifExist($whereById);
        
        if(empty($subscription_id)){
            $response = $this->setresponse->jsonResponse([],"Subscription id is required",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if($subscriptionData->num_rows() == 0){
            $response = $this->setresponse->jsonResponse([],"No Subsubscription on this id $business_id",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }
        else{
            try{

                $where = array("subscription_id" => $subscription_id);
                $payloadUser = array(
                    'is_active' => 0
                );

                $data = $this->SubscriptionModel->update($payloadUser,$where);

                if($data){
                    $response = $this->setresponse->jsonResponse($payloadUser,"Successfuly void subscription",false);
                    $this->set_response($response, REST_Controller::HTTP_OK); //This is the respon if success
                }else{
                    $response = $this->setresponse->jsonResponse([],"Void subscription Error",true);
                    $this->set_response($response, REST_Controller::HTTP_NOT_FOUND); //This is the respon if failed
                }
            }catch(Exception $e){
                $response = $this->setresponse->jsonResponse([],$e,true);
                $this->set_response($response, REST_Controller::HTTP_NOT_FOUND); //This is the respon if failed
            }
        }
    }

    // public function status(){

    //     $business_id        = $this->input->post("business_id");
    //     $status             = $this->input->post("status");

    //     $whereById          = array('business_id' => $business_id);
    //     $businessData        = $this->BusinessModel->checifExist($whereById);
        
    //     if(empty($business_id)){
    //         $response = $this->setresponse->jsonResponse([],"Business id is required",true);
    //         $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
    //     }else if(empty($status)){
    //         $response = $this->setresponse->jsonResponse([],"Status is required",true);
    //         $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
    //     }
    //     else if($businessData->num_rows() == 0){
    //         $response = $this->setresponse->jsonResponse([],"No business on this business id $business_id",true);
    //         $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
    //     }
    //     else{
    //         try{
    //             $where = array("business_id" => $business_id);
    //             $payloadUser = array(
    //                 'status' => $status
    //             );

    //             $data = $this->BusinessModel->update($payloadUser,$where);

    //             if($data){
    //                 $response = $this->setresponse->jsonResponse($payloadUser,"Successfuly updated business status to $status",false);
    //                 $this->set_response($response, REST_Controller::HTTP_OK); //This is the respon if success
    //             }else{
    //                 $response = $this->setresponse->jsonResponse([],"Errror updating business status",true);
    //                 $this->set_response($response, REST_Controller::HTTP_NOT_FOUND); //This is the respon if failed
    //             }
    //         }catch(Exception $e){
    //             $response = $this->setresponse->jsonResponse([],$e,true);
    //             $this->set_response($response, REST_Controller::HTTP_NOT_FOUND); //This is the respon if failed
    //         }
    //     }
    // }

}
