<?php require_once('function_php/lnet.php'); ?>
<?php require_once('struct_php/global.php'); ?>
<?php
      $page_user_id = -1;
	   $page_user_name = "";
		if($user_id > 0)
		{
			$page_user_id = $user_id;
		}
		if(isset($_GET['page_user_id']))
		{
			$page_user_id = $_GET['page_user_id'];
			$query = "select * from users where user_id = '$page_user_id'";
			$result = mysql_query($query,$dbc);
			if($row = mysql_fetch_array($result))
			{
				$page_user_name = $row['user_name'];
			}
		}
?>


<!DOCTYPE html>
<html>
     <head>
          <title>我的图片</title>
          <meta charset="utf-8">
          <link rel="stylesheet" href="CSS/lnet.css">
          <link rel="stylesheet" href="CSS/photos_home_page.css">
     </head>
     <body>
            <?php require_once('struct_php/top_nav.php');?>
            <?php require_once('struct_php/photo_head.php');?>
            <?php require_once('struct_php/l_share_user.php');?>
            <div id="main">
                 <div class="albums">
                     <div class="content">
                         <h2 id="albums_title"><?php echo $page_user_name;  ?>的相册<a class="right" style=" <?php if($page_user_id!= $user_id) echo 'display:none;'; ?>">管理相册</a><a class="right" style=" <?php if($page_user_id!= $user_id) echo 'display:none;'; ?>">创建相册</a><a class="right finish" style="display:none;">完成</a></h2>
                         <ul class="clearfix">
                             <?php
						    if($user_id == $page_user_id)
  						     $query = "select albums.album_type , albums.album_id , albums.album_name,photos.photo_thumb_path  from albums  inner join photos"
  							 ." on (albums.album_cover_id=photos.photo_id) where albums.user_id = '$page_user_id'";
                else 
                  $query = "select albums.album_type , albums.album_id , albums.album_name,photos.photo_thumb_path  from albums  inner join photos"
                 ." on (albums.album_cover_id=photos.photo_id) where albums.user_id = '$page_user_id' and albums.public_tag = 1";
							 $result=mysql_query($query,$dbc);
							  while($row = mysql_fetch_array($result))
							 {
								  $top = 0;
								  $left = 0;
								  $min = 'width';
								 $size = getimagesize($row['photo_thumb_path']);
								 if($size[0]<=$size[1])
								 {
									 $top= -($size[1] * 200/$size[0]-200)/2 ;
								 }
								 else
								 {
									 $min = 'height';
									 $left= -($size[0] * 200/$size[1]-200)/2 ;
								 }
							 ?>
								<li class="album">
                                     
								     <div class="album_frame">
								          <a href="album_new.php?album_id=<?php echo $row['album_id'];?>">
                                              <img style=" position:absolute;
                                              top:<?php echo $top; ?>px;
                                              left:<?php echo $left; ?>px;" 
                                              <?php echo $min; ?>="200px"   
                                              src="<?php echo $row['photo_thumb_path']; ?>" 
                                              alt="图片">
                                           </a>
								     </div>
								     <div class="album_name"><?php echo $row['album_name'];?></div>
                                     <a class="del_album" href="#<?php if($row['album_type']==0) echo $row['album_id']; ?>">x</a>
                                    
								 </li>
						     <?php 		 
							 }
						 ?>
                         </ul>
                         
                     </div>
                 </div>
                 
            </div>
            <div id="forms">
                  <div class="content">
                         <div id="create_album">
                                <h2>创建相册</h2>
                                <div class="back_shadow">
                                </div>
                                <form>
                                     <table>
                                         <tr>
                                              <td>相册名：</td>
                                         </tr>
                                         <tr>
                                              <td><input type="text" id="album_name" name="album_name" required></td>
                                         </tr>
                                         <tr>
                                              <td>相册简介：</td>
                                         </tr>
                                         <tr>
                                               <td><textarea name="album_description" id="album_description"></textarea></td>
                                         </tr>
                                         <tr>
                                                <td><input id="create_album_submit" class="button" type="submit" name="create_name" value="创建"></td>
                                         </tr>
                                     </table>
                                </form>
                                <div class="quit_form">X</div>
                                
                         </div>
                         <?php require_once('struct_php/log_in_form.php');?>
                  </div>
            </div>
            
            <?php require_once('struct_php/footer.php');?>
            <script type="text/javascript" src="JS/lnet.js"></script>
            <script type="text/javascript" src="JS/photos_home_page.js"></script>
     </body>
</html>