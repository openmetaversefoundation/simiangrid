<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * SabreDAV's PHP autoloader
 *
 * If you love the autoloader, and don't care as much about performance, this
 * file register a new autoload function using spl_autoload_register. 
 *
 * @package Sabre
 * @subpackage DAV
 * @version $Id: Sabre.autoload.php 707 2010-01-10 15:16:42Z evertpot $
 * @copyright Copyright (C) 2007-2010 Rooftop Solutions. All rights reserved.
 * @author Evert Pot (http://www.rooftopsolutions.nl/) 
 * @license http://code.google.com/p/sabredav/wiki/License Modified BSD License
 */

function Sabre_DAV_autoload($className) {

    if(strpos($className,'Sabre_')===0) {

        include dirname(__FILE__) . '/' . str_replace('_','/',$className) . '.php';

    }

}

spl_autoload_register('Sabre_DAV_autoload');
