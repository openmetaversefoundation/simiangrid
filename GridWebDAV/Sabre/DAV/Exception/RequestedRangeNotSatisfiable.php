<?php

/**
 * RequestedRangeNotSatisfiable 
 * 
 * @package Sabre
 * @subpackage DAV
 * @version $Id: RequestedRangeNotSatisfiable.php 706 2010-01-10 15:09:17Z evertpot $
 * @copyright Copyright (C) 2007-2010 Rooftop Solutions. All rights reserved.
 * @author Evert Pot (http://www.rooftopsolutions.nl/) 
 * @license http://code.google.com/p/sabredav/wiki/License Modified BSD License
 */

/**
 * RequestedRangeNotSatisfiable 
 *
 * This exception is normally thrown when the user 
 * request a range that is out of the entity bounds.
 */
class Sabre_DAV_Exception_RequestedRangeNotSatisfiable extends Sabre_DAV_Exception {

    function getHTTPCode() {

        return 416;

    }

}

