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
 * earchive_view_editlist class of the template "editlist.tpl.php"
 *
 */
 
class earchive_view_editlist
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
    function earchive_view_editlist()
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
     * Execute the view of the template "editlist.tpl.php"
     *
     * @return bool true on success else false
     */
    function perform()
    {
        // init 
        $this->B->form_error = FALSE;

        // delete list
        if($_POST['dellist'] == 1)
        {
            // check if the user of this request try to delete a list
            // with rights other than administrator 5.
            //
            if( TRUE == $this->B->F( MOD_EARCHIVE, 'permission', array('action' => 'delete')))
            {
                $this->B->M( MOD_EARCHIVE, 
                             'delete_list', 
                             array('lid' => (int)$_REQUEST['lid']));
        
                @header('Location: '.SF_BASE_LOCATION.'/index.php?admin=1&m=earchive');
                exit;
            }    
            else
            {        
                $this->B->form_error = 'You can\'t remove this list'; 
                $this->_reset_old_fields_data();
                return TRUE;
            }
        }

        // Modify list data
        if(isset($_POST['editlist']))
        {  
            // check if some fields are empty
            if(
                empty($_POST['name'])||
                empty($_POST['emailserver'])||
                empty($_POST['email']))
            {        
                $this->B->form_error = 'You have fill out all fields!';
                $this->_reset_old_fields_data();
                return TRUE;
            }
            else
            {
                // list form data
                $this->B->tmp_data = array(
                              'name'        => $this->B->db->quote(commonUtil::stripSlashes($_POST['name'])),
                              'emailserver' => $this->B->db->quote(commonUtil::stripSlashes($_POST['emailserver'])),
                              'email'       => $this->B->db->quote(commonUtil::stripSlashes($_POST['email'])),
                              'description' => $this->B->db->quote(commonUtil::stripSlashes($_POST['description'])),
                              'status'      => (int)$_POST['status']);

                // update list data
                if(TRUE === $this->B->M( MOD_EARCHIVE, 
                                         'update_list', 
                                         array( 'lid'  => (int)$_REQUEST['lid'], 
                                                'data' => $this->B->tmp_data)))
                {
                    @header('Location: index.php?admin=1&m=earchive');
                    exit;
                }
                else
                {
                    $this->B->form_error = $_error;
                    $this->_reset_old_fields_data();
                    return TRUE;                   
                }
            }
        }
        else
        {
            // assign template vars with list data
            $this->B->M( MOD_EARCHIVE, 
                         'get_list', 
                         array( 'lid'    => (int)$_REQUEST['lid'], 
                                'var'    => 'tpl_data',
                                'fields' => array('lid','name','status','email','emailserver','description')));
        }

        return TRUE;
    } 
    
    
    /**
     * reset the form fields with old user data
     *
     * @access privat
     */       
    function _reset_old_fields_data()
    {
        // if empty assign form field with old values
        $this->B->tpl_data['name']        = commonUtil::stripSlashes($_POST['name']);
        $this->B->tpl_data['emailserver'] = commonUtil::stripSlashes($_POST['emailserver']);
        $this->B->tpl_data['email']       = commonUtil::stripSlashes($_POST['email']);
        $this->B->tpl_data['description'] = commonUtil::stripSlashes($_POST['description']);
        $this->B->tpl_data['status']      = $_POST['status'];          
    }    
}

?>
