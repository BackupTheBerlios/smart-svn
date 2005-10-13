<?php
// ----------------------------------------------------------------------
// Smart3 PHP Framework
// Copyright (c) 2004, 2005
// by Armand Turpel < framework@smart3.org >
// http://www.smart3.org/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * ActionCommonMysqlInfo class 
 *
 *
 * $model->action('common','mysqlInfo',
 *                array('result'    => & array() ))
 *
 */

class ActionCommonMysqlInfo extends SmartAction
{
    /**
     * add node picture or file
     *
     * @param array $data
     * @return int node id or false on error
     */
    function perform( $data = FALSE )
    { 
        // get mysql server version
        $sql = "SELECT VERSION() AS `version`";
        $rs = $this->model->dba->query($sql);
        $row = $rs->fetchAssoc();
        $data['result']['version'] = $row['version'];
        
        // get mysql cache status 
        $sql = "SHOW VARIABLES LIKE 'have_query_cache'";
        $rs = $this->model->dba->query($sql);
        while($row = $rs->fetchAssoc())
        {
            $data['result']['status'][$row['Variable_name']] = $row['Value'];
        }        
        
        // get mysql cache status 
        $sql = "SHOW STATUS LIKE 'Qcache%'";
        $rs = $this->model->dba->query($sql);
        while($row = $rs->fetchAssoc())
        {
            $data['result']['status'][$row['Variable_name']] = $row['Value'];
        }
      
    }
    
    /**
     * validate  data
     *
     * @param array $data
     * @return bool 
     */    
    function validate( $data = FALSE )
    {
        if(!isset($data['result']))
        {
            throw new SmartModelException("'result' var isnt set!");
        }
        elseif(!is_array($data['result']))
        {
            throw new SmartModelException("'result' var isnt from type array!");
        }      
        
        return TRUE;
    }
}

?>
