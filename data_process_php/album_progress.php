<?php
      session_start();
	  if(isset($_GET['album_upload']))
	  {
		  $response = array();
		  $file_names = $_GET['file_names'];
		  $name = $_GET['name'];
		  $key = ini_get("session.upload_progress.prefix").$name;
		  foreach($file_names as $filename)
		  {
			  $response[$filename] = array();
			  if(isset($_SESSION[$filename]))
			  {
				  switch($_SESSION[$filename]['status'])
				  {
					  case 'finished':
					  $response[$filename]['status'] = 'finished';
					   $response[$filename]['path'] = $_SESSION[$filename]['path'];
					  $response[$filename]['photo_path'] = $_SESSION[$filename]['temp_path'];
					  $response[$filename]['photo_origin_path'] = $_SESSION[$filename]['temp_origin_path'];
					  $response[$filename]['photo_thumb_path'] = $_SESSION[$filename]['temp_thumb_path'];
					  unset($_SESSION[$filename]);
					  break;
					  case 'mistake':
					  $response[$filename]['status'] = 'mistake';
					  $response[$filename]['error'] = $_SESSION[$filename]['error'];
					  unset($_SESSION[$filename]);
					  break;
					  case 'processing':
					  $response[$filename]['status'] = 'processing';
					  break; 
				  }
				 
			  }
			  else 
			  {
				  if(isset($_SESSION[$key]))
				  {
					  for( $i = 0; $i < count($_SESSION[$key]['files']) ; $i++)
					  {
						  if($_SESSION[$key]['files'][$i]['name']==$filename)
						  {
							  if($_SESSION[$key]['files'][$i]['done']==1)
							  {
								  $response[$filename]['status'] = 'processing';
							  }
							  else
							  {
								  $response[$filename]['status'] = 'loading';
								  $response[$filename]['bytes_processed'] = $_SESSION[$key]['files'][$i]['bytes_processed'];
							  }
							  break;
						   }
					  }
					  if($i >= count($_SESSION[$key]['files']))
					  {
					       $response[$filename]['status'] = 'waiting'; 
					  }
				  }
				  
			  }
		  }
		  echo json_encode($response);
		  
	  }
?>