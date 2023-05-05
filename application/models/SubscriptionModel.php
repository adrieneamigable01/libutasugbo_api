<?php if(!defined('BASEPATH')) exit('No direct script allowed');

class SubscriptionModel extends CI_Model{

    function checifExist($q) {
		return $this->db->get_where('subscription',$q);
	}

	function get_info($q = array()) {
		
		$this->db->select("
            subscription.id,
            subscription.subscription_id,
            subscription.name,
            subscription.price,
            subscription.duration,
            subscription.created_at,
            subscription.description,
            subscription.is_active,
        ");
		$this->db->from("subscription");
		if(sizeof($q) > 0){
			$this->db->where($q);
		}	
		return $this->db->get();
	}

	function create($payload){
		$this->db->trans_start();
		// Insert New business
		$arrQuerybusiness =$this->db->set($payload)->get_compiled_insert('subscription');
		$this->db->query($arrQuerybusiness);
		return $this->db->trans_complete();
	}

	function update($payload,$where){
		$this->db->trans_start();
		///Where 
		$this->db->where($where);
		// update business
		$arrQuerybusiness =$this->db->set($payload)->get_compiled_update('subscription');
		$this->db->query($arrQuerybusiness);		
		return $this->db->trans_complete();
	}
	

	
}