<?php if(!defined('BASEPATH')) exit('No direct script allowed');

class AccomodationModel extends CI_Model{

    function checifExist($q) {
		return $this->db->get_where('accomodation',$q);
	}

	function get_info($q = array()) {
		
		$this->db->select("*");
		$this->db->from("accomodation");
		if(sizeof($q) > 0){
			$this->db->where($q);
		}	
		return $this->db->get();
	}

	function create($payload){
		$this->db->trans_start();
		// Insert New business
		$arrQuerybusiness =$this->db->set($payload)->get_compiled_insert('accomodation');
		$this->db->query($arrQuerybusiness);
		return $this->db->trans_complete();
	}

	function update($payload,$where){
		$this->db->trans_start();
		///Where 
		$this->db->where($where);
		// update business
		$arrQuerybusiness =$this->db->set($payload)->get_compiled_update('accomodation');
		$this->db->query($arrQuerybusiness);		
		return $this->db->trans_complete();
	}
	

	
}