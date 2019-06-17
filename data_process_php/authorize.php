<?php
	$auth_user_name = 'haopai';
	$auth_passwd = '945951';
	if(!isset($_SERVER['PHP_AUTH_USER'])||
	   !isset($_SERVER['PHP_AUTH_PW'])||
	   ($_SERVER['PHP_AUTH_USER']!=$auth_user_name)||
	   ($_SERVER['PHP_AUTH_PW']!=$auth_passwd)){
		   header('HTTP/1.1 401 Unauthorized');
		   header('WWW-Authenticate: Basic realm = "L_NET"');
		   exit('sorry,permission denied!');
	   }
?>