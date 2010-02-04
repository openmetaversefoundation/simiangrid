<?php
/**
 * Jijawi
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to mail@dasprids.de so I can send you a copy immediately.
 *
 * @category   Jijawi
 * @copyright  Copyright (c) 2009 Ben Scholzen "DASPRiD" (http://www.dasprids.de)
 * @license    http://jijawi.org/license/new-bsd    New BSD License
 * @version    $Id: bootstrap.php 16 2009-07-18 18:08:43Z dasprid $
 */

/**
 * Bootstrap of Jijawi
 * 
 * Take care when editing this file, as the make process requires the markers
 * of both the bootstrap code itself and the data. The bootstrap markers have
 * to be each on single lines above and below the bootstrap code, while the data
 * markers have to be on a single line, not surounded by any code, as the
 * complete line will be replaced by the data. 
 * 
 * @category   Jijawi
 * @package    Jijawi
 * @copyright  Copyright (c) 2009 Ben Scholzen "DASPRiD" (http://www.dasprids.de)
 * @license    http://jijawi.org/license/new-bsd    New BSD License 
 */

/**
 * @see Jijawi_Loader
 */
require_once 'Jijawi/Loader.php';

/* -----BEGIN BOOTSTRAP----- */
$loader = new Jijawi_Loader(
    /* -----BEGIN DATA----- */dirname(__FILE__), true/* -----END DATA----- */
);

$loader->load('Jijawi/Front.php');

$front = new Jijawi_Front($loader);
$front->dispatch();
/* -----END BOOTSTRAP----- */