<?php if(!defined('BASEPATH')) exit('No direct script allowed');

class ListingModel extends CI_Model{

    function checifExist($q) {
		return $this->db->get_where('listing',$q);
	}

    function get($table,$where){
        return $this->db->get_where($table,$where);
    }

	function get_info($q = array()) {
		$this->db->select("
            listing.listing_id,
            listing.listing_name,
            listing.listing_name,
            listing.listing_type,
            listing.size,
            listing.price,
            listing.accomodates,
            listing.bathrooms,
            listing.bedrooms,
            listing.description,
            listing.amenities,
            listing.recommendations,
            listing.rules,
            listing.directions,
            listing.status,
            listing.date_added,
            listing.is_active,
            listing_address.listing_address_id,
            listing_address.address_line1,
            listing_address.address_line2,
            listing_address.country,
            listing_address.province,
            listing_address.city,
            listing_address.zip_code,
            listing_address.map_location");
            
		$this->db->from("listing");
		if(sizeof($q) > 0){
			$this->db->where($q);
		}	
        $this->db->join("listing_address","listing_address.listing_id  = listing.listing_id");
		return $this->db->get();
	}

	function create($payload){
		$this->db->trans_start();
		// Insert Listing
		$arrQueryListing =$this->db->set($payload['listing'])->get_compiled_insert('listing');
		$this->db->query($arrQueryListing);
        // Insert Listing Address
		$arrQueryListingAddress =$this->db->set($payload['listing_address'])->get_compiled_insert('listing_address');
		$this->db->query($arrQueryListingAddress);
		return $this->db->trans_complete();
	}

	function update($payload,$where){
        $this->db->trans_start();
		// Update Listing
        if(array_key_exists("listing",$payload)){
            $this->db->where($where['listing']);
            $arrQueryListing =$this->db->set($payload['listing'])->get_compiled_update('listing');
            $this->db->query($arrQueryListing);
        }
        // Update Listing Address
        if(array_key_exists("listing_address",$payload)){
            $this->db->where($where['listing_address']);    
            $arrQueryListingAddress =$this->db->set($payload['listing_address'])->get_compiled_update('listing_address');
            $this->db->query($arrQueryListingAddress);
        }
       
		return $this->db->trans_complete();
	}
	

	
}