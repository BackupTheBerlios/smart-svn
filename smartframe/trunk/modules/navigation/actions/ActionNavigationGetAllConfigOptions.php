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
 * ActionNavigationGetAllConfigOptions  class 
 *
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
        $sql = "
            SELECT
                *
            FROM
                {$this->config['dbTablePrefix']}navigation_config";

        $rs = $this->model->dba->query($sql);
        
        $data['result'] = array();
        
        $data['result'] = $rs->fetchAssoc();
        
        return TRUE;
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
