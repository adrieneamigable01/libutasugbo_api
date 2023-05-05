<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Listing extends BD_Controller {
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('ListingModel');
        $this->load->model('BusinessModel');
        $this->load->library('SetResponse',NULL,'setresponse');
        $this->load->library('Uuid',NULL,'uuid');
        $this->auth();
    }

   

    public function index()
    {
        $listing_id             = $this->input->get("listing_id");
        $business_id            = $this->input->get("business_id");
        // $status                 = $this->input->get("status");
        $is_active              = $this->input->get("is_active");
        $where_listing_info    = array();
   
        if(isset($listing_id)){
            $where_listing_info['listings.listing_id']   = $listing_id;
        }
        if(isset($business_id)){
            $where_listing_info['listings.business_id']     = $business_id;
        }
        if(isset($is_active)){
            $where_listing_info['listings.is_active']    = $is_active;
        }
        // if(isset($status)){
        //     $where_listing_info['listing.status']       = $status;
        // }
       
        $listing_info           = $this->ListingModel->get_info($where_listing_info);
        $output                 = !empty($listing_id) ? $listing_info->row() : $listing_info->result();
        $response               = $this->setresponse->jsonResponse($output,"Success",false);
        $this->set_response($response, REST_Controller::HTTP_OK); //This is the respon if success
    }

    public function create(){

        
        // $this->user_data = $decoded;

        $tokenData          = $this->setresponse->getTokenData();

        // Listing Data
        $listing_id         = $this->uuid->guidv4('l-'.date("YmdHis"));
        $listing_name       = $this->input->post("listing_name");
        $listing_type       = $this->input->post("listing_type");
        $vehicle_name       = $this->input->post("vehicle_name");
        $accomodation       = $this->input->post("accomodation");
        $quantity           = $this->input->post("quantity");
        $price	            = $this->input->post("price");
        $capacity           = $this->input->post("capacity");
        $booking_type       = $this->input->post("booking_type");
        $description        = $this->input->post("description");
        $available_days     = $this->input->post("available_days");
        
        $date_added         = date("Y-m-d H:i:s");

        $owner_id               = $tokenData->user_id;

        $business = $this->BusinessModel->get_info(array("owner_id" => $owner_id));

        if(empty($listing_name)){
            $response = $this->setresponse->jsonResponse([],"Listing name is required",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($listing_type)){
            $response = $this->setresponse->jsonResponse([],"Listing type is required",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($price)){
            $response = $this->setresponse->jsonResponse([],"Price type is required",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($description)){
            $response = $this->setresponse->jsonResponse([],"Description type is required",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else{
            try{

                $payloadListing = array(
                    'listing' => array(
                        "listing_id"        => $listing_id,
                        "listing_name"      => $listing_name,
                        "listing_type"      => $listing_type,
                        "vehicle_name"      => $vehicle_name,
                        "accomodation"      => $accomodation,
                        "quantity"          => $quantity,
                        "price"             => $price,
                        "capacity"          => $capacity,
                        "booking_type"      => $booking_type,
                        "description"       => $description,   
                        "date_added"        => $date_added,
                        "available_days"    => $available_days,
                        "business_id"       => $business->row()->business_id,
                    ),
                );

                $data = $this->ListingModel->create($payloadListing);

                if($data){
                    $response = $this->setresponse->jsonResponse($payloadListing,"Successfuly create listing",false);
                    $this->set_response($response, REST_Controller::HTTP_OK); //This is the respon if success
                }else{
                    $response = $this->setresponse->jsonResponse([],"Error creating listing",true);
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

        // Listing Data
        $listing_id         = $this->input->post("listing_id");
        $listing_name       = $this->input->post("listing_name");
        $listing_type       = $this->input->post("listing_type");
        $vehicle_name       = $this->input->post("vehicle_name");
        $accomodation       = $this->input->post("accomodation");
        $quantity           = $this->input->post("quantity");
        $price	            = $this->input->post("price");
        $capacity           = $this->input->post("capacity");
        $booking_type       = $this->input->post("booking_type");
        $description        = $this->input->post("description");
        $date_added         = date("Y-m-d H:i:s");

        $owner_id               = $tokenData->user_id;

        $business = $this->BusinessModel->get_info(array("owner_id" => $owner_id));

        if(empty($listing_name)){
            $response = $this->setresponse->jsonResponse([],"Listing name is required",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($listing_type)){
            $response = $this->setresponse->jsonResponse([],"Listing type is required",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($price)){
            $response = $this->setresponse->jsonResponse([],"Price type is required",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($description)){
            $response = $this->setresponse->jsonResponse([],"Description type is required",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if($business->num_rows() == 0){
            $response = $this->setresponse->jsonResponse([],"You have no business yet, please register one!",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }
        else{
            try{

                $where = array(
                    'listing' => array(
                        "listing_id"        => $listing_id,
                    ),
                );

                $payloadListing = array(
                    'listing' => array(
                        "listing_name"      => $listing_name,
                        "listing_type"      => $listing_type,
                        "vehicle_name"      => $vehicle_name,
                        "accomodation"      => $accomodation,
                        "quantity"          => $quantity,
                        "price"             => $price,
                        "capacity"          => $capacity,
                        "booking_type"      => $booking_type,
                        "description"       => $description
                    ),
                );

                $data = $this->ListingModel->update($payloadListing,$where);

                if($data){
                    $responseData = array(
                        'listing' => $this->ListingModel->get("listings",array("listing_id"=>$listing_id))->row(),
                    );
                    $response = $this->setresponse->jsonResponse($responseData,"Successfuly update listing",false);
                    $this->set_response($response, REST_Controller::HTTP_OK); //This is the respon if success
                }else{
                    $response = $this->setresponse->jsonResponse([],"Error updateing business",true);
                    $this->set_response($response, REST_Controller::HTTP_NOT_FOUND); //This is the respon if failed
                }

            }catch(Exception $e){
                $response = $this->setresponse->jsonResponse([],$e,true);
                $this->set_response($response, REST_Controller::HTTP_NOT_FOUND); //This is the respon if failed
            }
        }
    }



    public function void(){

        $listing_id         = $this->input->post("listing_id");

        $whereById          = array('listing_id' => $listing_id);
        $listingData       = $this->ListingModel->checifExist($whereById);
        
        if(empty($listing_id)){
            $response = $this->setresponse->jsonResponse([],"Empty listing id",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if($listingData->num_rows() == 0){
            $response = $this->setresponse->jsonResponse([],"No listing on this id $business_id",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }
        else{
            try{
                $where          = array(
                    'listing' => array('listing_id' => $listing_id),
                );
                $payloadUser = array(
                    'listing'=>array('is_active' => 0)
                );

                $data = $this->ListingModel->update($payloadUser,$where);

                if($data){
                    $response = $this->setresponse->jsonResponse($payloadUser,"Successfuly void listing",false);
                    $this->set_response($response, REST_Controller::HTTP_OK); //This is the respon if success
                }else{
                    $response = $this->setresponse->jsonResponse([],"Void listing Error",true);
                    $this->set_response($response, REST_Controller::HTTP_NOT_FOUND); //This is the respon if failed
                }
            }catch(Exception $e){
                $response = $this->setresponse->jsonResponse([],$e,true);
                $this->set_response($response, REST_Controller::HTTP_NOT_FOUND); //This is the respon if failed
            }
        }
    }

    public function status(){

        $listing_id        = $this->input->post("listing_id");
        $status             = $this->input->post("status");

        $whereById          = array('listing_id' => $listing_id);
        $listingData       = $this->ListingModel->checifExist($whereById);
        
        if(empty($listing_id)){
            $response = $this->setresponse->jsonResponse([],"Listing id is required",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }else if(empty($status)){
            $response = $this->setresponse->jsonResponse([],"Status is required",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }
        else if($listingData->num_rows() == 0){
            $response = $this->setresponse->jsonResponse([],"No listing on this listing id $business_id",true);
            $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
        }
        else{
            try{
                $where          = array(
                    'listing' => array('listing_id' => $listing_id),
                );
                $payloadUser = array(
                    'listing' => array(
                        'status' => $status
                    )
                );

                $data = $this->ListingModel->update($payloadUser,$where);

                if($data){
                    $response = $this->setresponse->jsonResponse($payloadUser,"Successfuly updated listing status to $status",false);
                    $this->set_response($response, REST_Controller::HTTP_OK); //This is the respon if success
                }else{
                    $response = $this->setresponse->jsonResponse([],"Errror updating listing status",true);
                    $this->set_response($response, REST_Controller::HTTP_NOT_FOUND); //This is the respon if failed
                }
            }catch(Exception $e){
                $response = $this->setresponse->jsonResponse([],$e,true);
                $this->set_response($response, REST_Controller::HTTP_NOT_FOUND); //This is the respon if failed
            }
        }
    }

}
