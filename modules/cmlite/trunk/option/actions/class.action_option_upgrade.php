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
 * action_option_upgrade class 
 *
 */
 
class action_option_upgrade extends action
{
    /**
     * upgarde the common module
     *
     * @param array $data
     * @return bool true or false on error
     */
    function perform( $data )
    {
        // version prior to 0.1.5
        if(version_compare( $this->B->sys['module']['option']['version'], '0.1.5' , '<' ) == 1)
        {
            $this->B->sys['option']['view'] = SF_DEFAULT_VIEW_FOLDER; 
            $this->B->sys['option']['tpl']  = SF_DEFAULT_TEMPLATE_FOLDER;
        }
        return TRUE;
    } 
}

?>