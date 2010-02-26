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

define('PEAR_LOG_EMERG',    0);     /* System is unusable */
define('PEAR_LOG_ALERT',    1);     /* Immediate action required */
define('PEAR_LOG_CRIT',     2);     /* Critical conditions */
define('PEAR_LOG_ERR',      3);     /* Error conditions */
define('PEAR_LOG_WARNING',  4);     /* Warning conditions */
define('PEAR_LOG_NOTICE',   5);     /* Normal but significant */
define('PEAR_LOG_INFO',     6);     /* Informational */
define('PEAR_LOG_DEBUG',    7);     /* Debug-level messages */

class LogWriter
{
    private $level;
    private $service;
    
    function __construct($logLevel, $service)
    {
        $this->level = $logLevel;
        $this->service = $service;
    }

    function emerg($message)
    {
        if ($this->level >= PEAR_LOG_EMERG)
            error_log('[EMERG] ' . $this->service . ': ' . $message);
    }

    function alert($message)
    {
        if ($this->level >= PEAR_LOG_ALERT)
            error_log('[ALERT] ' . $this->service . ': ' . $message);
    }

    function crit($message)
    {
        if ($this->level >= PEAR_LOG_CRIT)
            error_log('[CRIT] ' . $this->service . ': ' . $message);
    }

    function err($message)
    {
        if ($this->level >= PEAR_LOG_ERR)
            error_log('[ERR] ' . $this->service . ': ' . $message);
    }

    function warning($message)
    {
        if ($this->level >= PEAR_LOG_WARNING)
            error_log('[WARNING] ' . $this->service . ': ' . $message);
    }

    function notice($message)
    {
        if ($this->level >= PEAR_LOG_NOTICE)
            error_log('[NOTICE] ' . $this->service . ': ' . $message);
    }

    function info($message)
    {
        if ($this->level >= PEAR_LOG_INFO)
            error_log('[INFO] ' . $this->service . ': ' . $message);
    }

    function debug($message)
    {
        if ($this->level >= PEAR_LOG_DEBUG)
            error_log('[DEBUG] ' . $this->service . ': ' . $message);
    }
}

class Logger
{
    private $LogInstance;

    function __construct($cfg_file, $service)
    {
        $logLevel = PEAR_LOG_WARNING;
        
        $config = parse_ini_file($cfg_file, true);
        $dir = dirname($cfg_file);
        
        if (isset($config['Services']['log_level']) && $config['Services']['log_level'] != '')
        {
            $logLevel = constant(str_replace('SIMIAN_', 'PEAR_', $config['Services']['log_level']));
        }
        
        $this->LogInstance = new LogWriter($logLevel, $service);
    }

    public function getInstance()
    {
        return $this->LogInstance;
    }
}
