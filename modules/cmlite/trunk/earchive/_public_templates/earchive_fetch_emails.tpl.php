<?php 

/**
 * Fetch messages from registered email accounts
 *
 * To execute this view you have to add the url variable "passID"
 * You can change this passID in the admin area
 * 
 */

if (!defined('SF_SECURE_INCLUDE')) exit; 

if( $_GET['passID'] == $B->sys['option']['passID'] )
{
    $B->M( MOD_EARCHIVE, 'fetch_emails', array('status' => 'status>1') );   
}

exit;

?>   

