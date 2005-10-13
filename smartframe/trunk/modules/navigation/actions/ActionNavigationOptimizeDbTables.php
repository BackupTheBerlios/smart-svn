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
 * ActionNavigationOptimizeDbTables class 
 *
 * USAGE:
 * $model->action('navigation','optimizeDbTables')
 */
 
class ActionNavigationOptimizeDbTables extends SmartAction
{                                      
    /**
     * optimize navigation module DB tables
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $sql = "OPTIMIZE NO_WRITE_TO_BINLOG TABLE 
                  {$this->config['dbTablePrefix']}navigation_node,
                  {$this->config['dbTablePrefix']}navigation_node_lock,
                  {$this->config['dbTablePrefix']}navigation_media_pic,
                  {$this->config['dbTablePrefix']}navigation_media_file,
                  {$this->config['dbTablePrefix']}navigation_view,
                  {$this->config['dbTablePrefix']}navigation_index";
        
        $this->model->dba->query($sql);
    } 
}

?>
