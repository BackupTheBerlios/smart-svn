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

// Assign css style
$base->tpl->assign('design_style', $base->design_style);
// set charset value
$base->tpl->assign('charset', $base->charset);



// Assign registered module handlers
$list = array();

foreach ($base->event->handler_list as $key => $value)
{
    if($value['module'] != 'system')
    {
        
        $list[$key] =  $value;
    }
}
// Assign registered handlers
$base->tpl->assign('handler', $list);

?>