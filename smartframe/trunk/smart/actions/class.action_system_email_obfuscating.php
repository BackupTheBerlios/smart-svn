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
 * action_system_email_obfuscating class
 *
 */
 
class action_system_email_obfuscating extends action
{
    /**
     * EMAIL_OBFUSCATING
     *
     * @param string $content
     * @return string filtered content
     */  
    function perform()
    {
        $this->B->tpl_buffer_content = str_replace("@", " AT ", $this->B->tpl_buffer_content );   
    }
}

?>
