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
 * Module loader of the option module
 *
 */

// Check if this file is included in the Smart environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

if(!isset($_GET['mf']))
{
    $B->templ = array();
    $directory =& dir(SF_BASE_DIR);

    while (false != ($dirname = $directory->read()))
    {
        if (FALSE == is_dir(SF_BASE_DIR . '/' . $dirname))
        {
            if(preg_match("/(^[^_]*).*\.tpl\.php$/", $dirname, $tmp))
            {
                if(!in_array($tmp[1], $B->templ))
                    $B->templ[] = $tmp[1];
            }
        }
    }

    $directory->close();
}

// set the base template for this module
$B->module = SF_BASE_DIR . '/admin/modules/option/templates/index.tpl.php';    

// Assign module handler name
$B->this_module = MOD_OPTION ;

?>
