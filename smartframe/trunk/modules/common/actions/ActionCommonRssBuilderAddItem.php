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
 * ActionCommonRssBuilderAddItem
 *
 * USAGE: 
 */
 
/**
 * 
 */
class ActionCommonRssBuilderAddItem extends SmartAction
{
    /**
     */
    public function perform( $data = FALSE )
    {
        if(!isset($data['about']))
        {
            $data['about'] = '';
        }
        $about = $link = (string) $data['about'];

        if(!isset($data['title']))
        {
            $data['title'] = '';
        } 
        $title = (string) $data['title'];

        if(!isset($data['description']))
        {
            $data['description'] = '';
        } 
        $description = (string) $data['description'];

        if(!isset($data['subject']))
        {
            $data['subject'] = '';
        } 
        $subject = (string) $data['subject'];
        
        $date = (string) time();
        
        if(!isset($data['author']))
        {
            $data['author'] = '';
        }        
        $author = (string) $data['author']; // (only rss 2.0)
        
        if(!isset($data['comments']))
        {
            $data['comments'] = '';
        }         
        $comments = (string) $data['comments']; // in minutes (only rss 2.0)

        if(!isset($data['image']))
        {
            $data['image'] = '';
        }         
        $image = (string) $data['image']; // in minutes (only rss 2.0)

        $data['rssObject']->addRSSItem($about, $title, $link, $description, $subject, $date,  $author, $comments, $image);
    }
    
    /**
     */
    public function validate( $data = FALSE )
    {
        if(!isset($data['rssObject']))
        {
            throw new SmartModelException("Missing 'rssObject' object var"); 
        } 
        if(!is_object($data['rssObject']))
        {
            throw new SmartModelException("Var 'rssObject' isnt from type object"); 
        }        
        

        if(!isset($data['title']))
        {
            throw new SmartModelException("Missing 'title' var"); 
        } 

        if(!isset($data['about']))
        {
            throw new SmartModelException("Missing 'about' var");
        }
        
        return TRUE;
    }    
}

?>