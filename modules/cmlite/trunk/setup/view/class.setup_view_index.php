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
 * setup_view_index class 
 *
 */
 
class setup_view_index
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
    function setup_view_index()
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
     * Do setup for this module
     *
     * @param array $data
     */
    function perform( $data )
    {    
        // Init error array
        $this->B->setup_error = array();

        // launch setup
        if( $_POST['do_setup'] )
        {
            $success = TRUE;

            if($success == TRUE)
                $success = $this->B->M( MOD_SYSTEM,       'sys_setup' );

            if($success == TRUE)    
                $success = $this->B->M( MOD_COMMON,       'sys_setup' );
    
            if($success == TRUE)    
                $success = $this->B->M( MOD_USER,         'sys_setup' );
          
            if($success == TRUE)
                $success = $this->B->M( MOD_EARCHIVE,     'sys_setup' );

            if($success == TRUE)
                $success = $this->B->M( MOD_NAVIGATION,   'sys_setup' );
        
            if($success == TRUE)
                $success = $this->B->M( MOD_OPTION,       'sys_setup' );

            // close db connection if present
            if(is_object($this->B->db))
                $this->B->db->disconnect();
        
            // check on errors before proceed
            if( $success == TRUE )
            {
                // set default template group that com with this package
                $this->B->conf_val['option']['tpl'] = 'earchive';    
                $this->B->conf_val['info']['status'] = TRUE;
  
                // include PEAR Config class
                include_once( SF_BASE_DIR . 'modules/common/PEAR/Config.php');

                $c = new Config();
                $root =& $c->parseConfig($this->B->conf_val, 'PHPArray');
                $c->writeConfig(SF_BASE_DIR . 'modules/common/config/config.php', 'PHPArray', array('name' => 'this->B->sys'));
        
                @header('Location: '.SF_BASE_LOCATION.'/index.php?admin=1');
                exit;  
            }
            else
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
