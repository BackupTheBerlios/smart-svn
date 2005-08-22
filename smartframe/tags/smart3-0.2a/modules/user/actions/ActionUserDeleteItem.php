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
 * ActionUserDeleteItem class 
 *
 * USAGE:
 *
 * $model->action('user','deleteItem',
 *                array('id_pic'  => int, // one of both param
 *                      'id_file' => int))
 * 
 */

class ActionUserDeleteItem extends SmartAction
{
    /**
     * Delete user picture or file
     *
     * @param array $data
     * @return bool
     */
    public function perform( $data = FALSE )
    {
        if(isset($data['id_file']))
        {
            $this->deleteFile($data);
        }
        elseif(isset($data['id_pic']))
        {
            $this->deletePicture($data);
        }
        else
        {
            throw new SmartModelException("No 'id_file' or 'id_pic'");
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

        if(isset($data['id_pic']) && !is_int($data['id_pic']))
        {
            throw new SmartModelException("'id_pic' isnt from type int");
        }
        elseif(isset($data['id_file']) && !is_int($data['id_file']))
        {
            throw new SmartModelException("'id_file' isnt from type int");
        }        
        return TRUE;
    } 
    /**
     * delete a picture
     *
     * @param array $data
     */     
    private function deletePicture( &$data )
    {
        $pic = array();

        $this->model->action('user','getPicture',
                             array('result' => & $pic,
                                   'id_pic' => (int)$data['id_pic'],
                                   'fields' => array('file','id_user')));   

        $user = array();

        $this->model->action('user','getUser',
                             array('result'  => & $user,
                                   'id_user' => (int)$pic['id_user'],
                                   'fields'  => array('media_folder')));   

        $this->idUser = $pic['id_user'];
        $this->mediaFolder = &$user['media_folder'];

        if(!@unlink(SMART_BASE_DIR . 'data/user/'.$user['media_folder'].'/'.$pic['file']))
        {
           trigger_error('Cant delete user logo: data/user/'.$user['media_folder'].'/'.$_file['logo'], E_USER_WARNING);
        }
        if(!@unlink(SMART_BASE_DIR . 'data/user/'.$user['media_folder'].'/thumb/'.$pic['file']))
        {
           trigger_error('Cant delete user logo: data/user/'.$user['media_folder'].'/thumb/'.$_file['logo'], E_USER_WARNING);
        }    
        // remove picture reference from database
        $this->model->action('user','updatePicture',
                             array('action'  => 'delete',
                                   'id_pic'  => (int)$data['id_pic'],
                                   'id_user' => (int)$pic['id_user']));    
    }  
    /**
     * delete a file
     *
     * @param array $data
     */      
    private function deleteFile( &$data )
    {
        $file = array();

        $this->model->action('user','getFile',
                             array('result' => & $file,
                                   'id_file' => (int)$data['id_file'],
                                   'fields'  => array('file','id_user')));   

        $user = array();

        $this->model->action('user','getUser',
                             array('result'  => & $user,
                                   'id_user' => (int)$file['id_user'],
                                   'fields'  => array('media_folder')));   

        $this->idUser = $file['id_user'];
        $this->mediaFolder = &$user['media_folder'];

        if(!@unlink(SMART_BASE_DIR . 'data/user/'.$user['media_folder'].'/'.$file['file']))
        {
           trigger_error('Cant delete user logo: data/user/'.$user['media_folder'].'/'.$file['file'], E_USER_WARNING);
        }   
        // remove file reference from database
        $this->model->action('user','updateFile',
                             array('action'  => 'delete',
                                   'id_file' => (int)$data['id_file'],
                                   'id_user' => (int)$file['id_user']));    
    }
    /**
     * remove empty user directory
     *
     * @return bool
     */  
    private function removeEmptyDirectory()
    {
        $dir = SMART_BASE_DIR . 'data/user/' . $this->mediaFolder;
        
        if(TRUE == $this->isDirEmpty( $dir ))
        {
            // delete whole tree
            SmartCommonUtil::deleteDirTree( $dir );
            
            $error = array();
            // remove media_folder reference
            $this->model->action( 'user','update',
                                  array('id_user' => (int)$this->idUser,
                                        'error'   => &$error,
                                        'user' => array('media_folder' => '')) );
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