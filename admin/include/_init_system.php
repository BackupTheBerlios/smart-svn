<?php




// Check if this file is included in the environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on'. __FILE__);
}


// Assign css style
$base->tpl->addVar( 'base', 'design_style', $base->design_style );
// set charset value
$base->tpl->addVar( 'base', 'charset', $base->charset );

// Assign registered module handlers
$list = array();

foreach ($base->event->handler_list as $value)
{
    if($value['module'] != 'system')
    {
        $list[] =  $value;
    }
}
// Assign registered handlers
$base->tpl->addRows( 'handler', $list );  

$base->tpl->readTemplatesFromInput(  "/admin/index.tpl.html" );


?>