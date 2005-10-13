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
 * ActionArticleDeleteLogo class 
 *
 * USAGE:
 * 
 * $model->action('article','deleteLogo',
 *                array('id_article' => int))
 */

class ActionArticleDeleteLogo extends SmartAction
{
    /**
     * Delete node logo
     *
     * param:
     * data['id_article']
     *
     * @param array $data
     * @return bool
     */
    public function perform( $data = FALSE )
    {
        $article = array();

        $this->model->action('article','getArticle',
                             array('result'     => & $article,
                                   'error'      => & $data['error'],
                                   'id_article' => (int)$data['id_article'],
                                   'fields'     => array('logo','media_folder')));   

        if(!@unlink(SMART_BASE_DIR . 'data/article/'.$article['media_folder'].'/'.$article['logo']))
        {
            throw new SmartModelException('Cant delete user logo: data/article/'.$article['media_folder'].'/'.$article['logo']);
        }
                            
        $this->model->action('article','updateArticle',
                             array('id_article' => (int)$data['id_article'],
                                   'error'      => & $data['error'],
                                   'fields'     => array('logo' => '')));

        $this->removeEmptyDirectory( $article['media_folder'], $data );
        
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
        if(!isset($data['id_article']))
        {
            throw new SmartModelException('"id_article" isnt defined');        
        }    
        elseif(!is_int($data['id_article']))
        {
            throw new SmartModelException('"id_article" isnt from type int');        
        }
        
        return TRUE;
    }
    
    /**
     * remove empty user directory
     *
     */  
    private function removeEmptyDirectory( &$media_folder, &$data )
    {
        $dir = SMART_BASE_DIR . 'data/article/' . $media_folder;
        
        if(TRUE == $this->isDirEmpty( $dir ))
        {
            // delete whole tree
            SmartCommonUtil::deleteDirTree( $dir );
            // remove media_folder reference
            $this->model->action( 'article','updateArticle',
                                  array('id_article' => (int)$data['id_article'],
                                        'error'      => & $data['error'],
                                        'fields'     => array('media_folder' => '')) );
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
                if ( ( $file == "." ) || ( $file == ".." ) || is_dir($dir . '/' . $file) )
                {
                    continue;
                }
                if ( file_exists( $dir . '/' . $file ) )
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