<?php require_once('struct_php/global.php'); ?>
<!doctype html>
<html>
     <head>
         <title>光辉岁月</title>
         <meta name="keywords" content="L网络">
         <meta charset="utf-8">
         <link rel="stylesheet" href="CSS/lnet.css">
         <link  rel="stylesheet" href="CSS/photos.css">
     </head>
     <body>
          <?php require_once('struct_php/top_nav.php');?>
          <?php require_once('struct_php/photo_head.php');?>

          <div id="popular_albums">
                 <div class="content">
                     <h2>推荐相册</h2>
                     <div class="albums clearfix">
                         <?php
						    
						     $query = "select albums.album_id , albums.album_name,photos.photo_thumb_path  from albums  inner join photos"
							 ." on (albums.album_cover_id=photos.photo_id) inner join album_evaluate on ( albums.album_id = album_evaluate.album_id )".
							 "where albums.public_tag = 1 and albums.album_cover_id <> '444'  order by album_final_score desc limit 4";
							 $result=mysql_query($query,$dbc);
							  while($row = mysql_fetch_array($result))
							 {
								 echo '<div class="album">';
								 echo      '<div class="album_frame">';
								 echo          '<a href="album_new.php?album_id='.$row['album_id'].'">';
								 echo           '<img alt="图片" src="'.$row['photo_thumb_path'].'">';
								 echo           '</a>';
								 echo       '</div>';
								 echo       '<div class="album_name">'.$row['album_name'].'</div>';
								 echo '</div>';
								 
							 }
						 ?>
                          
                          
                     </div>
                 </div>
          </div>
           <div id="show_albums">
              <div class="content">
                  <h2>全部相册</h2>
                  <div class="albums clearfix">
                  <?php
						    
						     $query = "select albums.album_id , albums.album_name,photos.photo_thumb_path  from albums  inner join photos"
							 ." on (albums.album_cover_id=photos.photo_id) where albums.public_tag = 1 and albums.album_cover_id <> '444'  order by album_id desc" ;
							 $result=mysql_query($query,$dbc);
							  while($row = mysql_fetch_array($result))
							 {
								 echo '<div class="album">';
								 echo      '<div class="album_frame">';
								 echo          '<a href="album_new.php?album_id='.$row['album_id'].'">';
								 echo           '<img alt="图片" src="'.$row['photo_thumb_path'].'">';
								 echo           '</a>';
								 echo       '</div>';
								 echo       '<div class="album_name">'.$row['album_name'].'</div>';
								 echo '</div>';
								 
							 }
						 ?>
                  </div>
              </div>
           </div>
          
           <?php require_once('struct_php/footer.php');?>
           <div id="gray_layer">
            </div>  
            <div id="forms">
                <div class="content">
                      <?php require_once('struct_php/log_in_form.php');?>
                </div>
            </div>
         <script type="text/javascript" src="JS/lnet.js"></script>
         <script type="text/javascript" src="JS/photos.js"></script>
     </body>
</html>

