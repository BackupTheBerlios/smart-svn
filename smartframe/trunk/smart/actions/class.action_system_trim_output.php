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
 * action_system_trim_output class - Remove space before and after the template output
 *
 */
 
class action_system_trim_output extends action
{
    /**
     * Remove space before and after the template output
     *
     * @param string $content
     * @return string filtered content
     */  
    function perform()
    {
        $this->B->tpl_buffer_content = trim( $this->B->tpl_buffer_content );  
    }
}

?>
