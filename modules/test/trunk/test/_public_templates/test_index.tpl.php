<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta name="robots" content="index">
<meta name="description" content="<?php echo str_replace("\"","'",$B->sys['option']['site_desc']); ?>">
<meta name="keywords" content="<?php echo str_replace("\"","'",$B->sys['option']['site_desc']); ?>">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $B->sys['option']['charset']; ?>">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php echo htmlspecialchars($B->sys['option']['site_title']); ?></title>
</head>

<body>
<p><a href="admin/index.php">admin</a></p>
<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">This is the public web page which is loaded by default. You will find the
    template of this page in the root folder <strong>/test_index.tpl.php</strong>
</font></p>
<p align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="index.php?tpl=showvars">switch
    to an other page</a></font></p>
<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">This template show you how to use public functions of the test module which
  are declared in the public event handler <strong>/admin/modules/test/event_handler_public.php</strong>.</font></p>
<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Counter function <strong>EVT_TEST_COUNTER</strong>:</font></p>
<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
<!-- Call the counter function of the test module defined in /admin/modules/test/event_handler_public.php -->
<!-- The function assign numbers to a defined array -->
<!-- The function is called indirectly through the class function $B->M() -->
<!-- This class function accepts 3 arguments -->
<!-- The arguments:
                   MOD_TEST -  The module where the really funtion is declared. 
				               This variable is declared in the 
							   /admin/modules/test/event_handler_public.php file 
				  EVT_TEST_COUNTER - This is the funtionality to execute. Defined in the
				                     /admin/modules/test/event_handler_public.php file 
	              array() - This array can contains additional data which may is required by the specific function
				            'var' - the array variable to store the result. You will find the result in $B->counter
				            'start_counter' - the num where the counter is starting
							'end_counter' - the num where the cuonter ends
-->				   
<?php $B->M( MOD_TEST, 
			 EVT_TEST_COUNTER, 
             array('var' => 'counter', 'start_counter' => 0, 'end_counter' => 200)); ?> 
</font></p>
<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>
<!-- print out the counter digits -->
<?php foreach($B->counter as $c) echo $c.' '; ?>
</strong></font></p>

</font>
</body>
</html>
