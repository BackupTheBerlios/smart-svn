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
 * view_attach class
 *
 */

include_once ('HTTP/Download.php');
 
class view_attach extends view
{
     /**
     * Template render flag
     * @var bool $render_template
     */    
    var $render_template = SF_TEMPLATE_RENDER_NONE; // We need no template here
    
    /**
     * Provide a file for download
     *
     * @return bool true on success else false
     */
    function perform()
    {
        //Get the demanded attachment and send it to the client

        //get the top requested email list attachment folder 
        M( MOD_EARCHIVE, 
           'get_list', 
           array( 'var'    => 'tpl_list', 
                  'lid'    => (int)$_GET['lid'], 
                  'fields' => array('folder'))); 

        //get the requested message attachment folder
        M( MOD_EARCHIVE, 
           'get_message', 
           array( 'var'    => 'tpl_msg', 
                  'mid'    => (int)$_GET['mid'], 
                  'lid'    => (int)$_GET['lid'], 
                  'fields' => array('folder')));                        

        //get the attachment file name, type
        M( MOD_EARCHIVE, 
           'get_attach', 
           array( 'var'    => 'tpl_attach', 
                  'aid'    => (int)$_GET['aid'], 
                  'mid'    => (int)$_GET['mid'], 
                  'lid'    => (int)$_GET['lid'], 
                  'fields' => array('file','type')));

        // set params to send http header and content
        $this->B->attach_params = array(
                     'file'                  => SF_RELATIVE_PATH . '/data/earchive/'.$this->B->tpl_list['folder'].'/'.$this->B->tpl_msg['folder'].'/'.stripslashes($this->B->tpl_attach['file']),
                     'contenttype'           => $this->B->tpl_attach['type'],
                     'contentdisposition'    => array(HTTP_DOWNLOAD_ATTACHMENT, stripslashes($this->B->tpl_attach['file'])),
                     );

        // stop output buffering
        // we dont need those headers which has been allready sended
        //
        while ( @ob_end_clean() );

        // send header and content
        $error = HTTP_Download::staticSend($this->B->attach_params, false);

        if (TRUE !== $error) 
        {
            trigger_error($error->message." ".$this->B->attach_params['file']."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }
        exit;
        // stop application here
        return TRUE;
    } 
    
    /**
     * default authentication
     *
     */
    function auth()
    {
        // Directed authentication event to the module handler, 
        // which takes the authentication part
        // The variable SF_AUTH_MODULE must be declared in the "common"
        // module event_handler.php file
        M( SF_AUTH_MODULE, 'sys_authenticate' );
    }   
}

?>
