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
 * action_user_set_options class 
 *
 */
 
class action_user_set_options extends action
{
    /**
     * Set options for this module
     *
     * @param array $data
     */
    function perform( $data )
    {    
        // set user options 
        // this event comes from the option module
        if(isset($_POST['update_user_options_allowreg']))
        {
            $this->B->sys['option']['user']['allow_register'] = (bool)$_POST['userallowregister'];
            $this->B->sys['option']['user']['register_type']  = $_POST['userregistertype'];
            $this->B->_modified = TRUE;
        }
    } 
}

?>
