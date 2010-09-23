<?php
class User_Validation extends Model 
{
	function User_Validation()
	{
		parent::Model();

		// Other stuff
		$this->_table = 'sgf_user_validation';
	}

	function set_code($user_id, $validation_type, $validation_code)
	{
		if ( $user_id == null || $validation_code == null || $validation_type == null ) {
			return null;
		}
		$data = array(
			'user_id' => $user_id,
			'validation_code' => $validation_code,
			'validation_type' => $validation_type
		);
		$this->db->insert($this->_table, $data);
	}
	
	function check_code($validation_type, $validation_code)
	{
		$query = $this->db->get_where($this->_table, array(
			'validation_code' => $validation_code,
			'validation_type' => $validation_type
		));
		$result = $query->result();
		if ( count($result) == 0 ) {
			return null;
		}
		$result = $result[0];
		if ( $result != null ) {
			$user_id = $result->user_id;
		} else {
			$user_id = null;
		}
		return $user_id;
	}
	
	function clear_validation($validation_type, $user_id)
	{		
		$this->db->where('user_id', $user_id);
		$this->db->where('validation_type', $validation_type);
		$this->db->delete($this->_table);
	}	
	
}
