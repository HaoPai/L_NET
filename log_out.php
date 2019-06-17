<?php 
  $return_url = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/index.php';
  if(isset($_GET['return_url']))
  $return_url =  $_GET['return_url'];
  setcookie('user_name' , '' , time()-3600);
  setcookie('user_id' ,'' ,time()-3600);
  
  header('location:'.$return_url);
  ?>