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

// Send a init message to all registered event handlers
$base->event->broadcast_run( SF_EVT_INIT );

// If no template request is done load the default template
if(!isset($_REQUEST['tpl']))
{
    $base->tmp_tpl = 'index';
}
else
{
    if(FALSE === ($base->tmp_tpl = sfSecureGPC::get( $_REQUEST['tpl'], 'string' )))
    {
        patErrorManager::raiseError( 'GPC', 'Wrong string Value', "VAR: tpl\nVALUE: ".$_REQUEST['tpl']."\nFILE: ".__FILE__."\nLINE:".__LINE__  );    
    }
}

// set the base template for this module
$base->tpl->readTemplatesFromInput(  "{$base->tpl_folder}/{$base->tmp_tpl}.tpl.html" );    

//  Output all templates
$base->tpl->displayParsedTemplate();

// Send the output buffer to the client
if( OB == TRUE)
{
    ob_end_flush();
}


?>