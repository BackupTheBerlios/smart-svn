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
 * view_earchive_editlist class
 *
 */
 
class view_earchive_editlist extends view
{
     /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'earchive_editlist';
    
     /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    var $template_folder = 'modules/earchive/templates/';
 
    /**
     * Execute the view of the template "tpl.earchive_editlist.php"
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
            if( TRUE == F( MOD_EARCHIVE, 'permission', array('action' => 'delete')))
            {
                M( MOD_EARCHIVE, 
                   'delete_list', 
                   array('lid' => (int)$_REQUEST['lid']));
        
                @header('Location: '.SF_BASE_LOCATION.'/'.SF_CONTROLLER.'?admin=1&m=earchive');
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
                              'name'        => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['name'])),
                              'emailserver' => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['emailserver'])),
                              'email'       => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['email'])),
                              'description' => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['description'])),
                              'status'      => (int)$_POST['status']);

                // update list data
                if(TRUE === M( MOD_EARCHIVE, 
                               'update_list', 
                               array( 'lid'  => (int)$_REQUEST['lid'], 
                                      'data' => $this->B->tmp_data)))
                {
                    @header('Location: '.SF_BASE_LOCATION.'/'.SF_CONTROLLER.'?admin=1&m=earchive');
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
            M( MOD_EARCHIVE, 
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
