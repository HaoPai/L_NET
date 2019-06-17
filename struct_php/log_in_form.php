<div id="log_in">
         <div class="back_layer">
         </div>
         <div class="form_content">
         <div class="control_bar"></div>
             <div class="log_info">
             <h2>用户登录</h2>
             <form method="post" action="/log_in.php?return_url=<?php if($return_url) echo $return_url; else echo $url?>">
																  
                   <p class="error"><?php echo $form_error; ?></p>
                   <input type="text" placeholder="请输入用户名" name="user_name" class="text" autofocus required>
                   <input type="password" placeholder="请输入密码" name="user_pwd" class="text pwd" required>
                   <input type="submit" value="登录" name="login" class="submit">
              </form>
              <p><a>忘记密码？</a><a href="../register.php" class="reg">去注册</a></p>
             </div>
         <div id="quit_login" onClick="hideForm('log_in')">X</div>
         </div>
         
 </div>
 
