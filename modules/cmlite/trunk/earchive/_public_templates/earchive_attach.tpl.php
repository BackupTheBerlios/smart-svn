<?php /* attach Template. See also /view/class.view_attach.php */ ?>

<?php /* Only allow calling this template from whithin the application */ ?>
<?php if (!defined('SF_SECURE_INCLUDE')) exit; ?> 

<?php 
// send header and content
$error = HTTP_Download::staticSend($B->attach_params, false);

if (TRUE !== $error) 
{
    trigger_error($error->message." ".$B->attach_params['file']."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
}

exit;

?>