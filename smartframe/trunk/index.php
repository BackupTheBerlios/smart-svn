<?php
// ----------------------------------------------------------------------
// Smart PHP Framework
// Copyright (c) 2004
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * The public main file
 *
 */
 
/*
 * Secure include of files from this script
 */
define('SF_SECURE_INCLUDE', 1);

// Define the absolute path to SMART
//
define('SF_BASE_DIR', dirname(__FILE__));

// Define section area
define('SF_SECTION', 'public');

// Include the base file
include( SF_BASE_DIR . "/admin/include/base.inc.php" );

// Send a authentication message to the event handlers which takes the authentication part
$B->M( SF_AUTH_MODULE, EVT_AUTHENTICATE );

// Send a init message to all registered event handlers
$B->B( SF_EVT_INIT );

// If no template request is done load the default template
if (!isset($_REQUEST['tpl']))
{
    $B->tmp_tpl = 'index';
}
else
{
    // get the requested template name and check if it contains only chars a-z
    if (FALSE === ($B->tmp_tpl = sfSecureGPC::get( $_REQUEST['tpl'], 'string' )))
    {
        trigger_error( "WRONG VAR FORMAT: tpl\nVALUE: " . $_REQUEST['tpl'] . "\nFILE: " . __FILE__ . "\nLINE:" . __LINE__  );    
    }
}

// build the whole requested template file path
$B->template_file = SF_BASE_DIR . '/' . $B->sys['option']['tpl'] . '_' . $B->tmp_tpl . '.tpl.php';

// check if the requested template exist
if (@file_exists( $B->template_file ))
{
    // Include the requested template
    include ( $B->template_file );
}
else
{
    // on error
    die ("The requested template file '{$B->template_file}' doent exist! Please contact the administrator {$B->sys['option']['email']}");
}

// Send the output buffer to the client
if ( SF_OB == TRUE)
{
    ob_end_flush();
}
?>