<?php if(!defined('BASEPATH')) exit('No direct script allowed');

class ListingModel extends CI_Model{

    function checifExist($q) {
		return $this->db->get_where('listings',$q);
	}

    function get($table,$where){
        return $this->db->get_where($table,$where);
    }

	function get_info($q = array()) {
		$this->db->select("
            listings.listing_id,
            listings.listing_name,
            listings.listing_type,
            listings.vehicle_name,
            listings.accomodation,
            listings.quantity,
            listings.price,
            listings.capacity,
            listings.booking_type,
            listings.description,
            listings.date_added,
            business.business_id,
            business.business_type,
            business.business_name,
            business.business_address,
            business.business_email,
            business.business_phone,
            business.business_image,
            business.owner_id,
            business.status,
            business.is_active");
            
		$this->db->from("listings");
		if(sizeof($q) > 0){
			$this->db->where($q);
		}	
        $this->db->join("business","business.business_id  = listings.business_id");
        
		return $this->db->get();
	}

	function create($payload){
		$this->db->trans_start();
		// Insert Listing
		$arrQueryListing =$this->db->set($payload['listing'])->get_compiled_insert('listings');
		$this->db->query($arrQueryListing);
        // Insert Listing Address
		// $arrQueryListingAddress =$this->db->set($payload['listing_address'])->get_compiled_insert('listing_address');
		// $this->db->query($arrQueryListingAddress);
		return $this->db->trans_complete();
	}

	function update($payload,$where){
        $this->db->trans_start();
		// Update Listing
        if(array_key_exists("listings",$payload)){
            $this->db->where($where['listing']);
            $arrQueryListing =$this->db->set($payload['listing'])->get_compiled_update('listings');
            $this->db->query($arrQueryListing);
        }
		return $this->db->trans_complete();
	}
	

	
}