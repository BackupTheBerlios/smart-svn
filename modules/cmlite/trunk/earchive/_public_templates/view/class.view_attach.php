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
 * view_attach class of the template "group_attach.tpl.php"
 *
 */

include_once ('HTTP/Download.php');
 
class view_attach
{
    /**
     * Global system instance
     * @var object $B
     */
    var $B;
    
    /**
     * constructor
     *
     */
    function view_attach()
    {
        $this->__construct();
    }

    /**
     * constructor php5
     *
     */
    function __construct()
    {
        $this->B = & $GLOBALS['B'];
    }
    
    /**
     * Execute the view of the template "group_attach.tpl.php"
     *
     * @return bool true on success else false
     * @todo validate $_GET['mid']
     */
    function perform()
    {
        //Get the demanded attachment and send it to the client

        //get the top requested email list attachment folder 
        $this->B->M( MOD_EARCHIVE, 
                     'get_list', 
                     array( 'var'    => 'tpl_list', 
                            'lid'    => (int)$_GET['lid'], 
                            'fields' => array('folder'))); 

        //get the requested message attachment folder
        $this->B->M( MOD_EARCHIVE, 
                     'get_message', 
                     array( 'var'    => 'tpl_msg', 
                            'mid'    => (int)$_GET['mid'], 
                            'lid'    => (int)$_GET['lid'], 
                            'fields' => array('folder')));                        

        //get the attachment file name, type
        $this->B->M( MOD_EARCHIVE, 
                     'get_attach', 
                     array( 'var'    => 'tpl_attach', 
                            'aid'    => (int)$_GET['aid'], 
                            'mid'    => (int)$_GET['mid'], 
                            'lid'    => (int)$_GET['lid'], 
                            'fields' => array('file','type')));

        // set params to send http header and content
        $this->B->attach_params = array(
                     'file'                  => './data/earchive/'.$this->B->tpl_list['folder'].'/'.$this->B->tpl_msg['folder'].'/'.stripslashes($this->B->tpl_attach['file']),
                     'contenttype'           => $this->B->tpl_attach['type'],
                     'contentdisposition'    => array(HTTP_DOWNLOAD_ATTACHMENT, stripslashes($this->B->tpl_attach['file'])),
                     );

        return TRUE;
    }    
}

?>
