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
 * user_get_users class 
 *
 */
 
class user_get_users
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
    function user_get_users()
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
     * update user data
     *
     * @param array $data
     * @return bool true or false on error
     */
    function perform( $data )
    {
        $this->B->$data['error'] = FALSE;
        $error                   = & $this->B->$data['error'];

        $this->B->$data['result'] = array();
        $_result                  = & $this->B->$data['result'];
        
        $comma = '';
        foreach ($data['fields'] as $f)
        {
            $_fields .= $comma.$f;
            $comma = ',';
        }
        
        if(isset($data['order']))
        {
            $_order = $data['order'];
        }
        else
        {
            $_order = 'rights DESC, lastname ASC';
        }
        
        $sql = "
            SELECT
                {$_fields}
            FROM
                {$this->B->sys['db']['table_prefix']}user_users
            ORDER BY
                {$_order}";
        
        $result = $this->B->db->query($sql);
        
        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            $error = 'Unexpected error';
            return FALSE;
        }        
        
        if(is_object($result))
        {        
            while($row = &$result->fetchRow( MDB2_FETCHMODE_ASSOC ))
            {
                $tmp = array();
                foreach($data['fields'] as $f)
                {
                    $tmp[$f] = stripslashes($row[$f]);
                }
                $_result[] = $tmp;
            }
        }
        
        return TRUE;
    } 
}

?>
