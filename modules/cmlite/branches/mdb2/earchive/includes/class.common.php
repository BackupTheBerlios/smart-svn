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
 * This class provide function which are common to some other classes
 *
 */
 
class earchive_common
{
    /**
     * check list status. If status=3 == registered user required
     * check if the user is registered
     *
     * @param int $lid list id
     */     
    function list_auth( $lid )
    {
        $sql = "
            SELECT
                status
            FROM
                {$this->B->sys['db']['table_prefix']}earchive_lists
            WHERE
                lid={$lid}";

        $_result = $this->B->db->getRow($sql, array(), DB_FETCHMODE_ASSOC);
        
        // check if authentication required
        if($_result['status'] == 3)
        {
            $this->_auth();
        }
    } 

    /**
     * check if user is registered
     *
     * @access privat
     */     
    function _auth()
    {
        if( $this->B->auth->is_user !== FALSE )
        {
            return TRUE;
        }
        else
        {
            $query = base64_encode(commonUtil::getQueryString());
            @header('Location: '.SF_BASE_LOCATION.'/index.php?tpl=login&ret='.$query);
            exit;
        }
    }
}
?>
