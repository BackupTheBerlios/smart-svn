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
 * Test action (simple example)
 *
 */

/**
 * Every action must extends from the parent class SmartAction
 *
 * The name of an action class must follows the rules:
 * [Action][Module name with first letter uppercase][Action name with first letter uppercase]
 * 
 * The file name of an action must follows the rules:
 * The same as above but with extension ".php"
 *
 * An action file must be located in the specific module directory "/actions"
 *
 * Parent class vars are:
 * $this->model - The model object
 * $this->constructorData - Data passed to the constructor         
 *
 * @todo Should we introduce a permission methode or should permission
 * be done by the validate methode??
 * 
 */
class ActionCommonImageThumb extends SmartAction
{
    private $allowedImageTypes = array(1 => 'GIF',
                                       2 => 'JPEG',
                                       3 => 'PNG');
    /**
     * Perform on the action call
     *
     * @param mixed $data Data passed to this action
     */
    public function perform( $data = FALSE )
    {
        $this->imgDestHeight = (int) (($data['imgDestWidth'] / $this->imgSourceWidth) * $this->imgSourceHeight);    
        
        $destImage = $data['imgDestFolder'].'/'.$data['imgDestName'];
        
        switch($this->imgSourceType)
        {
            case 1:
                $this->resizeGIF( $data['imgSource'], $destImage, $data['imgDestWidth'] );
                break;
            case 2:
                $this->resizeJEPG( $data['imgSource'], $destImage, $data['imgDestWidth'] );
                break;
            case 3:
                $this->resizePNG( $data['imgSource'], $destImage, $data['imgDestWidth'] );
        }
        
        if(isset($data['info']))
        {
            $data['info']['mime']    = $this->imgSourceMime;
            $data['info']['size']    = $this->imgSourceSize;
            $data['info']['type']    = $this->imgSourceType;
            $data['info']['width']   = $this->imgSourceWidth;
            $data['info']['height']  = $this->imgSourceHeight;
            $data['info']['twidth']  = $data['imgDestWidth'];
            $data['info']['theight'] = $this->imgDestHeight;            
        }
    }
    
    /**
     * Validate data passed to this action
     */
    public function validate( $data = FALSE )
    {
        if(!isset($data['imgDestWidth']))
        {
            $data['error'] = "No Image Source. Please contact the administrator";
            return FALSE;
        }
        
        if(!isset($data['imgDestName']))
        {
            $data['error'] = "No Image Source. Please contact the administrator";
            return FALSE;
        }        
        
        if(!file_exists($data['imgSource']))
        {
            $data['error'] = "No Image Source. Please contact the administrator";
            return FALSE;
        }
        
        if(!is_writeable($data['imgDestFolder']))
        {
            $data['error'] = "Image destination folder isnt writeable. Please contact the administrator";
            return FALSE;
        }        
        
        $this->getImageInfo($data['imgSource']);
        
        if(!isset($this->allowedImageTypes[$this->imgSourceType]))
        {
            $data['error'] = "This image type isnt supported: ".$this->imgSourceType.' '.$this->imgSourceMime;
            return FALSE;
        }

        return TRUE;
    }    

    private function getImageInfo( &$image )
    {
        $img_info = getimagesize($image);
        $this->imgSourceWidth  = $img_info[0];
        $this->imgSourceHeight = $img_info[1];
        $this->imgSourceType   = $img_info[2];
        $this->imgSourceMime   = $img_info['mime'];
        $this->imgSourceSize   = filesize($image);
    }
    
    private function resizeJEPG( $jepgFile, $newImageFile, $destWidth )
    {
        $destImage = imagecreatetruecolor($destWidth, $this->imgDestHeight);
        $sourceImage = imagecreatefromjpeg($jepgFile);
        imagecopyresampled($destImage, $sourceImage, 0, 0, 0, 0, $destWidth, $this->imgDestHeight, $this->imgSourceWidth, $this->imgSourceHeight);

        imagejpeg($destImage, $newImageFile, 100);    
    }
    
    private function resizePNG( $pngFile, $newImageFile, $destWidth )
    {
        $destImage = imagecreatetruecolor($destWidth, $this->imgDestHeight);
        $sourceImage = imagecreatefrompng($pngFile);
        imagecopyresampled($destImage, $sourceImage, 0, 0, 0, 0, $destWidth, $this->imgDestHeight, $this->imgSourceWidth, $this->imgSourceHeight);

        imagepng($destImage, $newImageFile);    
    }   
    
    private function resizeGIF( $gifFile, $newImageFile, $destWidth )
    {
        $destImage = imagecreate($destWidth, $this->imgDestHeight);
        $sourceImage = imagecreatefromgif($gifFile);
        imagecopyresampled($destImage, $sourceImage, 0, 0, 0, 0, $destWidth, $this->imgDestHeight, $this->imgSourceWidth, $this->imgSourceHeight);

        imagegif($destImage, $newImageFile);    
    }    
}

?>