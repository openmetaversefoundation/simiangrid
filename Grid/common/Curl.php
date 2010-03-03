<?php
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
 * @author     Philip Sturgeon <http://philsturgeon.co.uk/>
 * @copyright  Open Metaverse Foundation
 * @license    http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
 * @link       http://openmetaverse.googlecode.com/
 */

class Curl
{
    private $response;          // Contains the cURL response
   
    private $session;           // Contains the cURL handler for a session
    private $url;               // URL of the session
    private $options = array(); // Populates curl_setopt_array
    private $headers = array(); // Populates extra HTTP headers
    
    public $error_code;         // Error code returned as an int
    public $error_string;       // Error message returned as a string
    public $info;               // Returned after request (elapsed time, etc)
    
    function __construct($url = '')
    {
        if (!$this->is_enabled()) {
            throw new Exception('cURL is not enabled in this PHP installation');
        }
        
        if($url)
        {
        	$this->create($url);
        }
    }
 
    function __call($method, $arguments)
    {
    	if(in_array($method, array('simple_get', 'simple_post', 'simple_put', 'simple_delete')))
    	{
    		$verb = str_replace('simple_', '', $method);
    		array_unshift($arguments, $verb);
    		return call_user_func_array(array($this, '_simple_call'), $arguments);
    	}
    }
    
    /* =================================================================================
     * SIMPLE METHODS 
     * Using these methods you can make a quick and easy cURL call with one line.
     * ================================================================================= */
    
    public function _simple_call($method, $url, $params = array(), $options = array())
    {
        // If a URL is provided, create new session
        $this->create($url);

		$this->{$method}($params, $options);
        
        // Add in the specific options provided
        $this->options($options);

        return $this->execute();
    }
 
    public function simple_ftp_get($url, $file_path, $username = '', $password = '')
    {
        // If there is no ftp:// or any protocol entered, add ftp://
        if(!preg_match('!^(ftp|sftp)://! i', $url)) {
            $url = 'ftp://'.$url;
        }
        
        // Use an FTP login
        if($username != '')
        {
            $auth_string = $username;
            
            if($password != '')
            {
            	$auth_string .= ':'.$password;
            }
            
            // Add the user auth string after the protocol
            $url = str_replace('://', '://'.$auth_string.'@', $url);
        }
        
        // Add the filepath
        $url .= $file_path;

        $this->options(CURLOPT_BINARYTRANSFER, TRUE);
        $this->options(CURLOPT_VERBOSE, TRUE);
        
        return $this->execute();
    }
    
    /* =================================================================================
     * ADVANCED METHODS 
     * Use these methods to build up more complex queries
     * ================================================================================= */

    public function post($params = array(), $options = array()) { 
        
        // If its an array (instead of a query string) then format it correctly
        if(is_array($params)) {
            $result = '';
    	    foreach ($params as $key => $value)
    	        $result .= urlencode($key) . '=' . urlencode($value) . '&';
    	    $params = rtrim($result, '&');
        }
        
        // Add in the specific options provided
        $this->options($options);
        
        $this->http_method('post');
        
        $this->option(CURLOPT_POST, TRUE);
        $this->option(CURLOPT_POSTFIELDS, $params);
    }
    
    public function put($params = array(), $options = array())
    { 
        // If its an array (instead of a query string) then format it correctly
        if(is_array($params))
        {
            $params = http_build_query($params);
        }
        
        // Add in the specific options provided
        $this->options($options);
        
        $this->http_method('put');
        $this->option(CURLOPT_POSTFIELDS, $params);
        
        // Override method, I think this overrides $_POST with PUT data but... we'll see eh?
        $this->option(CURLOPT_HTTPHEADER, array('X-HTTP-Method-Override: PUT'));
    }
    
    public function delete($params, $options = array())
    {
        // If its an array (instead of a query string) then format it correctly
        if(is_array($params))
        {
            $params = http_build_query($params);
        }
        
        // Add in the specific options provided
        $this->options($options);
        
        $this->http_method('delete');
        
        $this->option(CURLOPT_POSTFIELDS, $params);
    }
    
    public function set_cookies($params = array())
    {
        if(is_array($params))
        {
            $params = http_build_query($params);
        }
        
        $this->option(CURLOPT_COOKIE, $params);
        return $this;
    }
    
    public function http_header($header_string)
    {
		$this->headers[] = $header_string;
    }
    
    public function http_method($method)
    {
    	$this->options[CURLOPT_CUSTOMREQUEST] = strtoupper($method);
        return $this;
    }
    
    public function http_login($username = '', $password = '', $type = 'any')
    {
		$this->option(CURLOPT_HTTPAUTH, constant('CURLAUTH_'.strtoupper($type) ));
        $this->option(CURLOPT_USERPWD, $username.':'.$password);
        return $this;
    }
    
    public function proxy($url = '', $port = 80)
    {
        $this->option(CURLOPT_HTTPPROXYTUNNEL. TRUE);
        $this->option(CURLOPT_PROXY, $url.':'. 80);
        return $this;
    }
    
    public function proxy_login($username = '', $password = '')
    {
        $this->option(CURLOPT_PROXYUSERPWD, $username.':'.$password);
        return $this;
    }
    
    public function options($options = array())
    {
        // Merge options in with the rest - done as array_merge() does not overwrite numeric keys
        foreach($options as $option_code => $option_value)
        {
            $this->option($option_code, $option_value);
        }

        // Set all options provided
        curl_setopt_array($this->session, $this->options);
        
        return $this;
    }
    
    public function option($code, $value)
    {
    	if(is_string($code) && !is_numeric($code))
    	{
    		$code = constant('CURLOPT_' . strtoupper($code));
    	}
    	
    	$this->options[$code] = $value;
        return $this;
    }
    
    // Start a session from a URL
    public function create($url)
    {
        // Reset the class
        $this->set_defaults();
        
        $this->url = $url;
        $this->session = curl_init($this->url);
        
        return $this;
    }
    
    // End a session and return the results
    public function execute()
    {
        // Set two default options, and merge any extra ones in
        if(!isset($this->options[CURLOPT_TIMEOUT]))           $this->options[CURLOPT_TIMEOUT] = 30;
        if(!isset($this->options[CURLOPT_RETURNTRANSFER]))    $this->options[CURLOPT_RETURNTRANSFER] = TRUE;
        if(!isset($this->options[CURLOPT_FOLLOWLOCATION]))    $this->options[CURLOPT_FOLLOWLOCATION] = TRUE;
        if(!isset($this->options[CURLOPT_FAILONERROR]))       $this->options[CURLOPT_FAILONERROR] = TRUE;

		if(!empty($this->headers))
		{
			$this->option(CURLOPT_HTTPHEADER, $this->headers); 
		}

        $this->options();

        // Execute the request & and hide all output
        $this->response = curl_exec($this->session);

        // Request failed
        if($this->response === FALSE)
        {
            $this->error_code = curl_errno($this->session);
            $this->error_string = curl_error($this->session);
            
            curl_close($this->session);
            $this->session = NULL;
            return FALSE;
        } 
        
        // Request successful
        else
        {
            $this->info = curl_getinfo($this->session);
            
            curl_close($this->session);
            $this->session = NULL;
            return $this->response;
        }
    }
    
    public function is_enabled()
    {
		return function_exists('curl_init');
    }
    
    private function set_defaults()
    {
        $this->response = '';
        $this->info = array();
        $this->options = array();
        $this->error_code = 0;
        $this->error_string = '';
    }
}
