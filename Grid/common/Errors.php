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

class ErrorHandler
{
    public static function handle_error($code, $message, $file, $line)
    {
        if (0 == error_reporting())
            return;
        
        $priority = 'error';
        
        switch ($code)
        {
            case E_ERROR:
            case E_USER_ERROR:
                $priority = 'error';
                break;
            case E_WARNING:
            case E_USER_WARNING:
                $priority = 'warn';
                break;
            case E_NOTICE:
            case E_USER_NOTICE:
            default:
                $priotity = 'info';
        }
        
        log_message($priority, 'CAUGHT ERROR: ' . $message . ' in ' . $file . ' at line ' . $line);
        throw new ErrorException($message, 0, $code, $file, $line);
    }
}

class ExceptionHandler
{
    public static function handle_exception(Exception $e)
    {
        log_message('error', sprintf("CAUGHT EXCEPTION: Class: %s Code: %d Message: %s -- %s",
            get_class($e), $e->getCode(), $e->getMessage(), print_r($e, true)));
    }
}

set_error_handler(array("ErrorHandler" , "handle_error"));
set_exception_handler(array("ExceptionHandler" , "handle_exception"));
