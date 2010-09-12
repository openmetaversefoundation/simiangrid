<?php
class User_Settings extends Model 
{
	function User_Settings()
	{
		parent::Model();

		// Other stuff
		$this->_table = 'sgf_user_settings';
	}

	function set_style($user_id, $style) {
		if ( $user_id == null || $style == null ) {
			return null;
		}
		$data = array(
			'user_id' => $user_id,
			'style' => $style
		);
		$this->_update_settings($user_id, $data);
	}
	
	function get_style($user_id)
	{
		$result = $this->_get_settings($user_id);
		if ( $result != null ) {
			$style = $result->style;
		} else {
			$style = null;
		}
		return $style;
	}

	function _update_settings($user_id, $data)
	{
		$existing = $this->_get_settings($user_id);
		if ( $existing ) {
			if ( !empty($data['style']) ) {
				$existing->style = $data['style'];
			}
			$this->db->update($this->_table, $existing);
		} else {
			$this->db->insert($this->_table, $data);
		}
	}

	function _get_settings($user_id)
	{
		$query = $this->db->get_where($this->_table, array('user_id' => $user_id));
		$result = $query->result();
		if ( $result != null && count($result) != 0 ) {
			return $result[0];
		} else {
			return null;
		}
	}

	function delete_settings($user_id)
	{		
		$this->db->where('user_id', $user_id);
		$this->db->delete($this->_table);
	}
}
