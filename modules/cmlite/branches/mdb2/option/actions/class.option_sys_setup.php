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
 * option_sys_setup class 
 *
 */
 
class option_sys_setup
{
    /**
     * Global system instance
     * @var object $this->B
     */
    var $B;
    
    /**
     * constructor
     *
     */
    function option_sys_setup()
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
        $success = TRUE;
        // The module name and version
        $this->B->conf_val['module']['option']['name']     = 'option';
        $this->B->conf_val['module']['option']['version']  = MOD_OPTION_VERSION;
        $this->B->conf_val['module']['option']['mod_type'] = 'lite';
        $this->B->conf_val['module']['option']['info']     = '';
        // Set some options
        $this->B->conf_val['option']['tpl'] = SF_DEFAULT_TEMPLATE_GROUP;
        $this->B->conf_val['option']['url'] = SF_BASE_LOCATION;
        $this->B->conf_val['option']['site_title'] = 'Site title';
        $this->B->conf_val['option']['site_desc'] = 'My first site';
        $this->B->conf_val['option']['email'] = 'admin@foo.com';
        $this->B->conf_val['option']['charset'] = $_POST['charset'];
    
        $this->_create_tables();
        
        return $success;    
    } 
    
    function _create_tables()
    {
        $table  = $this->B->conf_val['db']['table_prefix'] . 'bad_words';
        
        $fields = array( 'word'    => array( 'type'     => 'text',
                                             'length'   => 255),
                         'lang'    => array( 'type'     => 'text',
                                             'length'   => 4) 
                       );
                       
        $result = $this->B->dbmanager->createTable( $table, $fields );

        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            $this->B->setup_error[] = $result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
            return FALSE;
        } 
        
        return TRUE;
    }    
}

?>
