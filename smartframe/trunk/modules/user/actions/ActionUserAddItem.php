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
 * ActionUserAddItem class 
 *
 */
include_once(SMART_BASE_DIR . 'modules/user/includes/ActionUserFileUploadBase.php');

class ActionUserAddItem extends ActionUserFileUploadBase
{
    /**
     * add user picture or file
     *
     * @param array $data
     * @return int user id or false on error
     */
    function perform( $data = FALSE )
    { 
        $media_folder = $this->getUserMediaFolder( $data['id_user'] );
        
        $file_info = $this->getUniqueMediaFileName($media_folder, $_FILES[$data['postName']]['name']);

        if(FALSE == $this->moveUploadedFile($_FILES[$data['postName']]['tmp_name'], $file_info['file_path']))
        { 
            throw new SmartModelException ('Cant upload file');   
        }
        
        // set table name and item reference
        if($data['item'] == 'picture')
        {
            $this->addPicture($data, $media_folder, $file_info);
        }
        else
        {
            $this->addFile($data, $media_folder, $file_info);
        }
        
        return TRUE;
    }
    
    /**
     * validate user data
     *
     * @param array $data User data
     * @return bool 
     */    
    function validate( $data = FALSE )
    {
        // check if user exists
        if( !isset($data['postName']) || empty($data['postName']) )
        {        
            throw new SmartModelException ('"post_name" must be defined in view class'); 
        }
        // validate user name
        elseif( !isset($_FILES[$data['postName']]) )
        {
            throw new SmartModelException ('You have to select a local file to upload');
        }    

        if(!isset($data['item']))
        {
            throw new SmartModelException("No 'item' defined");
        }
        elseif(($data['item'] != 'picture') && ($data['item'] != 'file'))
        {
            throw new SmartModelException("'item' must be 'file' or 'picture'");
        }
        if(!isset($data['id_user']))
        {
            throw new SmartModelException("No 'id_user' defined");
        }
        elseif(preg_match("/[^0-9]/",$data['id_user']))
        {
            throw new SmartModelException("'id_user' isnt numeric");
        }  
        
        return TRUE;
    }
    /**
     * add user file db reference
     *
     * @param array $data User data
     * @param string &$media_folder
     * @param array &$file_info
     */   
    private function addFile( &$data, &$media_folder, &$file_info )
    {
        $rank = $this->getNewLastRank( $data['id_user'], 'user_media_file' );

        $_file = SMART_BASE_DIR . "data/user/" . $media_folder . '/' . $file_info['file_name'];
        
        // get mime type
        $type = '';
        if (function_exists('mime_content_type')) 
        {
            $type = mime_content_type($_file);
        }
        else
        {
            $type = $this->get_mime( $file_info['file_name'] );
        }

        $file_size = filesize($_file);
       
        $sql = "INSERT INTO {$this->config['dbTablePrefix']}user_media_file
                   (id_user,rank,file,size,mime)
                  VALUES
                   ({$data['id_user']},
                    {$rank},
                    '{$file_info['file_name']}',
                    {$file_size},
                    '{$type}' )";

        $this->model->dba->query($sql);     
    }
    /**
     * add user picture db reference
     *
     * @param array $data User data
     * @param string &$media_folder
     * @param array &$file_info
     */       
    private function addPicture( &$data, &$media_folder, &$file_info )
    {
        $image_source = SMART_BASE_DIR . "data/user/" . $media_folder . '/' . $file_info['file_name'];
        $image_dest_folder   = SMART_BASE_DIR . "data/user/" . $media_folder . '/thumb';
        
        $pic_info = array();
        
        $this->model->action('common','imageThumb',
                             array('imgSource'     => $image_source,
                                   'imgDestName'   => $file_info['file_name'],
                                   'imgDestWidth'  => $this->config['user']['thumb_width'],
                                   'imgDestFolder' => $image_dest_folder,
                                   'info'          => &$pic_info));  
        
        $rank = $this->getNewLastRank( $data['id_user'], 'user_media_pic' );
                
        $sql = "INSERT INTO {$this->config['dbTablePrefix']}user_media_pic
                   (id_user,rank,file,size,mime)
                  VALUES
                   ({$data['id_user']},
                    {$rank},
                    '{$file_info['file_name']}',
                    {$pic_info['size']},
                    '{$pic_info['mime']}' )";

        $this->model->dba->query($sql);                                       
    }
    
