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
 * Email obfuscation function
 *
 * @param string $string
 * @param string $type 'simple', 'text', 'javascript'
 * @return string Encoded string 
 */
function mailto( &$string, $type = 'text' )
{
    switch ($type)
    {
        case 'simple':
            $string = preg_replace("/(mailto:[^@]{2,})@/","\\1%40",$string);
        case 'text':
            return str_replace("@", " AT ", $string);
            break;               
        case 'javascript':
            preg_match_all('!<a\s([^>]*)href=["\']mailto:([^"\']+)["\']([^>]*)>(.*?)</a[^>]*>!is', $string, $matches);
    
            // $matches[0] contains full matched string: <a href="...">...</a>
            // $matches[1] contains additional parameters
            // $matches[2] contains the email address which was specified as href
            // $matches[3] contains additional parameters
            // $matches[4] contains the text between the opening and closing <a> tag
            $replace = $matches[0];
            foreach ($matches[0] as $key => $match) {
              $address = $matches[2][$key];
              $obfuscated_address = str_replace("@", " AT ", $address);
              $extra = trim($matches[1][$key]." ".$matches[3][$key]);
              $text = $matches[4][$key];
              $obfuscated_text = str_replace("@", " AT ", $text);
        
              $string2 = "document.write('<a href=\"mailto:".$address."\" ".$extra.">".$text."</a>');";
              $js_encode = '';
              for ($x=0; $x < strlen($string2); $x++) {
                $js_encode .= '%' . bin2hex($string2[$x]);
              }
              $replace[$key] =  '<script type="text/javascript" language="javascript">eval(unescape(\''.$js_encode.'\'))</script>';     
            }
            
            return str_replace($matches[0], $replace, $string);
            break;        
    }
}

?>
