
<div class="top_nav clearfix">
       <ul>
           <li><a href="index.php">主页</a></li>
           <li><a href="#">电影</a></li>
           <li><a  href="index.php">图片</a></li>
           <li><a  href="english.php">英语</a></li>
           <li><a href="#">图书馆</a></li>
           <li><a href="#">博客</a></li>
           <li><a  href="manager.php">关于</a></li>
       
       </ul>
       <?php
	       if(isset($_COOKIE['user_name'])&&isset($_COOKIE['user_id']))
		   {
			   echo '<span class="login_info"><a  href="user_info.php">'.$_COOKIE['user_name'].'</a> <a href="../log_out.php?return_url='.$url.'">注销</a></span>';
		   }
		   else
		   {
			   echo '<span class="login_info"> <a  href="../log_in.php" >登录</a><a href="../register.php">注册</a></span>';
		    }
	   ?>
       
</div>