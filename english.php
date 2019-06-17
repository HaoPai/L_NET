<?php require_once('struct_php/global.php'); ?>
<!DOCTYPE html>
<html>
      <head>
            <title>英语学习</title>
            <link rel="stylesheet" href="CSS/lnet.css">
            <link rel="stylesheet" href="CSS/english.css">
      </head>
      <body>
            <?php require_once('struct_php/top_nav.php');?>
            <div class="main_container">
            <div class="header">
                  <div class="content">
                       <h1>L英语</h1>
                  </div>
            </div>
            <div id="main">
                  <div class="nav">
                        <div class="content">
                              <ul class="clearfix">
                                   <li>听句子</li>
                                   <li>记单词</li>
                                   <li>编辑</li>
                                   <li>搜索</li>
                                   <li>记录</li>
                                   
                              </ul>
                        </div>
                  </div>
                  <div id="recite_sentance">
                         <div class="content">
                                <h2 id="dic_title">听写：新概念英语第四册第47课</h2>
                                <div id="show_frame">
                                      <p id="sentance"></p>
                                      <div id="audio_box"><audio id="sentance_audio" ></audio></div>
                                      <div id="sentance_check">
                                           <h2>对比结果:</h2>
                                           <p></p>
                                      </div>
                                      <div id="cover">—— 点我提示 ——</div>                                     
                                </div>
                                
                                <div id="sentance_dictation">
                                          <p class="manual"><button>重置</button><button>检查</button></p>
                                          <p><textarea></textarea></p>
                                          
                                 </div>
                                 <div id="operate_board">
                                       <div class="main_menu">
                                            <h3>发现英语</h3>
                                                <ul>
                                                    <li>经典教材<div class="tag"></div></li>
                                                    <li>经典美剧<div class="tag"></div></li>
                                                    <li>经典电影<div class="tag"></div></li>
                                                    <li>经典美文<div class="tag"></div></li>
                                                </ul>
                                            <h3>我的列表<span id="add_list">+</span></h3>
                                                <ul id="play_list">
                                                     <li>所有内容<div class="tag"></div></li>
                                                     <li>临时列表<div class="tag"></div></li>
        
                                             </ul>
                                             <div id="now_info">
                                                  <h4>当前资源</h4>
                                                  <div id ="flow">
                                                        <div class="play_now_title"></div> 
                                                        <div class="play_now_title"></div> 
                                                  </div>
                                             </div>
                                       </div>
                                       <div class="content_box">
                                           <div class="scroll_box">
                                               <div id="user_list">
                                                     <div class="list_head">
                                                          <h2>所有内容</h2>
                                                          <div class ="list_info">
                                                                <div class="portrait_frame"><img src="img/portrait/portrait-min.png" style=" <?php
																  if($portrait_id>0) 
																  {
																	   $top = (int)(($portrait_id-1) / 3)*72+7;
			                                                           $left = (int)(($portrait_id -1 )% 3)*83+11;
																	   echo 'top :'.(-$top).'px ; ';
																	   echo 'left :'.(-$left).'px ; ';
																  }
																  ?>"></div>
                                                                 <span class="user_name"><?php echo $user_name ; ?></span>
                                                                 <span class="create_date">2017-06-02创建</span>
                                                           </div>
                                                          <div class="buttons"><a href="#">播放全部</a><a href="#">快速添加</a><a href="#">删除列表</a></div>
                                                      </div>
                                                      <table class="list_items">
                                                          <thead>
                                                                <th>资源标题</th>
                                                                <th>播放</th>
                                                                <th>听写</th>
                                                                <th>移除</th>
                                                          </thead>
                                                          <tbody>
                                                          </tbody>
                                                         
                                                     </table>
                                               </div>
                                               <div id="empty_content">
                                                      <div class="pic_prame">
                                                      <h2>暂时没有资源</h2>
                                                      <img src="img/ico/sorry.png"></div>
                                               </div>
                                               <div id="classical_textbooks">
                                                      <div class="item">
                                                          <div class="pic_frame"><img src="img/nce4fggg.jpg" ></div>
                                                          <h2>新概念英语第四册</h2>
                                                      </div>
                                                      <table>
														   <?php 
                                                                 $query ="select * from english_resource".
																 " where resource_book_id = 1 order by resource_id desc ";
                                                                 $result = mysql_query($query,$dbc);
                                                                 while($row = mysql_fetch_array($result))
                                                                 {
																	 echo '<tr>';
                                                                     echo "<td>".$row['resource_name']."</td>";
																	 echo '<td> <a class= "play_source" href="#'.$row['resource_id'].'">播放</a></td>';
																	 echo '<td>收藏</td>';
																	 echo '</tr>';
                                                                 }
                                                            ?>
                                                            
                                                      </table>
                                               </div>
                                           </div>
                                            
                                       </div>
                                 </div>
                                 <div id="player_face">
                                       <div id="hide_face"><img src="img/ico/smaller.png"></div>
                                       <h2></h2>
                                       <div class="subtitle">
                                             <ul>
                                                   <li></li>
                                                   <li class="now"></li>
                                                   <li></li>
                                                  
                                             </ul>
                                       </div>
                                 </div>
                                 <div class="control clearfix">
                                       <div class="float_left">
                                       <a><img src="img/playlist4555.png" alt="列表" id="playlist"/><span class="play_info"></span></a>
                                       </div>
                                       <a><img src="img/previous_64px_1133645_easyicon.net.png" alt="向前" id="prev"/></a>
                                       <a><img src="img/play_64px_1133643_easyicon.net.png" alt="播放" id="play"/></a>
                                       <a><img src="img/pause_64px_1133640_easyicon.net.png" alt="暂停" id="pause" style="display:none;" /></a>
                                        <a><img src="img/next_64px_1133638_easyicon.net.png"alt="向后" id="next"/></a>
                                        <div class="float_right">
                                        <a><img src="img/shuufle.png" alt="列表" id="shuffle"/></a>  
                                        <a><img src="img/order25255.png" alt="order" id="order"/></a>    
                                        <a><img src="img/loop52555.png" alt="loop" id="loop"/></a> 
                                        </div>    
                                        
                                 </div> 
                             
                         </div>
                  </div>
                  <div id="query">
                           <div class="content">
                                <div id="query_area" class="clearfix">
                                      <div class="input"><input type="text"></div>
                                      <div class="search_button">搜索</div>
                                      <ul>
                                           <li></li>
                                           <li></li>
                                           <li></li>
                                           <li></li>
                                           <li></li>
                                      </ul>
                                </div>
                                 <div id="query_result">
                                      <div class="result_number"></div>
                                      <ul class="result_list">
                                      
                                      </ul>
                                 </div>
                           </div>
                  </div>
            </div>
            </div>
            <div id="forms">
                  <div class="content">
                       <?php require_once('struct_php/log_in_form.php');?>
                       <div id="select_sentances">
                            <div class="form_control">
                                       <span class="form_title">选择句子范围</span>
                           </div>
                            <div class="form_quit" onClick="hideForm('select_sentances')">X</div>
                            <ul class="clearfix">
                                 <?php
								   $query = "select * from english_resource order by resource_name";
								   $result= mysql_query($query,$dbc);
								   while($row = mysql_fetch_array($result))
								   {
									   echo '<li><input type="checkbox" class="checkbox" value="'.$row['resource_id'].'">'.$row['resource_name'].'</li>';
								   }
								 ?>
                            </ul>
                            <p class="control"><button>确定</button></p>
                            
                       </div>
                  </div>
            </div>
             <?php require_once('struct_php/footer.php');?>
             <script type="text/javascript" src="JS/lnet.js"></script>
            <script type="text/javascript" src="JS/english.js"></script>
      </body>
</html>