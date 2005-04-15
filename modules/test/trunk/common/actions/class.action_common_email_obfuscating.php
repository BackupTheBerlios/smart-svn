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
 * action_common_email_obfuscating class
 *
 */
 
class action_common_email_obfuscating extends action
{
    /**
     * EMAIL_OBFUSCATING
     *
     * @param string $content
     * @return string filtered content
     */  
    function perform( $data = FALSE )
    {
        switch($data['type'])
        {
            case '40':
                $this->B->tpl_buffer_content = preg_replace("/(href=[\"\']mailto:[^@]+)@/", "\\1%40", $this->B->tpl_buffer_content );
                $this->B->tpl_buffer_content = str_replace("@", " [&#". ord('@').";] ", $this->B->tpl_buffer_content );
                break;
            case 'at':
            case 'default':
                $this->B->tpl_buffer_content = str_replace("@", " [@] ", $this->B->tpl_buffer_content );   
                break;            
        }
        return SF_IS_VALID_ACTION;
    }    
}

?>
