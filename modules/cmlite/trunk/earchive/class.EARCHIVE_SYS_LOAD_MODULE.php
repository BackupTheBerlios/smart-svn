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
 * EARCHIVE_SYS_LOAD_MODULE class 
 *
 */
 
class EARCHIVE_SYS_LOAD_MODULE
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
    function EARCHIVE_SYS_LOAD_MODULE()
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
     * Perform on admin requests for this module
     *
     * @param array $data
     */
    function perform( $data )
    {    
        // earchive rights class
        include(SF_BASE_DIR.'/admin/modules/earchive/class.rights.php');   

        // check if the login user have rights to access this module
        // 4 or 5 required (editor or administrator)
        if(FALSE == earchive_rights::ask_access_to_list())
        {
            @header('Location: '.SF_BASE_LOCATION.'/admin/index.php');
            exit;
        }
        
        // the user class
        include_once SF_BASE_DIR . '/admin/modules/earchive/class.earchive.php';

        //User Class instance
        $this->B->earchive = & new earchive;

        // set the base template for this module
        $this->B->module = SF_BASE_DIR . '/admin/modules/earchive/templates/index.tpl.php';  

        // Switch to module features
        switch($_REQUEST['mf'])
        {
            case 'edit_list':
                include( SF_BASE_DIR."/admin/modules/earchive/editlist.php" ); 
                // set the base template for this module feature
                $this->B->section = SF_BASE_DIR . '/admin/modules/earchive/templates/editlist.tpl.php';    
                break;
            case 'add_list':
                if(isset($_POST['addlist']))
                {
                    include( SF_BASE_DIR."/admin/modules/earchive/addlist.php" ); 
                }
                // set the base template for this module feature
                $this->B->section = SF_BASE_DIR . '/admin/modules/earchive/templates/addlist.tpl.php';
                break;         
            case 'show_mess':
                // Delete messages on demande
                if(isset($_POST['deletemess']))
                {
                    if(count($_POST['mid']) > 0)
                    {
                        foreach($_POST['mid'] as $mid)
                        {
                            $this->B->earchive->delete_message( $mid );
                        }
                    }
                }
        
                // get list name and id
                $fields = array('lid','name');
                $this->B->tpl_list = $this->B->earchive->get_list( (int)$_GET['lid'], $fields );

                // get list messages
                $fields = array('mid', 'lid', 'subject', 'sender', 'mdate');
                $this->B->earchive->get_messages( 'tpl_messages', (int)$_GET['lid'], $fields, 'tpl_messages_pager');
        
                // set the base template for this module feature
                $this->B->section = SF_BASE_DIR . '/admin/modules/earchive/templates/showmessages.tpl.php';
                break; 
            case 'edit_mess':
                include( SF_BASE_DIR."/admin/modules/earchive/editmessage.php" ); 
                // set the base template for this module feature
                $this->B->section = SF_BASE_DIR . '/admin/modules/earchive/templates/editmessage.tpl.php';    
                break;        
           default:
                // set the base template for this module
                $this->B->section = SF_BASE_DIR . '/admin/modules/earchive/templates/default.tpl.php';    

                $this->B->tmp_fields = array('lid','status','email','name','description');
                $this->B->all_lists = $this->B->earchive->get_lists( $this->B->tmp_fields );  
        }        
    } 
}

?>
