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
 * ActionLinkOptimizeDbTables class 
 *
 * USAGE:
 * $model->action('link','optimizeDbTables')
 */
 
class ActionLinkOptimizeDbTables extends SmartAction
{                                      
    /**
     * optimize link module DB tables
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $sql = "OPTIMIZE NO_WRITE_TO_BINLOG TABLE 
                  {$this->config['dbTablePrefix']}link_links,
                  {$this->config['dbTablePrefix']}link_lock";
        
        $this->model->dba->query($sql);
    } 
}

?>
