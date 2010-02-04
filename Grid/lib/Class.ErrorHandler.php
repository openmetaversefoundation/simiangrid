<?php

/** Simian grid services
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
 * @author     Jim Radford <http://www.jimradford.com/>
 * @copyright  Open Metaverse Foundation
 * @license    http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
 * @link       http://openmetaverse.googlecode.com/
 */
class ErrorHandler
{
    public static function printError(Exception $e)
    {
        $fp = fopen('exception.log', "a");
        fwrite($fp, sprintf("[%s] class: %s code: %d message: %s\n%s\n========== END ==========", date('r'), get_class($e), $e->getCode(), $e->getMessage(), print_r($e, true)));
        fclose($fp);
    }

    public static function handleError($code, $message, $file, $line)
    {
        require_once ('Log.php');
        //self::printError($e);
        global $logger;
        $priority = PEAR_LOG_ERR;
        if (0 == error_reporting())
        {
            return;
        }
        
        switch ($code)
        {
            case E_WARNING:
            case E_USER_WARNING:
                $priority = PEAR_LOG_WARNING;
                break;
            case E_NOTICE:
            case E_USER_NOTICE:
                $priority = PEAR_LOG_NOTICE;
                break;
            case E_ERROR:
            case E_USER_ERROR:
                $priority = PEAR_LOG_ERR;
                break;
            default:
                $priotity = PEAR_LOG_INFO;
        }
        
        $logger->log($message . ' in ' . $file . ' at line ' . $line, $priority);
        throw new ErrorException($message, 0, $code, $file, $line);
    
    }
}

set_error_handler(array("ErrorHandler" , "handleError"));
