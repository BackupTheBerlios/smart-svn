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
 * ActionCommonSmartCoreNewVersion
 *
 * Delete Tinymce cache
 *
 * USAGE:
 * $model->action( 'common', 'smartCoreNewVersion', 
 *                 array('new_version' => (string) );
 *
 */

class ActionCommonSmartCoreNewVersion extends SmartAction
{
    /**
     * Add http headers to disable browser caching
     *
     * @param mixed $data
     */
    public function perform( $data = FALSE )
    {
        $this->model->action('common', 'deleteTinymceCache');
        
        // set new smart core version number in db
        $this->setNewSmartCoreVersionNumber( $data['new_version'] );
    }
    
    /**
     * Validate data passed to this action
     */
    public function validate( $data = FALSE )
    {
        if(!is_string($data['new_version']))
        {
            throw new SmartModelException("'new_version' isnt from type string");
        }    
        return TRUE;
    }   
    
    /**
     * update to new smart core version number
     *
     * @param string $version  version number
     */
    private function setNewSmartCoreVersionNumber( $version )
    {
        $sql = "UPDATE {$this->config['dbTablePrefix']}common_config
                    SET
                        `smart_version_num`='{$version}'";

        $this->model->dba->query($sql);          
    }       
}

?>