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
 * ActionNavigation class 
 * Some navigation action classes may extends this class
 *
 */

class ActionNavigation extends SmartAction
{
    /**
     * Fields and the format of each of the db table navigation_node 
     *
     */
    protected $tblFields_node = 
                      array('id_node'      => 'Int',
                            'id_parent'    => 'Int',
                            'id_sector'    => 'Int',
                            'id_view'      => 'Int',
                            'status'       => 'Int',
                            'rank'         => 'Int',
                            'format'       => 'Int',
                            'logo'         => 'String',
                            'media_folder' => 'String',
                            'lang'         => 'String',
                            'title'        => 'String',
                            'short_text'   => 'String',
                            'body'         => 'String');
                 
}

?>
