<?php
class User_Session extends Model 
{
	function User_Session()
	{
		parent::Model();

		// Other stuff
		$this->_table = 'sgf_user_session';
	}

	function set_session($user_id, $session_id)
	{
		if ( $user_id == null || $session_id == null ) {
			return null;
		}
		$data = array(
			'user_id' => $user_id,
			'session_id' => $session_id
		);
		$this->db->insert($this->_table, $data);
	}
	
	function get_from_session_id($session_id)
	{
		$sql = "select user_id from sgf_user_session where session_id = \"?\"";
		$query= $this->db->query($sql, array($session_id));
		$result = $query->result();
		if ( $result != null ) {
			$user_id = $result->user_id;
		} else {
			$user_id = null;
		}
		return $user_id;
	}
	
	function clear_sessions($user_id)
	{		
		$this->db->where('user_id', $user_id);
		$this->db->delete($this->_table);
	}	
	
}
