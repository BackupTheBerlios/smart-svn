<?php
// ----------------------------------------------------------------------
// Smart (PHP Framework)
// Copyright (c) 2004
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * The setup file for this module group
 *
 */
 
/*
 * Secure include of files from this script
 */
define('SF_SECURE_INCLUDE', 1);

// Init error array
$B->setup_error = array();

// launch setup
if( $_POST['do_setup'] )
{
    // Send a setup message to all registered handlers
    $B->M( MOD_SYSTEM,       EVT_SETUP );
    $B->M( MOD_USER,         EVT_SETUP );
    $B->M( MOD_EARCHIVE,     EVT_SETUP );
    $B->M( MOD_OPTION,       EVT_SETUP );
    
    // close db connection if present
    if(is_object($B->db))
        $B->db->disconnect();
        
    // check on errors before proceed
    if( count($B->setup_error) == 0 )
    {      
        $B->conf_val['info']['status'] = TRUE;
        $B->conf->setConfigValues( $B->conf_val );
        $B->conf->writeConfigFile( "config_system.xml.php", array('filetype' => 'xml', 'mode' => 'pretty') );
        
        @header('Location: ./index.php');
        exit;  
    }
    else
    {
        $B->form_host        = htmlspecialchars($B->util->stripSlashes($_POST['dbhost']));
        $B->form_user        = htmlspecialchars($B->util->stripSlashes($_POST['dbuser']));
        $B->form_dbname      = htmlspecialchars($B->util->stripSlashes($_POST['dbname']));
        $B->form_tableprefix = htmlspecialchars($B->util->stripSlashes($_POST['dbtablesprefix']));
        $B->form_sysname     = htmlspecialchars($B->util->stripSlashes($_POST['sysname']));
        $B->form_syslastname = htmlspecialchars($B->util->stripSlashes($_POST['syslastname']));
        $B->form_syslogin    = htmlspecialchars($B->util->stripSlashes($_POST['syslogin']));
    }
}

// Include the setup template
include(  SF_BASE_DIR . '/admin/modules/setup/index.tpl.php' ); 

// Send the output buffer to the client
if( SF_OB == TRUE)
{
    ob_end_flush();
}

// Basta
exit;

?>