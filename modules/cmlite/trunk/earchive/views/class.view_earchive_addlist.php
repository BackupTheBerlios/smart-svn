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
 * view_earchive_addlist class
 *
 */
 
class view_earchive_addlist extends view
{
     /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'earchive_addlist';
    
     /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    var $template_folder = 'modules/earchive/templates/';
    
    /**
     * Execute the view of the template "tpl.earchive_addlist.php"
     *
     * @return bool true on success else false
     */
    function perform()
    {
        if(isset($_POST['addlist']))
        {
            // Init form field values
            $this->B->form_error       = FALSE;
            $this->B->form_name        = '';
            $this->B->form_emailserver = '';
            $this->B->form_email       = '';
            $this->B->form_description = '';
            $this->B->form_status      = '';

            // Check if some form fields are empty
            if(
                empty($_POST['name'])||
                empty($_POST['emailserver'])||
                empty($_POST['email']))
            {
                $this->B->form_error = 'You have fill out the fields: name, emailserver, email !';
                $this->_reset_old_fields_data();
                return TRUE;        
            }
            else
            {
               // add new email list
                $this->B->tmp_data = array('name'  => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['name'])),
                             'emailserver' => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['emailserver'])),
                             'email'       => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['email'])),
                             'description' => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['description'])),
                             'status'      => (int)$_POST['status']);
             
                if(TRUE === M( MOD_EARCHIVE, 'add_list', $this->B->tmp_data))
                {
                    @header('Location: '.SF_BASE_LOCATION.'/'.SF_CONTROLLER.'?admin=1&m=earchive');
                    exit;
                }  
                else
                {
                    $this->B->form_error = 'An unexpected error occured. Please check error_log!';
                    $this->_reset_old_fields_data();
                    return TRUE;                   
                }
            
            }
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
