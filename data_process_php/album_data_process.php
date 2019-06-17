<?php session_start(); ?>
<?php require_once('../function_php/lnet.php'); ?>
<?php require_once('../struct_php/global.php'); ?>

<?php
  
	if(isset($_POST['upload_photos']))
	  {
		 $files = $_FILES['photo_file'];
		 for( $i = 0; $i < count($files['name']); $i ++)
		 {
			 $file_name = $files['name'][$i];
			 $_SESSION[$file_name] = array();
			 $_SESSION[$file_name]['status'] = 'processing';
			 if(($files['type'][$i]=='image/jpeg')&&($files['size'][$i]>0))
			 {
				 $temp = $files['tmp_name'][$i];
				 $temp_origin_path = ROOT_PATH.'temp/origin'.rand().rand().'.jpg';
				 move_uploaded_file($temp,$temp_origin_path);
				 $_SESSION[$file_name]['temp_origin_path'] = $temp_origin_path;
				 try{
						 $re1 = create_thumb($temp_origin_path,300);
						 
						 if($re1[0])
						 {
							 $_SESSION[$file_name]['path'] = $re1[2];
							 $_SESSION[$file_name]['temp_thumb_path'] = $re1[1];
							 
							 
						 }
						 else
						 {
							  $_SESSION[$file_name]['status'] = 'mistake';
							  $_SESSION[$file_name]['error'] = '创建缩略图失败！';
						 }
						 $re2 = resize($temp_origin_path,1000);
						 if($re2[0])
						 {
							  $_SESSION[$file_name]['temp_path'] = $re2[1];
							  $_SESSION[$file_name]['status'] = 'finished';
						 }
						 else
						 {
							 $_SESSION[$file_name]['status'] = 'mistake';
							 $_SESSION[$file_name]['error'] = '上传图片尺寸太小，最小1000像素！';
						 }
				   }catch (Exception $e){
					   print_r ($e);
					   exit();
				   }
				 
			 }
			 else
			 {
				 $_SESSION[$file_name]['status'] = 'mistake';
				 $_SESSION[$file_name]['error'] = '传输文件无效！必须是jpeg格式！';
				 
			 }
			 
		 }
		 
	  }
	  if(isset($_POST['add_photos'])&&($user_id > 0)&&isset($_POST['album_id']))
	  {
		  $album_id = $_POST['album_id'];
		  $photo_dir = 'photos/'.$user_id.'/';
		  $thumb_dir = 'photos/'.$user_id.'/thumb/';
		  $origin_dir = 'photos/'.$user_id.'/origin/';
		  
		  if(!is_dir('../'.$photo_dir))
		  {
			  mkdir('../'.$photo_dir,0777,true);
		  }
		  if(!is_dir('../'.$thumb_dir))
		  {
			  mkdir('../'.$thumb_dir,0777,true);
		  }
		  if(!is_dir('../'.$origin_dir))
		  {
			  mkdir('../'.$origin_dir,0777,true);
		  }
		  
		  $photos_json = $_POST['photos_json'];
		  $photos = json_decode($photos_json,true);
		  foreach($photos as $photo)
		  {
			  $new_path = $photo_dir.basename($photo['path']);
			  $new_thumb_path = $thumb_dir.basename($photo['thumb_path']);
			  $new_origin_path = $origin_dir.basename($photo['origin_path']);
			  rename($photo['path'],ROOT_PATH.$new_path);
			  rename($photo['thumb_path'],ROOT_PATH.$new_thumb_path);
			  rename($photo['origin_path'],ROOT_PATH.$new_origin_path);
			  $query = "insert into photos (user_id , album_id , photo_path , photo_origin_path , photo_thumb_path , photo_date) ".
			  "  values ('$user_id','$album_id','$new_path','$new_origin_path','$new_thumb_path',now())";
			  mysql_query($query,$dbc);
			  
		  }
		  set_album_cover($album_id,$dbc);
		  
	  }
	  
	  function set_album_cover($album_id,$dbc){
		   $query = "select * from photos where album_id = '$album_id'";
		   $result = mysql_query($query,$dbc);
		   if($row = mysql_fetch_array($result))
		   {
			   $photo_id = $row['photo_id'];
			   $query = "select * from albums where album_id = '$album_id'";
			   $result2 = mysql_query($query,$dbc);
			   $row2 = mysql_fetch_array($result2);
			   if($row2['album_cover_id']==444)
			   {
				   $query = "update albums set album_cover_id = '$photo_id' where album_id = '$album_id'";
				   mysql_query($query,$dbc);
			   }
		   }
		   else
		   {
			   $query = "update albums set album_cover_id = '444' where album_id = '$album_id'";
			   mysql_query($query,$dbc);
		   }
	 }
	   
	
	
	 

?>
<?php
      mysql_close($dbc);
?>