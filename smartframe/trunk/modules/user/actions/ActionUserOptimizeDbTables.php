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
 * ActionUserOptimizeDbTables class 
 *
 * USAGE:
 * $model->action('user','optimizeDbTables')
 */
 
class ActionUserOptimizeDbTables extends SmartAction
{                                      
    /**
     * optimize user module DB tables
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $sql = "OPTIMIZE NO_WRITE_TO_BINLOG TABLE 
                  {$this->config['dbTablePrefix']}user_user,
                  {$this->config['dbTablePrefix']}user_access,
                  {$this->config['dbTablePrefix']}user_lock,
                  {$this->config['dbTablePrefix']}user_media_pic,
                  {$this->config['dbTablePrefix']}user_media_file";
        
        $this->model->dba->query($sql);
    } 
}

?>
