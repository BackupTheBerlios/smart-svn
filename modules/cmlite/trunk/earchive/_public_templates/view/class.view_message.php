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
 * view_message class of the template "group_message.tpl.php"
 *
 */
 
class view_message extends view
{
     /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'message';
 
    /**
     * Execute the view of the template "group_message.tpl.php"
     *
     * @return mixed (object) this object on success else (bool) false on error
     * @todo validate $_GET['mid']
     */
    function perform()
    {       
        /* get the requested message and store the result in the array $B->tpl_msg 
         assign template vars with message data */
        $this->B->M( MOD_EARCHIVE, 
                     'get_message', 
                     array( 'mid'    => (int)$_GET['mid'], 
                            'var'    => 'tpl_msg',
                            'fields' => array('subject','sender','mdate','body','folder')));
        
        /* get the message attachments and store the result in the array $B->tpl_attach */
        $this->B->M( MOD_EARCHIVE, 
                     'get_attachments', 
                     array( 'var'    => 'tpl_attach', 
                            'mid'    => (int)$_GET['mid'],  
                            'fields' => array('aid', 'mid', 'lid', 'file', 'size', 'type')));

        return $this;
    }    
}

?>
