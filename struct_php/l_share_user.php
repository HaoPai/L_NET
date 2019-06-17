<div id="user_info">
    <div class="content">
           <div class="user_info_box">
                <?php 
                     $query = "select  users.portrait_id , users.user_name , ".
                     "  user_info.user_city , user_info.user_word ,user_portrait.portrait_path".
                     "  from users ".
                     "  inner join user_info on (users.user_id = user_info.user_id )".
                     "  inner join user_portrait on (users.portrait_id = user_portrait.portrait_id) ".
                     "  where user_info.user_id = '$page_user_id'";
                     $result = mysql_query($query,$dbc);
                     if($row = mysql_fetch_array($result))
                     {
                         
                     
                ?>
                <div class="portrait">
                    <div class="portrait_frame">
                       <?php
                           $portrait_id = $row['portrait_id'];
                           if($portrait_id<=15)
                           {
                               $top = (int)(($portrait_id -1) / 3)*144+13;
                               $left = (int)(($portrait_id -1 )% 3)*166+21;
                      ?>
                                <a href="#">
                                <img src="img/portrait/portrait.png" style="top:-<?php echo $top; ?>px; left:-<?php echo $left; ?>px;">
                                </a>
                      <?php
                           }
						   else
						   {
						?>
                               <a href="#">
                                <img src="img/portrait/<?php echo $row['portrait_path']; ?>" style=" width:120px;">
                               </a>
                        <?php
						   }
                       ?>
                        
                    </div>
                </div>
                <div class="user_data">
                      <p><a class="user_name" href="photos_home_page.php?page_user_id=<?php echo $page_user_id; ?>"><?php echo $row['user_name']; ?></a></p> 
                      <p class="user_city">现居城市：<span id="city"><?php echo ($row['user_city'])? $row['user_city'] : '未设置'; ?></span>
                      <a id="change_city" style=" <?php if($page_user_id!= $user_id) echo 'display:none;'; ?>" href="#"></a></p>
                      <p class="user_word">个性签名：<span id="word"><?php echo ($row['user_word'])? $row['user_word'] : '未设置'; ?></span>
                      <a id="change_word" style=" <?php if($page_user_id!= $user_id) echo 'display:none;'; ?>" href="#"></a>
                      </p>
                </div>
                <?php
                     }
                ?>
           </div>
    </div>
</div>