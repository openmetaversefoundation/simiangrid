<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Simian grid services
 *
 * PHP version 5
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. The name of the author may not be used to endorse or promote products
 *    derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
 * IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
 * NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
 * THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 *
 * @package    SimianGrid
 * @author     John Hurliman <http://software.intel.com/en-us/blogs/author/john-hurliman/>
 * @copyright  Open Metaverse Foundation
 * @license    http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
 * @link       http://openmetaverse.googlecode.com/
 */

class Log
{
	var $log_path;
	var $_threshold    = 1;
	var $_date_fmt     = 'Y-m-d H:i:s';
	var $_use_built_in = FALSE;
	var $_enabled      = TRUE;
	var $_levels       = array('ERROR' => '1', 'WARN' => '2', 'INFO' => '3',  'DEBUG' => '4');

	/**
	 * Constructor
	 *
	 * @access	public
	 */
	function Log()
	{
		$config =& get_config();
		
		$this->log_path = ($config['log_path'] != '') ? $config['log_path'] : BASEPATH.'logs/';
		
		if (!is_dir($this->log_path) OR !is_writable($this->log_path))
		{
			$this->_use_built_in = TRUE;
		}
		
		if (is_numeric($config['log_threshold']))
		{
			$this->_threshold = (int)$config['log_threshold'];
			
			if ($this->_threshold <= 0)
			    $this->_enabled = FALSE;
		}
		
		if ($config['log_date_format'] != '')
		{
			$this->_date_fmt = $config['log_date_format'];
		}
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Write Log File
	 *
	 * Generally this function will be called using the global log_message() function
	 *
	 * @access	public
	 * @param	string	the error level
	 * @param	string	the error message
	 * @param	bool	whether the error is a native PHP error
	 * @return	bool
	 */
	function write_log($level = 'error', $msg, $php_error = FALSE)
	{
		if ($this->_enabled === FALSE)
		{
			return FALSE;
		}
	    
		$level = strtoupper($level);
		
		if (!isset($this->_levels[$level]) OR ($this->_levels[$level] > $this->_threshold))
		{
			return FALSE;
		}
	    
		if ($this->_use_built_in)
		{
		    error_log($level.' '.(($level == 'INFO') ? ' -' : '-').' --> '.$msg);
		    return TRUE;
		}
		
		$filepath = $this->log_path.'log-'.date('Y-m-d').'.php';
		$message  = '';
		
		try
		{
    		if (!file_exists($filepath))
    		{
    			$message .= "<"."?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?".">\n\n";
    		}
    		
    		if (!$fp = @fopen($filepath, 'ab'))
    		{
    			return FALSE;
    		}
    
    		$message .= $level.' '.(($level == 'INFO') ? ' -' : '-').' '.date($this->_date_fmt). ' --> '.$msg."\n";
    		
    		flock($fp, LOCK_EX);	
    		fwrite($fp, $message);
    		flock($fp, LOCK_UN);
    		fclose($fp);
    	    
    		@chmod($filepath, 0666);
    		return TRUE;
		}
		catch (Exception $ex)
		{
		    error_log("Exception occurred logging message \"$msg\" to $filepath (is this file writable?)");
		    return FALSE;
		}
	}
}

function log_message($level = 'error', $message, $php_error = FALSE)
{
	static $LOG;
	
	$config =& get_config();
	if ($config['log_threshold'] == 0)
	{
		return;
	}
	
	if (!isset($LOG))
	{
	    $LOG = new Log();
	}
    
	$LOG->write_log($level, $message, $php_error);
}
