<?php

/**
* 
* Tests filters and plugins
*
* @version $Id: 5_filters.php,v 1.2 2004/06/22 01:17:00 pmjones Exp $
* 
*/


error_reporting(E_ALL);

require_once 'Savant2.php';

$conf = array(
	'template_path' => 'templates',
	'resource_path' => 'resources'
);

$savant =& new Savant2($conf);

// set up filters
$savant->loadFilter('colorizeCode');
$savant->loadFilter('trimwhitespace');
$savant->loadFilter('fester');

// run through the template
$savant->display('filters.tpl.php');

// do it again to test object persistence
$savant->display('filters.tpl.php');

// do it again to test object persistence
$savant->display('filters.tpl.php');


print_r($savant);

?>