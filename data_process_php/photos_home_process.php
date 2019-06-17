<?php require_once('../function_php/lnet.php'); ?>
<?php require_once('../struct_php/global.php'); ?>
<?php
      
	  
      if(isset($_POST['create_album']))
	  {
		  $album_name = $_POST['album_name'];
		  $album_description = $_POST['album_description'];
		  if($album_name&&($user_id>0))
		  {
			  $query = "insert into albums (user_id , album_name, album_description , album_create_time )".
			  " values('$user_id','$album_name','$album_description' , now())";
			  mysql_query($query,$dbc);
		  }
	  }
	  if(isset($_GET['del_album_id']))
	  {
		   $del_album_id = $_GET['del_album_id'];
		   $query= "select * from albums where album_id = '$del_album_id'";
		   $result = mysql_query($query,$dbc);
		   if($row=mysql_fetch_array($result))
		   {
			   if($row['user_id']==$user_id)
			   {
				   $query="delete from albums where album_id = '$del_album_id'";
				   mysql_query($query,$dbc);
			   }
		   }
	  }
	  if(isset($_POST['upload_photos']))
	  {
		  $album_id =0;
		  $num =0;
		  $album_id = $_POST['album_id'];
		  $num = count($_FILES['upload_files']['tmp_name']);
		  if( $album_id && ($num> 0 )&& $user_id )
		  {
			  $dirs = array();
			  $dirs[0] = 'photos/'.$user_id.'/';
			  $dirs[1] = $dirs[0] . 'thumb/' ;
			  $dirs[2] = $dirs[0] . 'origin/';
			  for($i=0;$i<$num;$i++)
			  {
				  $file_name = rand().iconv('UTF-8','UTF-8',$_FILES['upload_files']['name'][$i]);
				  $temp = $_FILES['upload_files']['tmp_name'][$i];
				  if(img_check('upload_files',$i))
				  {
					 
				     //copy($temp,$dirs[2].$file_name);
					 $r1=resize($temp,1000);
					 $r2 = create_thumb($temp,300);
					 if($r1[0]&&$r2[0])
					 {
						 $path = $dirs[0].$r1[1];
						 $thumb_path = $dirs[1].$r2[1];
						 $origin_path = $dirs[2].$file_name;
						 copy($r1[1],$path);
						 copy($r2[1],$thumb_path);
						 move_uploaded_file($temp,$origin_path);
						 $query = "insert into photos ( user_id , album_id , photo_path , photo_thumb_path ,photo_origin_path ,photo_date)".
						 "values ('$user_id','$album_id','$path','$thumb_path','$origin_path',now())";
						 mysql_query($query,$dbc);
					 }
					 if($r1[0])
					       unlink($r1[1]);
					  if($r2[0])
					       unlink($r2[1]);
				  }
				 
			  }
		  }
	  }
?>