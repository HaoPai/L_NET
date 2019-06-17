<?php require_once('struct_php/global.php'); ?>
<!DOCTYPE html>
<html> 
     <head>
           <title><?php echo $user_name; ?></title>
           <link rel="stylesheet" href="CSS/lnet.css">
           <link rel="stylesheet" href="CSS/user_info.css">
     </head>
     <body>
           <?php require_once('struct_php/top_nav.php');?>
           <div id="main">
                <div class="content">
                     <div id="info_box">
                          <div class="box_title">个人设置</div>
                          <ul class="box_menu">
                               <li class="title">个人资料</li>
                               <li class="selected">基本资料</li>
                               <li>工作信息</li>
                               <li>教育背景</li>
                               <li>实名认证</li>
                               <li>安全设置</li>
                          </ul>
                          <div class="show_box" style="display:block;">
                               <div id="basic_info">
                               <h2>基本资料</h2>
                               <?php 
							   $user_sex = "未知";
							   $user_birthday="未知";
							   $user_city="未知";
							   $query = "select * from user_info where user_id = '$user_id'";
							   $result = mysql_query($query,$dbc);
							   if($row = mysql_fetch_array($result))
							   {
								   $user_sex = ($row['user_sex']==0)? '男':'女';
								   $user_birthday = $row['user_birthday'];
								   $user_city = $row['user_city'];
							   }
							  $query = "select portrait_id , user_name , join_date from users where user_id = '$user_id'";
							  $result = mysql_query($query,$dbc);
							  if($row=mysql_fetch_array($result))
							  {
								    $top = 0;
									$left = 0;
									$portrait_id = $row['portrait_id'];
									$user_name =  $row['user_name'];
									$join_date =  $row['join_date'];
									if($portrait_id <=15)	
									{
										$top = (int)(($portrait_id -1) / 3)*144+13;
										$left = (int)(($portrait_id -1 )% 3)*166+21;
										$portrait_path = "img/portrait/portrait.png";
									}
									else
									{
										$query = "select * from user_portrait where portrait_id = '$portrait_id'";
										$result2 = mysql_query($query,$dbc);
										$row2 = mysql_fetch_array($result2);
										$portrait_path = 'img/portrait/'.$row2['portrait_path'];
									}
									
							   ?>
                               <table>
                                  <tr>
                                      <td class="td_title">头像：</td>
                                      <td>
                                      <div class="portrait_frame"><img style="
                                      <?php 
									      if($portrait_id<=15)
										  {
												?>
												position:absolute;
												top:-<?php echo $top; ?>px;
												left:-<?php echo $left; ?>px;
												<?php
										  }
										  else
										  {
											    echo "width:120px;";
										  }
										  ?> " 
                                      src="<?php echo $portrait_path; ?>"></div>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td class="td_title">用户昵称：</td><td><?php echo $user_name; ?></td>
                                  </tr>
                                  <tr>
                                      <td class="td_title">性别：</td><td><?php echo $user_sex; ?></td>
                                  </tr>
                                  <tr>
                                      <td class="td_title">生日：</td><td><?php echo $user_birthday; ?></td>
                                  </tr>
                                  <tr>
                                      <td class="td_title">现居地：</td><td><?php echo $user_city; ?></td>
                                  </tr>
                                  <tr>
                                      <td class="td_title">注册日期：</td><td><?php echo $join_date; ?></td>
                                  </tr>
                                  <tr>
                                      <td class="td_title"></td>
                                      <td><button class="button">修改</button><button style="display:none;" class="button">保存</button></td>
                                  </tr>
                               </table>
                               
                               <?php 
									}
							   ?>
                           </div>
                          </div>
                          <div class="show_box">
                          </div>
                          <div class="show_box">
                          </div>
                          <div class="show_box">
                          </div>
                     </div>
                </div>
           </div>
           <div id="forms">
                 <div class="content">
                          <div id="select_portrait">
                              <h2 class="portrait_tab"><span class="selected">系统头像</span><span>上传头像</span></h2>
                              <div class="wraper">
                                      <div class="portrait_list clearfix">
                                      <?php
                                         for($i=0 ;$i <15 ; $i++)
                                         {
                                             $top = (int)(($i) / 3)*144+13;
                                             $left = (int)(($i)% 3)*166+21;
                                             echo '<div class="portrait_frame">
                                             <img style="
                                              position:absolute;
                                              top:-'.$top.'px;
                                              left:-'.$left.'px;
                                              " 
                                              src="img/portrait/portrait.png">
                                              </div>';
                                         }
                                      ?>
                                       </div>
                                       <div id="upload_portrait">
                                             <div class="right">
                                                   <h3>头像预览</h3>
                                                   <div class="portrait_display"><img id="portrait_final"></div>
                                             
                                             </div>
                                             <div class="show_box">
                                                  <label for="portrait_file" class="button portrait_button">选择头像</label>
                                                  <div id="portrait_message">
                                                         <p>文件：<span class="file_name">123.jpg</span></p>
                                                         <p>状态：<span class="file_status">上传中.....</span></p>
                                                  </div>
                                                  <img id="portrait_img">
                                                  <div class="progress">
                                                      <div id="progress_bar"></div>
                                                  </div>
                                             </div>
                                            
                                             <form enctype="multipart/form-data" action="data_process_php/user_info_process.php" method="post" target="shadow_frame">
                                                   <input type="hidden" name="<?php echo ini_get('session.upload_progress.name'); ?>" value="portrait">
                                                   <input type="file" id="portrait_file" name="portrait_file" accept="image/jpeg">
                                                   <input type="hidden" name="set_portrait" value="">
                                             </form>
                                       </div>
                               </div>
                               <p><button class="button">确认</button><button class="button" style="display:none;">重置</button></p>
                               <div id="quit_login" onClick="hideForm('select_portrait')">X</div>
                          </div>
                 </div>
           </div>
           <?php require_once('struct_php/footer.php');?>
           <script type="text/javascript" src="JS/lnet.js"></script>
           <script type="text/javascript" src="JS/user_info.js"></script>
     </body>
</html>
<input type="radio" checked >