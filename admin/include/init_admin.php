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

// Check if this file is included in the environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on '. __FILE__);
}


// Assign registered module handlers
$B->mod_list = array();

foreach ($B->handler_list as $key => $value)
{
    if($value['module'] != 'SYSTEM')
    {
        $B->mod_list[$key] =  $value;
    }
}

?>