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
 * ActionCommonGmtConverter
 *
 */

class ActionCommonGmtConverter extends SmartAction
{
    /**
     * Perform on the action call
     *
     * @param mixed $data
     */
    public function perform( $data = FALSE )
    {
        $timezone = 3600 * $data['timezone'];
        
        $timestamp = $this->_Mktime( $data['date']['year'], 
                                     $data['date']['month'], 
                                     $data['date']['day'], 
                                     $data['date']['hour'], 
                                     $data['date']['minute']);
    
        if($data['action'] == "gmtToDate")
        {
            $this->gmtToDate( $data['date'], $timestamp, $timezone );
        }
        elseif($data['action'] == "dateToGmt")
        {
            $this->dateToGmt( $data['date'], $timestamp, $timezone );
        }
    }
    
    /**
     */
    public function validate( $data = FALSE )
    {
        if(!isset($data['action']))
        {
            throw new SmartModelException("var 'action' isnt defined");
        }
        if(($data['action'] != "gmtToDate") && ($data['action'] != "dateToGmt"))
        {
            throw new SmartModelException("var 'action' has wrong value: 'gmtToDate' or 'dateToGmt'");
        }    
        if(!isset($data['date']))
        {
            throw new SmartModelException("array 'date' isnt defined");
        }
        if(!is_array($data['date']))
        {
            throw new SmartModelException("var 'date' isnt from type array");
        }
        if(!isset($data['date']['year']))
        {
            throw new SmartModelException("array var 'date''year' isnt set");
        }
        if(!preg_match("/[0-9]{4}/",$data['date']['year']))
        {
            throw new SmartModelException("array var 'date''year' has wrong value");
        }
        if(!isset($data['date']['month']))
        {
            throw new SmartModelException("array var 'date''month' isnt set");
        }
        if(!preg_match("/[0-9]{1,2}/",$data['date']['month']))
        {
            throw new SmartModelException("array var 'date''month' has wrong value");
        }   
        if(!isset($data['date']['day']))
        {
            throw new SmartModelException("array var 'date''day' isnt set");
        }
        if(!preg_match("/[0-9]{1,2}/",$data['date']['day']))
        {
            throw new SmartModelException("array var 'date''day' has wrong value");
        } 
        if(!isset($data['date']['hour']))
        {
            throw new SmartModelException("array var 'date''hour' isnt set");
        }
        if(!preg_match("/[0-9]{1,2}/",$data['date']['hour']))
        {
            throw new SmartModelException("array var 'date''hour' has wrong value");
        } 
        if(!isset($data['date']['minute']))
        {
            throw new SmartModelException("array var 'date''minute' isnt set");
        }
        if(!preg_match("/[0-9]{1,2}/",$data['date']['minute']))
        {
            throw new SmartModelException("array var 'date''minute' has wrong value");
        } 
        
        return TRUE;
    }  

    private function gmtToDate( & $data, $timestamp, $timezone )
    {                            
        $this->_Date( $data, $timestamp, $timezone, "add" );
    }

    private function dateToGmt( & $data, $timestamp, $timezone )
    {                            
        $this->_Date( $data, $timestamp, $timezone, "sub" );
    }
    
    private function _Mktime( $year, $month, $day, $hour, $minute )
    {
        return mktime( $hour, $minute, "0", $month, $day, $year );
    }
    
    private function _Date( & $data, $timestamp, $timezone, $cal )
    {  
        if($cal == "sub")
        {
            $timestamp = $timestamp - $timezone;
        }
        elseif($cal == "add")
        {
            $timestamp = $timestamp + $timezone;
        }
        
        $data['year']   = date( "Y", $timestamp );
        $data['month']  = date( "m", $timestamp );
        $data['day']    = date( "d", $timestamp );
        $data['hour']   = date( "H", $timestamp );
        $data['minute'] = date( "i", $timestamp );
    }
}

?>