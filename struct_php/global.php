<?php
     
      //定义数据库连接信息
	 $dbc=mysql_connect("localhost:3306","root","hunan2010");
      mysql_select_db("l_net",$dbc);
	  mysql_query('SET NAMES UTF8');

      define('ROOT_PATH','/var/www/html/');
	  
	  //定义表单登录错误信息
	  
	  $form_error ='';
	  
	  
	  //定义当前浏览器范文URL
	  $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ;
	  $return_url='';
	  
	  $user_id = -1;
	  $user_name = '';
	  $portrait_id = -1 ;
	  
	  if(isset($_COOKIE['user_id']))
	  {
		  $user_id = $_COOKIE['user_id'];
		  $query = "select * from users where user_id = '$user_id'";
		  $result = mysql_query($query,$dbc);
		  $row =mysql_fetch_array($result);
		  $portrait_id = $row['portrait_id'];
		  
	  }
	  if(isset($_COOKIE['user_name']))
	  {
		  $user_name = $_COOKIE['user_name'];
	  }
	  
?>     
