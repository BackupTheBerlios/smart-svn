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
 * ActionCommonFilterTrim
 *
 * USAGE:
 * $model->action( 'common', 'upgrade', 
 *                 array('str' => & (string) );
 *
 */

class ActionCommonUpgrade extends SmartAction
{
    /**
     * Do upgrade
     *
     * @param mixed $data
     */
    public function perform( $data = FALSE )
    {
        $this->setNewModuleVersionNumber( $data['new_version'] ); 
    }
    
    /**
     * Validate data passed to this action
     */
    public function validate( $data = FALSE )
    {
        if(!isset($data['new_version']))
        {
            throw new SmartModelException('data var "new_version" is required');        
        }  
        if(!is_string($data['new_version']))
        {
            throw new SmartModelException('data var "new_version" isnt from type string');        
        }        
    }    
    
    /**
     * Validate data passed to this action
     */
    private function setNewModuleVersionNumber( $version )
    {
        $sql = "UPDATE {$this->config['dbTablePrefix']}common_module
                    SET
                        `version`='{$version}'
                    WHERE
                        `id_module`={$this->config['module']['common']['id_module']}";

        $this->model->dba->query($sql);          
    }  
    
    
}

?>