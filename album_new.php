<?php session_start(); ?>
<?php require_once('function_php/lnet.php'); ?>
<?php require_once('struct_php/global.php'); ?>

<?php
    $album_id = -1;
	$page_user_id = -1 ;
	$album_name = "";
	if(isset($_GET['album_id']))
	 {
		 $album_id=$_GET['album_id'];
		 $query = "select * from albums where album_id = '$album_id'";
		 $result = mysql_query($query,$dbc);
		 if($row = mysql_fetch_array($result))
		  {
			  $page_user_id = $row['user_id'];
			  $album_name = $row['album_name'];
		  }
		
	 }

?>
<!DOCTYPE html>
<html>
     <head>
          <title>我的图片</title>
          <meta charset="utf-8">
          <link rel="stylesheet" href="CSS/lnet.css">
          <link rel="stylesheet" href="CSS/album.css">
         
     </head>
     <body>
            <?php require_once('struct_php/top_nav.php');?>
            <?php require_once('struct_php/photo_head.php');?>
            <?php require_once('struct_php/l_share_user.php');?>
            

            <div id="photos">
                  <div class="content clearfix">
                       <h2 id="album_title"><?php echo $album_name; ?><a class="right" href="photos_home_page.php?page_user_id=<?php echo $page_user_id; ?>" >全部相册</a><a class="right" style=" <?php if($page_user_id!= $user_id) echo 'display:none;'; ?>">管理</a><a class="right" style=" <?php if($page_user_id!= $user_id) echo 'display:none;'; ?>" >上传</a><a class="right" style="display:none" >完成</a><a class="right" style="display:none" >选择</a><a class="right" style="display:none" >移动</a></h2>
                       <ul class="clearfix">
                           <?php 
                            $query = "select * from photos where album_id = '$album_id' order by photo_id";
                            $result= mysql_query($query,$dbc);
                            $i = 0;
                            while($row=mysql_fetch_array($result))
                            {
                                
                            ?>
                            <li class="photo">
                                <div class="photo_frame">
                                    <a href="#<?php echo $row['photo_id']; ?>"><img  src="<?php echo $row['photo_thumb_path']; ?>" alt="图片"></a>
                                </div>
                                <div  class="photo_name" ><?php echo $row['photo_name']; ?></div>
                                <div class="shadow">
                                </div>
                                <a class="del_photo" href="#<?php echo $row['photo_id']; ?>">x</a>
                                <div class="check"><input type="checkbox" value="<?php echo $row['photo_id']; ?>"></div>
                           </li>
                           <?php  
                              $i++;
                            }
                           ?>
                           
                       </ul>
                  </div>
            </div>
             
            <div id="forms">
                     <div class="content">
                            <div id="upload_photos">
                                 <div class="form_head">
                                     <h2>上传图片</h2>
                                     <div class="select_album">上传到：<select>
                                             <?php
											    $query = "select * from albums where user_id = '$user_id' ";
												$result = mysql_query($query,$dbc);
												while($row = mysql_fetch_array($result))
												{
													if($row['album_id']==$album_id)
													{
														echo '<option value="'.$row['album_id'].'" selected >'.$row['album_name'].'</option>';
													}
													else
													{ 
													   echo '<option value="'.$row['album_id'].'">'.$row['album_name'].'</option>';
													}
												}
											 ?>
                                     </select></div>
                                 </div>
                                 <div class="form_body">
                                      <form action="data_process_php/album_data_process.php" method="post" enctype="multipart/form-data" target="shadow_frame">
                                      <input type="hidden" name="<?php echo ini_get('session.upload_progress.name'); ?>" value="form">
                                      <input id="photo_file" type="file" name="photo_file[]" accept="image/jpeg" multiple >
                                      <input type="hidden" name="upload_photos" value="">
                                      </form>
                                      <div class="wrapper">
                                            <label class=" select_photo" for="photo_file">选择图片</label>
                                       </div>
                                      <ul id="loading_files" class="clearfix">
                                           
                                      </ul>
                                 </div>
                                 <div class="form_foot">
                                        <div class="button">确认上传</div>
                                 </div>
                                 <div class="form_quit">X</div>
                            </div>  
                            <div id="move_to_album" >
                                <ul>
                                        <?php
										     $query = "select * from albums  where user_id = '$user_id' ";
											 $result = mysql_query($query,$dbc);
											 while($row = mysql_fetch_array($result))
											 {
												 echo '<li>';
												 echo '<a href="#'.$row['album_id'].'">'.$row['album_name'].'</a>';
												 echo '</li>';
											 }
										?>
                                </ul>
                                <div class="form_quit">X</div>
                            </div>     
                                 
                     </div>
            </div>
            
            
             
             <div id="show_layer">
                 <div class="show_back"></div> 
                 <div class="show_content">
                       <a id="prev_button"></a>
                       <a id="next_button"></a>
                       <div id="show_box" >
                             <div class="pic_frame">
                                  <img id="show_photo" >
                             </div>
                             <div class="comment">
                                    <div class="author_info">
                                       
                                    </div>
                                    <div class="comment_show">
                                    </div>
                                    <div class="comment_board">
                                    
                                    </div>
                             </div>
                        </div>
                        
                 </div>
                 <div class="quit">X</div>
             </div>
             <?php require_once('struct_php/log_in_form.php');?>
            <?php require_once('struct_php/footer.php');?>
             <script type="text/javascript" src="JS/lnet.js"></script>
			 <script type="text/javascript" src="JS/album.js"></script>
             
     </body>
</html>