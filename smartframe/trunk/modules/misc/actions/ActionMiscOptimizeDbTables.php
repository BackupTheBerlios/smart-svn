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
 * ActionMiscOptimizeDbTables class 
 *
 * USAGE:
 * $model->action('misc','optimizeDbTables')
 */
 
class ActionMiscOptimizeDbTables extends SmartAction
{                                      
    /**
     * optimize article module DB tables
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $sql = "OPTIMIZE NO_WRITE_TO_BINLOG TABLE 
                  {$this->config['dbTablePrefix']}misc_text,
                  {$this->config['dbTablePrefix']}misc_text_lock,
                  {$this->config['dbTablePrefix']}misc_text_pic,
                  {$this->config['dbTablePrefix']}misc_text_file,
                  {$this->config['dbTablePrefix']}misc_config,
                  {$this->config['dbTablePrefix']}misc_keyword,
                  {$this->config['dbTablePrefix']}article_keyword";
        
        $this->model->dba->query($sql);
    } 
}

?>
