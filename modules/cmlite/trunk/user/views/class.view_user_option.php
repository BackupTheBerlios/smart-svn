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
 * view_user_option class 
 *
 */
 
class view_user_option extends view
{
     /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'user_option';
    
     /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    var $template_folder = 'modules/user/templates/';
  
    /**
     * Set options for this module
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        // set user options 
        // this event comes from the option module (module_loader.php)
        if(isset($_POST['update_user_options_allowreg']))
        {
            $this->B->sys['option']['user']['allow_register'] = (bool)$_POST['userallowregister'];
            $this->B->sys['option']['user']['register_type']  = $_POST['userregistertype'];
            $this->B->_modified = TRUE;
        }
        
        return TRUE;
    }       
}

?>
