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
 * view_setup_index class 
 *
 */
 
class view_setup_index extends view
{
     /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'setup_index';
    
     /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    var $template_folder = 'modules/setup/templates/';
    

    /**
     * Do setup for this module
     *
     * @param array $data
     */
    function perform( $data )
    {    
        // launch setup
        if( $_POST['do_setup'] )
        {
            if( FALSE == M(MOD_SETUP, 'sys_setup') )
            {
                $this->B->form_host        = htmlspecialchars(commonUtil::stripSlashes($_POST['dbhost']));
                $this->B->form_user        = htmlspecialchars(commonUtil::stripSlashes($_POST['dbuser']));
                $this->B->form_dbname      = htmlspecialchars(commonUtil::stripSlashes($_POST['dbname']));
                $this->B->form_tableprefix = htmlspecialchars(commonUtil::stripSlashes($_POST['dbtablesprefix']));
                $this->B->form_sysname     = htmlspecialchars(commonUtil::stripSlashes($_POST['sysname']));
                $this->B->form_syslastname = htmlspecialchars(commonUtil::stripSlashes($_POST['syslastname']));
                $this->B->form_syslogin    = htmlspecialchars(commonUtil::stripSlashes($_POST['syslogin']));            
            }
        }   
        return TRUE;
    } 
}

?>
