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
 * earchive_sys_setup class 
 *
 */
 
class earchive_sys_setup
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
    function earchive_sys_setup()
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
        $success = TRUE;
        //create data folder
        if(!is_dir(SF_BASE_DIR . 'data/earchive'))
        {
            if(!mkdir(SF_BASE_DIR . 'data/earchive', SF_DIR_MODE))
            {
                trigger_error("Cant make dir: ".SF_BASE_DIR."data/earchive\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
                $this->B->setup_error[] = 'Cant make dir: ' . SF_BASE_DIR . 'data/earchive';
                $success = FALSE;
            }
            elseif(!is_writeable( SF_BASE_DIR . 'data/earchive' ))
            {
                trigger_error("Cant make dir: ".SF_BASE_DIR."data/earchive\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
                $this->B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . 'data/earchive';
                $success = FALSE;
            }  
        }

        $success = $this->_create_tables();

        // create configs info for this module
        $this->B->conf_val['module']['earchive']['name']     = 'Earchive';
        $this->B->conf_val['module']['earchive']['version']  = MOD_EARCHIVE_VERSION;
        $this->B->conf_val['module']['earchive']['mod_type'] = 'lite';
        $this->B->conf_val['module']['earchive']['info']     = 'Email messages archive. Author: Armand Turpel <smart AT open-publisher.net>';         

        return $success;
    } 
    /**
     * Create earchive tables
     *
     * @access privat
     */     
    function _create_tables()
    {
        /* #################################### */
        /* ####   TABLE  "earchive_lists"  #### */
        /* #################################### */
        $table  = $this->B->conf_val['db']['table_prefix'] . 'earchive_lists';
        
        $fields = array( 'lid'           => array( 'type'     => 'integer', 
                                                   'notnull'  => 1,
                                                   'default'  => 0 ), 
                         'status'        => array( 'type'     => 'integer',
                                                   'notnull'  => 1,
                                                   'default'  => 1 ), 
                         'name'          => array( 'type'     => 'text',
                                                   'length'   => 255),
                         'description'   => array( 'type'     => 'text'),
                         'email'         => array( 'type'     => 'text',
                                                   'length'   => 1024), 
                         'emailserver'   => array( 'type'     => 'text',
                                                   'length'   => 1024),
                         'folder'        => array( 'type'     => 'text',
                                                   'length'   => 32) 
                       );
                       
        $result = $this->B->dbmanager->createTable( $table, $fields );

        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            $this->B->setup_error[] = $result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
            return FALSE;
        } 
        
        // set index on lid
        $index = array( 'fields' => array( 'lid' => array() ) ); 
        $result = $this->B->dbmanager->createIndex( $table, 'lid', $index);    

        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }  
        
        // set index on status
        $index = array( 'fields' => array( 'status' => array() ) ); 
        $result = $this->B->dbmanager->createIndex( $table, 'status', $index);    

        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }        

        // create sequence table earchive_seq_list
        $result = $this->B->dbmanager->createSequence( $this->B->conf_val['db']['table_prefix'] . 'earchive_lists' );

        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            $this->B->setup_error[] = $result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
        } 

        /* ####################################### */
        /* ####   TABLE  "earchive_messages"  #### */
        /* ####################################### */
        $table  = $this->B->conf_val['db']['table_prefix'] . 'earchive_messages';
        
        $fields = array( 'mid'           => array( 'type'     => 'integer', 
                                                   'notnull'  => 1,
                                                   'default'  => 0 ), 
                         'lid'           => array( 'type'     => 'integer', 
                                                   'notnull'  => 1,
                                                   'default'  => 0 ),                           
                         'subject'       => array( 'type'     => 'text',
                                                   'length'   => 1024),
                         'sender'        => array( 'type'     => 'text',
                                                   'length'   => 1024),
                         'body'          => array( 'type'     => 'text'), 
                         'mdate'         => array( 'type'     => 'time'),
                         'folder'        => array( 'type'     => 'text',
                                                   'length'   => 32) 
                       );
                       
        $result = $this->B->dbmanager->createTable( $table, $fields );

        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            $this->B->setup_error[] = $result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
            return FALSE;
        } 

        // set index on lid
        $index = array( 'fields' => array( 'lid' => array() ) ); 
        $result = $this->B->dbmanager->createIndex( $table, 'lid', $index);    

        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }  
        
        // set index on mid
        $index = array( 'fields' => array( 'mid' => array() ) ); 
        $result = $this->B->dbmanager->createIndex( $table, 'mid', $index);    

        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }
        
        // create sequence table earchive_seq_message
        $result = $this->B->dbmanager->createSequence( $this->B->conf_val['db']['table_prefix'] . 'earchive_messages' );

        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            $this->B->setup_error[] = $result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
        } 

        /* ####################################### */
        /* ####   TABLE  "earchive_attach"  ###### */
        /* ####################################### */
        $table  = $this->B->conf_val['db']['table_prefix'] . 'earchive_attach';
        
        $fields = array( 'aid'           => array( 'type'     => 'integer', 
                                                   'notnull'  => 1,
                                                   'default'  => 0 ), 
                         'mid'           => array( 'type'     => 'integer', 
                                                   'notnull'  => 1,
                                                   'default'  => 0 ), 
                         'lid'           => array( 'type'     => 'integer', 
                                                   'notnull'  => 1,
                                                   'default'  => 0 ),                          
                         'file'          => array( 'type'     => 'text',
                                                   'length'   => 1024),
                         'size'          => array( 'type'     => 'integer', 
                                                   'notnull'  => 1,
                                                   'default'  => 0 ),                          
                         'type'          => array( 'type'     => 'text',
                                                   'length'   => 255) 
                       );
                       
        $result = $this->B->dbmanager->createTable( $table, $fields );

        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            $this->B->setup_error[] = $result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
            return FALSE;
        } 

        // set index on lid
        $index = array( 'fields' => array( 'lid' => array() ) ); 
        $result = $this->B->dbmanager->createIndex( $table, 'lid', $index);    

        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }  
        
        // set index on mid
        $index = array( 'fields' => array( 'mid' => array() ) ); 
        $result = $this->B->dbmanager->createIndex( $table, 'mid', $index);    

        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }
        
        // set index on aid
        $index = array( 'fields' => array( 'aid' => array() ) ); 
        $result = $this->B->dbmanager->createIndex( $table, 'aid', $index);    

        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }   
        
        // create sequence table earchive_seq_attach
        $result = $this->B->dbmanager->createSequence( $this->B->conf_val['db']['table_prefix'] . 'earchive_attach' );

        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            $this->B->setup_error[] = $result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
        }        

        /* ############################################ */
        /* ####   TABLE  "earchive_words_crc32"  ###### */
        /* ############################################ */        
        $table  = $this->B->conf_val['db']['table_prefix'] . 'earchive_words_crc32';
        
        $fields = array( 'word'           => array( 'type'     => 'integer', 
                                                   'notnull'  => 1,
                                                   'default'  => 0 ), 
                         'mid'           => array( 'type'     => 'integer', 
                                                   'notnull'  => 1,
                                                   'default'  => 0 ), 
                         'lid'           => array( 'type'     => 'integer', 
                                                   'notnull'  => 1,
                                                   'default'  => 0 ) 
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
