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
 * action_earchive_get_message class 
 *
 */
 
class action_earchive_get_message extends action
{
    /**
     * Assign array with message data
     *
     * @param array $data
     */
    function perform( & $data )
    { 
        if(empty($data['mid']) || empty($data['var']))
        {
            trigger_error("'mid' or 'var' is empty!\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }
        
        // get var name to store the result
        $this->B->$data['var'] = array();
        $result                = & $this->B->$data['var'];              
        
        $_fields = implode(',', $data['fields']);
        
        if(!isset($data['fields']['enc_type']))
        {
            $_fields .= ',enc_type'; 
        }
        
        $sql = "
            SELECT
                {$_fields}
            FROM
                {$this->B->sys['db']['table_prefix']}earchive_messages
            WHERE
                mid={$data['mid']}";
        
        $result = $this->B->db->getRow($sql, array(), DB_FETCHMODE_ASSOC);

        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }

        // transform text to html
        if($result['enc_type'] == 'text/plain')
        {
            $result['body'] = nl2br($this->_text2html( $result['body'] ));
        }

        return TRUE;     
    }    
    
    function _text2html( & $str )
    {
        $str = str_replace("<","&lt;", $str);
        $str = str_replace(">","&gt;", $str);
        
        $str = eregi_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '<a href="\\1" target="_blank">\\1</a>', $str);
        $str = eregi_replace('([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '\\1<a href="http://\\2" target="_blank">\\2</a>', $str);
        $str = eregi_replace('([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})','<a href="mailto:\\1">\\1</a>', $str);
        
        return $str;
    }
}

?>
