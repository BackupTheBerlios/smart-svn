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
 * SETUP_SYS_SETUP class 
 *
 */
 
class setup_sys_setup
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
    function setup_sys_setup()
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
        
        $success = TRUE;
                
        if($success == TRUE)
            $success = $this->B->M( MOD_SYSTEM,       'sys_setup' );

        if($success == TRUE)    
            $success = $this->B->M( MOD_COMMON,       
                                    'sys_setup_validate', 
                                    array( 'dbcreate' => $data['dbcreate'],
                                    'dbtype'   => $data['dbtype'],
                                    'dbuser'   => $data['dbuser'],
                                    'dbpasswd' => $data['dbpasswd'],
                                    'dbname'   => $data['dbname'],
                                    'dbhost'   => $data['dbhost'],
                                    'dbtablesprefix' => $data['dbtablesprefix']) );

        if($success == TRUE)    
            $success = $this->B->M( MOD_USER,         
                                    'sys_setup_validate', 
                                    array( 'username'     => $data['username'],
                                           'userlastname' => $data['userlastname'],
                                           'userlogin'    => $data['userlogin'],
                                           'userpasswd1'  => $data['userpasswd1'],
                                           'userpasswd2'  => $data['userpasswd2']) );


        if($success == TRUE)    
            $success = $this->B->M( MOD_COMMON,       
                                    'sys_setup', 
                                    array( 'dbcreate' => $data['dbcreate'],
                                           'dbtype'   => $data['dbtype'],
                                           'dbuser'   => $data['dbuser'],
                                           'dbpasswd' => $data['dbpasswd'],
                                           'dbname'   => $data['dbname'],
                                           'dbhost'   => $data['dbhost'],
                                           'dbtablesprefix' => $data['dbtablesprefix']) );
    
        if($success == TRUE)    
            $success = $this->B->M( MOD_USER,         
                                    'sys_setup', 
                                    array( 'username'     => $data['username'],
                                           'userlastname' => $data['userlastname'],
                                           'userlogin'    => $data['userlogin'],
                                           'userpasswd1'  => $data['userpasswd1'],
                                           'userpasswd2'  => $data['userpasswd2']) );

/*        
        if($success == TRUE)
            $success = $this->B->M( MOD_EARCHIVE,     'sys_setup' );
*/
        if($success == TRUE)
            $success = $this->B->M( MOD_OPTION,       'sys_setup' );
    
        // close db connection if present
        //if(is_object($this->B->db))
            //$this->B->db->disconnect();
        
        // check on errors before proceed
        if( $success == TRUE )
        {
            // set default template group that com with this package
            $this->B->conf_val['option']['tpl'] = 'earchive';    
            $this->B->conf_val['info']['status'] = TRUE;
                
            $this->B->M( MOD_COMMON, 'sys_update_config', $this->B->conf_val ); 
                
            @header('Location: '.SF_BASE_LOCATION.'/index.php?admin=1');
            exit;  
        }  
        return $success;
    } 
    

}

?>
