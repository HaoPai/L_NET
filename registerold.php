<?php
$page_title="用户登录";
$page_css="register.css";
require_once ('header.php');
?>
<div id="content">
<div id="message">
<?php 
    $form_put=true;
	$user_name='';
	$email='';
    if(isset($_POST['submit']))
	{
		$user_name=$_POST['user_name'];
		$email=$_POST['email'];
		$password=$_POST['password'];
		$password2=$_POST['password2'];
		if(!empty($user_name)&&!empty($email)&&!empty($password)&&!empty($password2)&&($password==$password2))
		{
			$dbc=mysql_connect("localhost:3310","root","hunan2010");
			mysql_select_db("haobai_web",$dbc);
			$query= "select * from users where user_name = '$user_name'";
			$result=mysql_query($query,$dbc);
			if(mysql_num_rows($result)==0)
			{
				$query="insert into users (user_name,email,password,join_date) values ( '$user_name','$email',SHA('$password'),now())";
				mysql_query($query,$dbc);
				echo "<p>恭喜您，您已经成功注册！</p>";
		        echo '<p><a href="log_in.php">登录</a></p>';
				$form_put=false;
			}
			else
			{
				echo '<p class="error">用户名已经存在，请使用其他用户名:</p>';
			}
			mysql_close($dbc);
			
		}
		else
		{
			if($password!=$password2)
			echo '<p class="error">两次输入的密码不匹配！</p>';
			echo '<p class="error">信息输入不完整，请继续输入！</p>';
		}
	}
	if($form_put==true)
	{
      
?>
</div>
<div id="register">
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">

    <h1>用户注册</h1>
    <p></p>
    <p><span>用户名：</span> <input type="text" value="<?php echo $user_name;?>" class="text" name="user_name" ></p>
   <p><span>邮箱地址:</span><input  type="text" value="<?php echo $email;?>" class="text" name="email"> </p>
   <p><span>输入密码：</span><input type="password" class="text" name="password" ></p>
   <p><span>确认密码:</span><input  type="password" class="text"  name="password2"> </p>
   <p><input type="submit"  class="buton"value="注册" name="submit"></p>

   
 </form>
 </div>
 <?php 
	}
	
 require_once('footer.php');
	?>

