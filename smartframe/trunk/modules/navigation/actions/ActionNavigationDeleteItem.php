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
 * ActionNavigationDeleteItem class 
 *
 */

class ActionNavigationDeleteItem extends SmartAction
{
    /**
     * Delete node picture or file
     *
     * @param array $data
     * @return bool
     */
    public function perform( $data = FALSE )
    {
        // set table name and item reference
        if(isset($data['id_file']))
        {
            $this->deleteFile($data);
        }
        else
        {
            $this->deletePicture($data);
        }
        
        $this->removeEmptyDirectory();
  
        return TRUE;
    }
    
    /**
     * validate the parameters passed in the data array
     *
     * @param array $data
     * @return bool
     */    
    public function validate(  $data = FALSE  )
    {
        if(!isset($data['id_pic']) && !isset($data['id_file']))
        {
            throw new SmartModelException("No 'id_pic' or 'id_file' defined");
        }

        if(isset($data['id_pic']) && preg_match("/[^0-9]/",$data['id_pic']))
        {
            throw new SmartModelException("'id_pic' isnt numeric");
        }
        
        if(isset($data['id_file']) && preg_match("/[^0-9]/",$data['id_file']))
        {
            throw new SmartModelException("'id_file' isnt numeric");
        }        
        return TRUE;
    } 
    /**
     * delete a picture
     *
     * @param array $data
     * @return bool
     */     
    private function deletePicture( &$data )
    {
        $pic = array();

        $this->model->action('navigation',
                             'getPicture',
                             array('result' => & $pic,
                                   'id_pic' => $data['id_pic'],
                                   'fields' => array('file','id_node')));   

        $node = array();

        $this->model->action('navigation',
                             'getNode',
                             array('result'  => & $node,
                                   'id_node' => $pic['id_node'],
                                   'fields'  => array('media_folder')));   

        $this->idNode = $pic['id_node'];
        $this->mediaFolder = &$node['media_folder'];

        if(!@unlink(SMART_BASE_DIR . 'data/navigation/'.$node['media_folder'].'/'.$pic['file']))
        {
           trigger_error('Cant delete user logo: data/navigation/'.$user['media_folder'].'/'.$pic['file'], E_USER_WARNING);
        }
        if(!@unlink(SMART_BASE_DIR . 'data/navigation/'.$node['media_folder'].'/thumb/'.$pic['file']))
        {
           trigger_error('Cant delete user logo: data/navigation/'.$node['media_folder'].'/thumb/'.$pic['file'], E_USER_WARNING);
        }    
        // remove picture reference from database
        $this->model->action('navigation',
                             'updatePicture',
                             array('action'  => 'delete',
                                   'id_pic'  => $data['id_pic'],
                                   'id_node' => $pic['id_node']));    
    }  
    /**
     * delete a file
     *
     * @param array $data
     * @return bool
     */      
    private function deleteFile( &$data )
    {
        $file = array();

        $this->model->action('navigation',
                             'getFile',
                             array('result' => & $file,
                                   'id_file' => $data['id_file'],
                                   'fields'  => array('file','id_node')));   

        $node = array();

        $this->model->action('navigation',
                             'getNode',
                             array('result'  => & $node,
                                   'id_node' => $file['id_node'],
                                   'fields'  => array('media_folder')));   

        $this->idNode = $file['id_node'];
        $this->mediaFolder = &$node['media_folder'];

        if(!@unlink(SMART_BASE_DIR . 'data/navigation/'.$node['media_folder'].'/'.$file['file']))
        {
           trigger_error('Cant delete user logo: data/navigation/'.$node['media_folder'].'/'.$file['file'], E_USER_WARNING);
        }   
        // remove file reference from database
        $this->model->action('navigation',
                             'updateFile',
                             array('action'  => 'delete',
                                   'id_file' => $data['id_file'],
                                   'id_node' => $file['id_node']));    
    }
    /**
     * remove empty user directory
     *
     * @return bool
     */  
    private function removeEmptyDirectory()
    {
        $dir = SMART_BASE_DIR . 'data/navigation/' . $this->mediaFolder;
        
        if(TRUE == $this->isDirEmpty( $dir ))
        {
            // delete whole tree
            SmartCommonUtil::deleteDirTree( $dir );
            // remove media_folder reference
            $this->model->action( 'navigation','update',
                                  array('id_node' => (int)$this->idNode,
                                        'fields'  => array('media_folder' => '')) );
        }
    }
    /**
     * check if user directory is empty
     *
     * @param string $dir whole dir path
     * @return bool
     */     
    private function isDirEmpty( &$dir )
    {
        if ( (($handle = @opendir( $dir ))) != FALSE )
        {
            while ( (( $file = readdir( $handle ) )) != false )
            {
                if ( ( $file == "." ) || ( $file == ".." ) )
                {
                    continue;
                }
                if ( @is_file( $dir . '/' . $file ) )
                {
                    return FALSE;
                }
            }
            @closedir( $handle );
        }
        else
        {
            trigger_error( "Can not open dir: {$dir}", E_USER_ERROR  );
            return FALSE;
        }  
        return TRUE;
    }
}

?>