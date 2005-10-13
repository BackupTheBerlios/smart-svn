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
 * ActionNavigationGetAllConfigOptions  class 
 *
 * USAGE:
 *
 * $model->action('navigation','getAllConfigOptions',
 *                array('result' => & array));
 */
 
class ActionNavigationGetAllConfigOptions extends SmartAction
{
    /**
     * get all navigation module config options
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {
        $sql = "SELECT * FROM {$this->config['dbTablePrefix']}navigation_config";

        $rs = $this->model->dba->query($sql);
        
        $data['result'] = $rs->fetchAssoc();
    } 
    
    public function validate( $data = FALSE )
    {
        if(!isset($data['result']))
        {
            throw new SmartModelException("No 'result' defined");
        }

        return TRUE;
    }
}

?>
