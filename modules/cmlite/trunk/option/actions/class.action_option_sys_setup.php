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
 * action_option_sys_setup class 
 *
 */
 
class action_option_sys_setup extends action
{
    /**
     * Perform on admin requests for this module
     *
     * @param array $data
     */
    function perform(  $data = FALSE  )
    {    
        $success = TRUE;
        // The module name and version
        $this->B->conf_val['module']['option']['name']     = 'option';
        $this->B->conf_val['module']['option']['version']  = MOD_OPTION_VERSION;
        $this->B->conf_val['module']['option']['mod_type'] = 'openpublisher';

        // Set some options
        $this->B->conf_val['option']['tpl']  = SF_DEFAULT_TEMPLATE_FOLDER;
        $this->B->conf_val['option']['view'] = SF_DEFAULT_VIEW_FOLDER;     
        $this->B->conf_val['option']['url']  = SF_BASE_LOCATION;
        $this->B->conf_val['option']['site_title'] = 'Site title';
        $this->B->conf_val['option']['site_desc']  = 'My first site';
        $this->B->conf_val['option']['email']   = 'admin@foo.com';
        $this->B->conf_val['option']['charset'] = $_POST['charset'];
     
        return $success;    
    } 
}

?>
