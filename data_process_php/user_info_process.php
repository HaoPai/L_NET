<?php session_start(); ?>
<?php require_once('../function_php/lnet.php'); ?>
<?php require_once('../struct_php/global.php'); ?>

<?php
       if(isset($_POST['set_portrait']))
	   {
		   $_SESSION['portrait_file'] = array();
		   if(($_FILES['portrait_file']['type']=='image/jpeg')&&($_FILES['portrait_file']['size']>0)&&($_FILES['portrait_file']['error']==0))
		   {
			    $temp = $_FILES['portrait_file']['tmp_name'];
				$temp_path = ROOT_PATH.'temp/portrait'.rand().'.jpg';
				move_uploaded_file($temp,$temp_path);
				$re1 = create_thumb($temp_path,400);
				unlink($temp_path);
				if($re1[0])
				{
					$new_name = 'portrait'.rand().rand().'.jpg';
					$path = ROOT_PATH.'temp/'.$new_name;
					rename($re1[1],$path);
					$_SESSION['portrait_file'][0] = true; 
					$_SESSION['portrait_file'][1] = $new_name;
					$_SESSION['portrait_file'][2] = 'temp/'.$new_name;
					
				}
				else
				{
					$_SESSION['portrait_file'][0] = false; 
					$_SESSION['portrait_file'][1] = "图像太小"; 
				}
				
		   }
		   else
		   {
			   $_SESSION['portrait_file'][0] = false; 
			   $_SESSION['portrait_file'][1] = "文件不符合要求，仅接受jpeg或jpg格式！"; 
		   }
	   }
	   if(isset($_POST['load_portrait']))
	   {
		   $_SESSION['portrait'] = array();
		   if(($_FILES['portrait']['type']=='image/jpeg')&&($_FILES['portrait']['size']>0)&&($_FILES['portrait']['error']==0))
		   {
				$temp = $_FILES['portrait']['tmp_name'];
				$name = $_FILES['portrait']['name'];
				$temp_path = ROOT_PATH.'temp/portrait'.rand().'.jpg';
				move_uploaded_file($temp,$temp_path);
				$re1 = create_thumb($temp_path,300);
				unlink($temp_path);
				if($re1[0])
				{
					$new_name = 'portrait'.rand().rand().'.jpg';
					$path = ROOT_PATH.'img/portrait/'.$new_name;
					rename($re1[1],$path);
					$_SESSION['portrait'][0] = true; 
					$_SESSION['portrait'][1] = $new_name;
					$_SESSION['portrait'][2] = 'img/portrait/'.$new_name;
					
				}
				else
				{
					$_SESSION['portrait'][0] = false; 
					$_SESSION['portrait'][1] = "图像太小"; 
				}
		   }
		   else
		   {
			  $_SESSION['portrait'][0] = false; 
			  $_SESSION['portrait'][1] = "上传文件出错"; 
		   }
	   }
?>