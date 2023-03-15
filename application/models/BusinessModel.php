<?php if(!defined('BASEPATH')) exit('No direct script allowed');

class BusinessModel extends CI_Model{

    function checifExist($q) {
		return $this->db->get_where('business',$q);
	}

	function get_info($q = array()) {
		
		$this->db->select("business.business_id,business.business_type,business.business_name,business.business_address,business.business_email,business.business_phone,business.business_image,business.owner_id,business.status,business.is_active");
		$this->db->from("business");
		if(sizeof($q) > 0){
			$this->db->where($q);
		}	
		return $this->db->get();
	}

	function create($payload){
		$this->db->trans_start();
		// Insert New business
		$arrQuerybusiness =$this->db->set($payload)->get_compiled_insert('business');
		$this->db->query($arrQuerybusiness);
		return $this->db->trans_complete();
	}

	function update($payload,$where){
		$this->db->trans_start();
		///Where 
		$this->db->where($where);
		// update business
		$arrQuerybusiness =$this->db->set($payload)->get_compiled_update('business');
		$this->db->query($arrQuerybusiness);		
		return $this->db->trans_complete();
	}
	

	
}