    /**
     * get new last item rank
     *
     * @param int $id_user User ID
     * @return int Rank number
     */    
    function getNewLastRank( $id_user, $table )
    {
        $sql = "
            SELECT
                rank
            FROM
                {$this->config['dbTablePrefix']}{$table}  
            WHERE
                id_user='$id_user'
            ORDER BY 
                rank DESC
            LIMIT 1";
        
        $stmt = $this->model->dba->query($sql);

        if($stmt->numRows() == 1)
        {
            $row = $stmt->fetchAssoc();
            return ++$row['rank'];
        }
        return 1;    
    } 
    
    /**
     * get_extension
     *
     * Get the extension of a file
     *
     * @param string $file file name
     * @return string Extension eg. ".zip"
     */ 
    private function get_extension( &$file )
    {
        return strrchr($file, '.');
    }      
    /**
     * get_mime
     *
     * Get the mime type of a file. A file type is identified by its extension
     *
     * @param string $file file name
     * @return string Mime type
     */    
    private function get_mime( &$file )
    {
        $_mimetypes = array(
         ".ez"      => "application/andrew-inset",
         ".hqx"     => "application/mac-binhex40",
         ".cpt"     => "application/mac-compactpro",
         ".doc"     => "application/msword",
         ".bin"     => "application/octet-stream",
         ".dms"     => "application/octet-stream",
         ".lha"     => "application/octet-stream",
         ".lzh"     => "application/octet-stream",
         ".exe"     => "application/octet-stream",
         ".class"   => "application/octet-stream",
         ".so"      => "application/octet-stream",
         ".dll"     => "application/octet-stream",
         ".oda"     => "application/oda",
         ".pdf"     => "application/pdf",
         ".ai"      => "application/postscript",
         ".eps"     => "application/postscript",
         ".ps"      => "application/postscript",
         ".smi"     => "application/smil",
         ".smil"    => "application/smil",
         ".wbxml"   => "application/vnd.wap.wbxml",
         ".wmlc"    => "application/vnd.wap.wmlc",
         ".wmlsc"   => "application/vnd.wap.wmlscriptc",
         ".bcpio"   => "application/x-bcpio",
         ".vcd"     => "application/x-cdlink",
         ".pgn"     => "application/x-chess-pgn",
         ".cpio"    => "application/x-cpio",
         ".csh"     => "application/x-csh",
         ".dcr"     => "application/x-director",
         ".dir"     => "application/x-director",
         ".dxr"     => "application/x-director",
         ".dvi"     => "application/x-dvi",
         ".spl"     => "application/x-futuresplash",
         ".gtar"    => "application/x-gtar",
         ".hdf"     => "application/x-hdf",
         ".js"      => "application/x-javascript",
         ".skp"     => "application/x-koan",
         ".skd"     => "application/x-koan",
         ".skt"     => "application/x-koan",
         ".skm"     => "application/x-koan",
         ".latex"   => "application/x-latex",
         ".nc"      => "application/x-netcdf",
         ".cdf"     => "application/x-netcdf",
         ".sh"      => "application/x-sh",
         ".shar"    => "application/x-shar",
         ".swf"     => "application/x-shockwave-flash",
         ".sit"     => "application/x-stuffit",
         ".sv4cpio" => "application/x-sv4cpio",
         ".sv4crc"  => "application/x-sv4crc",
         ".tar"     => "application/x-tar",
         ".tcl"     => "application/x-tcl",
         ".tex"     => "application/x-tex",
         ".texinfo" => "application/x-texinfo",
         ".texi"    => "application/x-texinfo",
         ".t"       => "application/x-troff",
         ".tr"      => "application/x-troff",
         ".roff"    => "application/x-troff",
         ".man"     => "application/x-troff-man",
         ".me"      => "application/x-troff-me",
         ".ms"      => "application/x-troff-ms",
         ".ustar"   => "application/x-ustar",
         ".src"     => "application/x-wais-source",
         ".xhtml"   => "application/xhtml+xml",
         ".xht"     => "application/xhtml+xml",
         ".zip"     => "application/zip",
         ".au"      => "audio/basic",
         ".snd"     => "audio/basic",
         ".mid"     => "audio/midi",
         ".midi"    => "audio/midi",
         ".kar"     => "audio/midi",
         ".mpga"    => "audio/mpeg",
         ".mp2"     => "audio/mpeg",
         ".mp3"     => "audio/mpeg",
         ".aif"     => "audio/x-aiff",
         ".aiff"    => "audio/x-aiff",
         ".aifc"    => "audio/x-aiff",
         ".m3u"     => "audio/x-mpegurl",
         ".ram"     => "audio/x-pn-realaudio",
         ".rm"      => "audio/x-pn-realaudio",
         ".rpm"     => "audio/x-pn-realaudio-plugin",
         ".ra"      => "audio/x-realaudio",
         ".wav"     => "audio/x-wav",
         ".pdb"     => "chemical/x-pdb",
         ".xyz"     => "chemical/x-xyz",
         ".bmp"     => "image/bmp",
         ".gif"     => "image/gif",
         ".ief"     => "image/ief",
         ".jpeg"    => "image/jpeg",
         ".jpg"     => "image/jpeg",
         ".jpe"     => "image/jpeg",
         ".png"     => "image/png",
         ".tiff"    => "image/tiff",
         ".tif"     => "image/tif",
         ".djvu"    => "image/vnd.djvu",
         ".djv"     => "image/vnd.djvu",
         ".wbmp"    => "image/vnd.wap.wbmp",
         ".ras"     => "image/x-cmu-raster",
         ".pnm"     => "image/x-portable-anymap",
         ".pbm"     => "image/x-portable-bitmap",
         ".pgm"     => "image/x-portable-graymap",
         ".ppm"     => "image/x-portable-pixmap",
         ".rgb"     => "image/x-rgb",
         ".xbm"     => "image/x-xbitmap",
         ".xpm"     => "image/x-xpixmap",
         ".xwd"     => "image/x-windowdump",
         ".igs"     => "model/iges",
         ".iges"    => "model/iges",
         ".msh"     => "model/mesh",
         ".mesh"    => "model/mesh",
         ".silo"    => "model/mesh",
         ".wrl"     => "model/vrml",
         ".vrml"    => "model/vrml",
         ".css"     => "text/css",
         ".html"    => "text/html",
         ".htm"     => "text/html",
         ".asc"     => "text/plain",
         ".txt"     => "text/plain",
         ".rtx"     => "text/richtext",
         ".rtf"     => "text/rtf",
         ".sgml"    => "text/sgml",
         ".sgm"     => "text/sgml",
         ".tsv"     => "text/tab-seperated-values",
         ".wml"     => "text/vnd.wap.wml",
         ".wmls"    => "text/vnd.wap.wmlscript",
         ".etx"     => "text/x-setext",
         ".xml"     => "text/xml",
         ".xsl"     => "text/xml",
         ".mpeg"    => "video/mpeg",
         ".mpg"     => "video/mpeg",
         ".mpe"     => "video/mpeg",
         ".qt"      => "video/quicktime",
         ".mov"     => "video/quicktime",
         ".mxu"     => "video/vnd.mpegurl",
         ".avi"     => "video/x-msvideo",
         ".movie"   => "video/x-sgi-movie",
         ".ice"     => "x-conference-xcooltalk"
        );
        
        return $_mimetypes[$this->get_extension( strtolower($file) )];
    }        
}

?>
