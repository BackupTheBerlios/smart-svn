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
 * common_sys_init class 
 *
 */
 
class common_sys_init
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
    function common_sys_init()
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
    
     * Check if version number has changed and perfom additional upgarde code
     * Furthermore assign array with module menu names for the top right
     * module html seletor
     *
     * @param array $data
     */
    function perform( $data )
    {
        // get os related separator to set include path
        if(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
            $tmp_separator = ';';
        else
            $tmp_separator = ':';

        // set include path to the PEAR packages
        ini_set( 'include_path', SF_BASE_DIR . 'modules/common/PEAR' . $tmp_separator . ini_get('include_path') );
        unset($tmp_separator); 

        // init system config array
        $this->B->sys = array();

        // include system config array $this->B->sys
        if(file_exists(SF_BASE_DIR . 'modules/common/config/config.php'))
            include_once( SF_BASE_DIR . 'modules/common/config/config.php' );  

        // if setup was done
        if($this->B->sys['info']['status'] == TRUE)
        { 
            // include PEAR DB class
            include_once( SF_BASE_DIR . 'modules/common/PEAR/DB.php');        
        
            $this->B->dsn = array('phptype'  => $this->B->sys['db']['dbtype'],
                                  'username' => $this->B->sys['db']['user'],
                                  'password' => $this->B->sys['db']['passwd'],
                                  'hostspec' => $this->B->sys['db']['host'],
                                  'database' => $this->B->sys['db']['name']);

            $this->B->dboptions = array('debug'       => 0,
                                        'portability' => DB_PORTABILITY_NONE);
    
            $this->B->db =& DB::connect($this->B->dsn, $this->B->dboptions, TRUE);
            if (DB::isError($this->B->db)) 
            {
                trigger_error( 'Cannot connect to the database: '.__FILE__.' '.__LINE__, E_USER_ERROR  );
            }
    
            // include session class
            include_once( SF_BASE_DIR . 'modules/common/includes/class.sfSession.php' );  
    
            /* Create new object of session class */
            $this->B->session = & new session();    
        }
        // else launch setup
        else
        {
            // switch to the admin section if we comes from the public section
            if(SF_SECTION == 'public')
            {
                @header('Location: '.SF_BASE_LOCATION.'/index.php?admin=1');
                exit;  
            }
           
            // launch setup screen
            include( $this->B->M( MOD_COMMON, 'get_module_view', array('m' => 'setup', 'tpl' => 'index')) ); 
            
            // Send the output buffer to the client
            ob_end_flush();
            exit;
        }    
    
        // Check for upgrade  
        if(MOD_COMMON_VERSION != (string)$this->B->sys['module']['common']['version'])
        {
            // set the new version num of this module
            $this->B->sys['module']['common']['version']  = MOD_COMMON_VERSION;
            $this->B->system_update_flag = TRUE;  
                
            // include here additional upgrade code
        }
     
        if( SF_SECTION == 'admin')
        {
            // sort handler array by name
            ksort($this->B->handler_list);
        
            // assign template handler names array
            // this array is used to build the modul select form of the admin menu
            $this->B->tpl_mod_list = array();    
            
            foreach ($this->B->handler_list as $key => $value)
            {
                if( $value['menu_visibility'] == TRUE )
                {
                    $this->B->tpl_mod_list[$key] =  $value;
                }
            }
        }
    }    
}

?>
