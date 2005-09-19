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
 * ActionMiscDeleteText class 
 *
 * USAGE:
 *
 * $model->action('misc','delete',
 *                array('id_text'  => int))
 *
 */
 
class ActionMiscDeleteText extends SmartAction
{
    /**
     * delete misc text, referenced table entries and pictures, files
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {        
        $this->deleteText( $data['id_text'] );
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool
     */    
    public function validate( $data = FALSE )
    { 
        if(!isset($data['id_text']))
        {
            throw new SmartModelException('"id_text" isnt defined and is required!');        
        }    
        if(!is_int($data['id_text']))
        {
            throw new SmartModelException('"id_text" isnt from type int!');        
        }

        return TRUE;
    }

    private function deleteText( $id_text )
    {
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}misc_text_lock
                  WHERE
                   `id_text`={$id_text}";

        $this->model->dba->query($sql);

        $sql = "DELETE FROM {$this->config['dbTablePrefix']}misc_text_pic
                  WHERE
                   `id_text`={$id_text}";

        $this->model->dba->query($sql);

        $sql = "DELETE FROM {$this->config['dbTablePrefix']}misc_text_file
                  WHERE
                   `id_text`={$id_text}";

        $this->model->dba->query($sql);

        $sql = "SELECT `media_folder` FROM {$this->config['dbTablePrefix']}misc_text
                  WHERE
                   `id_text`={$id_text}";
                   
        $rs = $this->model->dba->query($sql);

        $row = $rs->fetchAssoc();

        if(isset($row['media_folder']) && !empty($row['media_folder']))
        {
            // delete user data media folder
            SmartCommonUtil::deleteDirTree( SMART_BASE_DIR.'data/misc/'.$row['media_folder'] );
        }
        
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}misc_text
                  WHERE
                   `id_text`={$id_text}";

        $this->model->dba->query($sql);
           
    }
}

?>
