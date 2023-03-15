<?php if(!defined('BASEPATH')) exit('No direct script allowed');

class UsersModel extends CI_Model{

	function authenticate($q) {
		return $this->db->get_where('users',$q);
	}

	function get_user_info($q = array()) {
		
		$this->db->select("users.id,users.user_id,users.username,users.level,users.user_type,users.role,user_info.user_info_id,user_info.lastname,user_info.middlename,user_info.firstname,user_info.mobile,user_info.email,users.is_active");
		$this->db->from("users");
		if(sizeof($q) > 0){
			$this->db->where($q);
		}	
		$this->db->join("user_info","user_info.user_id = users.user_id");
		return $this->db->get();
	}

	function register_user($payload){
		$this->db->trans_start();
		// Insert User
		$arrQueryUser =$this->db->set($payload['user'])->get_compiled_insert('users');
		$this->db->query($arrQueryUser);
		// Insert User Info
		$arrQueryUserInfo =$this->db->set($payload['user_info'])->get_compiled_insert('user_info');
		$this->db->query($arrQueryUserInfo);
		return $this->db->trans_complete();
	}

	function update_user($payload,$where){
		$this->db->trans_start();

		///Where 
		$this->db->where($where);

		// Insert User
		$arrQueryUser =$this->db->set($payload['user'])->get_compiled_update('users');
		$this->db->query($arrQueryUser);
		// Insert User Info
		if(array_key_exists("user_info",$payload)){
			$arrQueryUserInfo =$this->db->set($payload['user_info'])->get_compiled_update('user_info');
			$this->db->query($arrQueryUserInfo);
		}
		
		return $this->db->trans_complete();
	}
	

	
}