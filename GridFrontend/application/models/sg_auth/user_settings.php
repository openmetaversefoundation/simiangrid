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
		return $this->_set_setting($user_id, 'style', $style);
	}
	
	function get_style($user_id)
	{
		return $this->_get_setting($user_id, 'style');
	}

	function set_language($user_id, $language) {
		return $this->_set_setting($user_id, 'language', $language);
	}

	function get_language($user_id)
	{
		return $this->_get_setting($user_id, 'language');
	}
	
	function _set_setting($user_id, $key, $value)
	{
		$data = array(
			'user_id' => $user_id,
			$key => $value
		);
		$this->_update_settings($user_id, $data);
	}
	
	function _get_setting($user_id, $key)
	{
		$result = $this->_get_settings($user_id);
		if ( $result != null ) {
			$value = $result->$key;
		} else {
			$value = null;
		}
		return $value;
	}

	function _update_settings($user_id, $data)
	{
		$existing = $this->_get_settings($user_id);
		if ( $existing ) {
			if ( !empty($data['style']) ) {
				$existing->style = $data['style'];
			}
			if ( !empty($data['language']) ) {
				$existing->language = $data['language'];
			}
			$this->db->where('user_id', $user_id);
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
