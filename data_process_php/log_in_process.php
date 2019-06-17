<?php
       $error = '';
	   $user_id = '';
	   $user_name ='';
	   $user_pwd ='';
	   $return_url='http://'.$_SERVER['HTTP_HOST'].'/index.php';
	   if(isset($_GET['return_url']))
	   {
		   $return_url = $_GET['return_url'];
	   }
	   if(isset($_POST['login']))
	   {
		   $user_name = $_POST['user_name'];
		   $user_pwd = $_POST['user_pwd'];
		   if(empty($user_name)||empty($user_pwd))
		   {
			   $form_error = '用户名和密码不能为空！';
		   }
		   else
		   {
			   $query = "select * from users where user_name = '$user_name' and password = SHA('$user_pwd') ";
			   $result = mysql_query($query,$dbc);
			   if(mysql_num_rows($result)==1)
			   {
				   $row = mysql_fetch_array($result);
				   $user_id = $row['user_id'];
				   $user_name = $row['user_name'];
				   setcookie('user_name',$user_name,time()+360000);
				   setcookie('user_id',$user_id,time()+360000);
				   header ('Location:'.$return_url);
			    }
				else
				{
				    $form_error ='用户名或密码出现错误!';
				}
		   }
		 
	   }
		   
?>