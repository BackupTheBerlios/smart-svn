<?php
/**
 * This is a test controller.
 * SMART can be included in any other project from within an other folder
 *
 *
 */


/* 
 *Secure include of files from this script
 */
define('SF_SECURE_INCLUDE', 1);

// name of this file
define('SF_CONTROLLER', 'test_controller.php');

// relative path to SMART
define('SF_RELATIVE_PATH', '');

// the main controller of SMART
include(SF_RELATIVE_PATH . 'index.php');

?>