<?php require_once('../struct_php/global.php'); ?>
<?php require_once('../function_php/lnet.php'); ?>




<?php

	if(isset($_POST['upload_mistakes']))
	{
		$mistakes_json = $_POST['mistakes_json'];
		$english_mistakes = json_decode($mistakes_json,true);
		for($i =0;$i < count($english_mistakes); $i++)
		{
			$temp = $english_mistakes[$i];
			$sentance_id = $temp['sentance_id'];
			$mistake_id = add_mistake($temp,$dbc);
			add_mistake_log( $mistake_id ,$sentance_id ,$dbc);
		 }
	 }
	 if(isset($_POST['add_manager_message']))
	 {
		 $message = $_POST['manager_message'];
		 if(isset($_COOKIE['user_id']))
	     {
		     $user_id = $_COOKIE['user_id'];
			 $query = "insert into page_messages (page_id , user_id_from , user_id_to , message_content , message_time)".
			 "values ('1','$user_id','0','$message',now())";
			 mysql_query($query,$dbc);
	     }
	 }
	 if(isset($_POST['add_english_list']))
	 {
		 $list_name = $_POST['english_list_name'];
		 if($user_id>=0)
		 {
			 $query = "insert into english_play_list (user_id , list_name , list_use_time , list_create_time) ".
			 "values ('$user_id','$list_name',now(),now())";
			 mysql_query($query,$dbc);
		 }
	 }
	 if(isset($_POST['del_english_list']))
	 {
		 $list_id = $_POST['english_list_id'];
		 $query = "delete from english_list_item where list_id = '$list_id'";
		 mysql_query($query,$dbc);
		 $query = "delete from english_play_list where list_id = '$list_id'";
		 mysql_query($query,$dbc);
		 
	 }
	 if(isset($_POST['add_english_list_item']))
	 {
		 $list_id = $_POST['list_id'];
		 $list_item = $_POST['list_item'];
		 foreach( $list_item as $item)
		 {
			 $query = "select * from english_list_item where list_id = '$list_id' and source_id = '$item'";
			 $result = mysql_query($query,$dbc);
			 if(mysql_num_rows($result)==0)
			 {
				 $query = "insert into english_list_item (list_id , source_id ) "
				 ." values ('$list_id' , '$item')";
				 mysql_query($query,$dbc);
			 }
		 }

	 }
	 if(isset($_POST['del_english_list_item']))
	 {
		 $item_id = $_POST['item_id'];
		 $query = "delete from english_list_item where item_id = '$item_id'";
		 echo $query;
		 mysql_query($query,$dbc);
	 }
	 if((isset($_POST['change_user_city']))&&($user_id>0))
	 {
		 $city = $_POST['city'];
		 $query = "update user_info set user_city = '$city' where user_id = '$user_id' ";
		 mysql_query($query,$dbc);
	 }
	 if((isset($_POST['change_user_word']))&&($user_id>0))
	 {
		 $word = $_POST['word'];
		 $query = "update user_info set user_word = '$word' where user_id = '$user_id' ";
		 mysql_query($query,$dbc);
	 }
	 if((isset($_POST['set_portrait']))&&($user_id>0))
	 {
		 $path = ROOT_PATH.$_POST['path'] ;
		 $re1 = create_thumb($path,200);
		 unlink($path);
		 echo 'hello';
		 if($re1[0])
		 {
			 $new_name = 'portrait'.rand().rand().'.jpg';
			 rename($re1[1],ROOT_PATH.'img/portrait/'.$new_name);
			 $query = "insert into user_portrait (user_id , portrait_path ) values ('$user_id','$new_name')";
			 mysql_query($query,$dbc);
			 $query = "select * from user_portrait where user_id = '$user_id' order by portrait_id desc limit 1";
			 $result = mysql_query($query,$dbc);
			 if($row = mysql_fetch_array($result))
			 {
				 $portrait_id = $row['portrait_id'];
				 $query="update users set portrait_id = '$portrait_id' where user_id = '$user_id'";
				 mysql_query($query,$dbc);
			 }
			 
		   }
		 
	   }
	   
	   if((isset($_POST['del_album']))&&($user_id>0))
	   {
		   $album_id = $_POST['album_id'];
		   $query = "select * from photos where album_id = '$album_id' and user_id = '$user_id' limit 1";
		   $result = mysql_query($query,$dbc);
		   if($row = mysql_fetch_array($result))
		   {
			   $default_album_id = -1;
			   $photo_id = $row['photo_id'];
			   $query = "select * from albums where album_type = '1' and user_id = '$user_id'";
			   $result = mysql_query($query,$dbc);
			   if($row = mysql_fetch_array($result))
			   {
				   $default_album_id = $row['album_id'];
				   if($row['apbum_cover_id']==444)
				    {
						$query = "update albums set album_cover_id = '$photo_id' where album_id = '$default_album_id'";
			            mysql_query($query,$dbc);  	
					}
			   }
			   else
			   {
				   $query = "insert into albums (album_type , public_tag ,user_id , album_name , album_create_time )".
				   " values ('1','0','$user_id','未分类',now())";
				   mysql_query($query,$dbc);
				   $query = "select * from albums where album_type = '1' and user_id = '$user_id' limit 1";
				   $result = mysql_query($query,$dbc);
				   $row = mysql_fetch_array($result);
				   $default_album_id = $row['album_id'];
				   $query = "update albums set album_cover_id = '$photo_id' where album_id = '$default_album_id'";
			       mysql_query($query,$dbc);  				   
			   }
			   
			   $query = "update photos set album_id = '$default_album_id' where album_id = '$album_id' and user_id = '$user_id'"; 
			   mysql_query($query,$dbc);
			  
		   }
		   
		   $query = "delete from albums where album_id = '$album_id' and user_id = '$user_id'";
		   mysql_query($query,$dbc);
		   
	   }
	   if((isset($_POST['del_photo']))&&($user_id>0))
	   {
		   $photo_id = $_POST['photo_id'];
		   $query = "select albums.album_cover_id , albums.album_id , photos.photo_path , photos.photo_thumb_path , photos.photo_origin_path  from albums inner join photos using ( album_id ) where photos.photo_id = '$photo_id'";
		   $result = mysql_query($query);
		   if($row = mysql_fetch_array($result))
		   {
			   $cover_id = $row['album_cover_id'];
			   $album_id = $row['album_id'];
			   unlink(ROOT_PATH.$row['photo_path']);
			   unlink(ROOT_PATH.$row['photo_thumb_path']);
			   unlink(ROOT_PATH.$row['photo_origin_path']);
			   $query = "delete from photos where photo_id = '$photo_id'";
			   mysql_query($query); 
			   if($cover_id==$photo_id)
			   {
				   $query = "update albums set album_cover_id = '444' where album_id = '$album_id'";
				   mysql_query($query,$dbc);
				   set_album_cover($album_id,$dbc);
				   
			   }
			   
		   }
	   }
	   if((isset($_POST['move_to_album']))&&($user_id>0))
	   {
		   $photo_ids = $_POST['photo_ids'];
		   $album_id = $_POST['album_id'];
		   $query = "select * from albums where album_id = '$album_id'";
		   $result = mysql_query($query,$dbc);
		   if(($row = mysql_fetch_array($result))&&(count($photo_ids)>0))
		   {
			   $photo_id_test = $photo_ids[0];
			   $query = "select * from photos where photo_id = '$photo_id_test'";
			   $result = mysql_query($result);
			   $row = mysql_fetch_array($result);
			   $album_id_from = $row['album_id'];
			   foreach($photo_ids as $photo_id)
			   {
				   $query = "update photos set album_id = '$album_id' where photo_id = '$photo_id'";
				   mysql_query($query,$dbc);
			   }
			   set_album_cover($album_id_from,$dbc);
			   set_album_cover($album_id,$dbc);
		   }
		   
	   }
	      if(isset($_POST['add_standard_words']))
	   {
		   $collins_rate = $_POST['collins_rate'];
		   $words = $_POST['words'];
		   foreach($words as $word)
		   {
			  $query = "select * from english_words_lib where word_content = '$word'";
			  $result = mysql_query($query,$dbc);
			  if(mysql_num_rows($result)==0)
			  {
			     $query = "insert into english_words_lib (word_content,collins_rate) values ('$word','$collins_rate')";
			     mysql_query($query,$dbc);
			     echo "<p> $word </p>";
			  }
		   }
	   }
	   
	   
	   if(isset($_POST['add_sentances']))
	   {
		   $resource_id = $_POST['resource_id'];
		   $sentances = $_POST['sentances'];
		   foreach($sentances as $sentance)
		   {
			   $query = "insert into english_sentances (sentance_content , resource_id ) values ".
			   " ( '$sentance','$resource_id')";
			   mysql_query($query,$dbc) or die("error".mysql_error());
			   echo '<p>'.$sentance.'</p>';
		   }
	    }
		
		if(isset($_POST['invalid_word']))
	   {
		   $word_id = $_POST['word_id'];
		   $query = "update english_words_media set word_valid = '0' where word_id = '$word_id'";
		   mysql_query($query,$dbc);
	    }
		
		if(isset($_POST['set_word']))
	   {
		   $word_id = $_POST['word_id'];
		   $lib_id =   $_POST['lib_id'];
		   $query = "update english_words_media set lib_id  = '$lib_id' , word_checked = '1' where word_id = '$word_id'";
		   mysql_query($query,$dbc);
	    }
		
		
		if(isset($_POST['check_words']))
	   {
		   $words = $_POST['words'];
		   $material_content = $_POST['material_content'];
		   $query = "update english_material_analysis set material_content = '$material_content' where material_id = '7'";
		   mysql_query($query,$dbc);
		   echo '[{"word":"null"}';
		   foreach($words as $word)
		   {
			   $lib_id =  word_proto($word,$dbc);
			   if($lib_id > 0)
			   {
				   $query = "select * from english_words_lib where word_id = '$lib_id'";
				   $result = mysql_query($query,$dbc);
				   $row = mysql_fetch_array($result);
				   $collins_rate = $row['collins_rate'];
				   $word_status = $row['word_status'];
				   $reference_count = $row['reference_count'];
				   $temp = sqrt($reference_count);
					$rate = ($word_status > (6-$collins_rate))? $word_status:(6-$collins_rate);
				   echo ',{"word":"'.$word.'","word_proto":"'.$row['word_content'].'","lib_id":"'.$row['word_id'].'","collins_rate":"'.$row['collins_rate'].'","rate":"'.$rate.'"}';
			   }
			   else
			   {
				   echo ',{"word":"'.$word.'","word_proto":"","lib_id":"0","collins_rate":"0","rate":"0"}';
			   }
		   }
		   echo ']';
	    }
		
		
		if(isset($_POST['set_resource_audio']))
	   {
		   $resource_id = $_POST['resource_id'];
		   $file_name = trim($_POST['file_name']);
		   if(is_file('../audio/'.$file_name))
		   {
			   $query = "update english_resource set audio_name = '$file_name' where resource_id = '$resource_id'";
			   mysql_query($query,$dbc);
			   echo '{"status":"success","file":"'.$file_name.'" }';
		   }
		   else
		   {
			   echo '{"status":"error","file":""}';
		   }
		   
	   }
	   
	   if(isset($_POST['get_sentance_audio']))
	   {
		   $sentance_id = $_POST['sentance_id'];
		   get_sentance_audio($sentance_id,$dbc);
	   }
	   
	   if(isset($_POST['change_audio_stop']))
	   {
		   $sentance_id = $_POST['sentance_id'];
		   $num = $_POST['num'];
		   $query = "select audio_stop from english_sentances where sentance_id = '$sentance_id'";
		   $result = mysql_query($query,$dbc);
		   if($row = mysql_fetch_array($result))
		   {
			   $audio_stop_new = $row['audio_stop']+$num;
			   if($audio_stop_new < 0)
			         $audio_stop_new = 0;
					 
				$query = "update english_sentances set audio_stop = '$audio_stop_new' where sentance_id = '$sentance_id'";
				mysql_query($query,$dbc);
		   }
		   get_sentance_audio($sentance_id,$dbc);
	   }
	   
	   if(isset($_POST['change_audio_begin']))
	   {
		   $sentance_id = $_POST['sentance_id'];
		   $num = $_POST['num'];
		   $query = "select audio_begin,audio_stop from english_sentances where sentance_id = '$sentance_id'";
		   $result = mysql_query($query,$dbc);
		   if($row = mysql_fetch_array($result))
		   {
			   $audio_begin_new = $row['audio_begin']+$num;
			   $audio_stop_new = $row['audio_stop']+$num;
			   if($audio_begin_new < 0)
			         $audio_begin_new = 0;
					 
				$query = "update english_sentances set audio_begin = '$audio_begin_new' where sentance_id = '$sentance_id'";
				mysql_query($query,$dbc);
				$query = "update english_sentances set audio_stop = '$audio_stop_new' where sentance_id = '$sentance_id'";
				mysql_query($query,$dbc);
		   }
		   get_sentance_audio($sentance_id,$dbc);
	   }
		
		
		
		if(isset($_POST['confirm_sentance']))
	   {
		   $sentance_id = $_POST['sentance_id'];
		   $query = "update english_sentances set audio_checked = '1' where sentance_id = '$sentance_id'";
		   mysql_query($query,$dbc);
	    }
		
		if(isset($_POST['auto_change_audio']))
	   {
		   $sentance_id = $_POST['sentance_id'];
		   $query = "select english_sentances.audio_checked,english_sentances.sentance_index, english_resource.resource_id, ".
		   " english_resource.audio_length from english_sentances ".
		   " inner join english_resource on (english_sentances.resource_id = english_resource.resource_id ) " .
		   " where english_sentances.sentance_id = '$sentance_id'";
		   $result = mysql_query($query,$dbc);
		   if(($row = mysql_fetch_array($result))&&($row['audio_checked']==0))
		   {
			   $resource_id = $row['resource_id'];
			   $audio_length = $row['audio_length'];
			   $sentance_index = $row['sentance_index'];
			   $query = "select sentance_index,audio_begin,audio_stop,audio_checked,word_count from english_sentances ".
			             "  where resource_id = '$resource_id' order by sentance_index ";
			   $result = mysql_query($query,$dbc);
			   $ids = array();
			   $begin = array();
			   $stop = array();
			   $checked = array();
			   $num = array();
			   $checked[0]=1;
			   $stop[0]=0;
			   $count = 1;
			   $target = 1;
			   while($row = mysql_fetch_array($result))
			   {
				   $ids[$count]=$row['sentance_index'];
				   $begin[$count]=$row['audio_begin'];
				   $stop[$count]=$row['audio_stop'];
				   $num[$count] = $row['word_count'];
				   $checked[$count]=$row['audio_checked'];
				   if($row['sentance_index']==$sentance_index)
				   {
					   $target =  $count;
					   $min = $count;
					   $max = $count;
					   
				   }
				   $count ++;
			   }
			   $checked[$count]=1;
			   $begin[$count]=$audio_length;
			   while($checked[$min]==0)
			   		$min --;
			   while($checked[$max]==0)
			   		$max ++;
			   $total_word_num = 0;
			   for($i = $min+1;$i<$max;$i++)
			   		$total_word_num += $num[$i];
				$skip_word_num =0;
				for($i=$min+1;$i<$target;$i++)
					$skip_word_num += $num[$i];
				$total_audio_length = $begin[$max]-$stop[$min];
				$begin_base = $stop[$min];
				$audio_begin = $begin_base + $total_audio_length*$skip_word_num/$total_word_num;
				$audio_stop = $audio_begin + $total_audio_length*$num[$target]/$total_word_num;
				$audio_begin=(int)$audio_begin;
				$audio_stop = (int)$audio_stop;
				$query = "update english_sentances set audio_begin = '$audio_begin' where sentance_id = '$sentance_id'";
				mysql_query($query,$dbc);
				$query = "update english_sentances set audio_stop = '$audio_stop' where sentance_id = '$sentance_id'";
				mysql_query($query,$dbc);
			   
		   }
		   get_sentance_audio($sentance_id,$dbc);
	    }
	   if(isset($_POST['prepare_resource']))
	   {
		   
		   $resource_id = $_POST['resource_id'];
		   $shell_file = '../temp_file/split.sh';
		   $shell_command = "";
		   $query = "select resource_alias,audio_name from english_resource where resource_id = '$resource_id'";
		   $result = mysql_query($query,$dbc);
		   if($row = mysql_fetch_array($result)){
			   $resource_name = $row['resource_alias'];
			   $audio_name = $row['audio_name'];
			   $audio_name = str_replace('(','\(',$audio_name);
			   $audio_name = str_replace(')','\)',$audio_name);
			   $audio_name = str_replace(' ','\\ ',$audio_name);
			   
			   $file_name = '/var/www/html/temp_file/'.$resource_name.'.txt';
			   $file_content = "";
			   $shell_command ="cd /var/www/html/temp_file"."\n";
			   $shell_command .= "cp ../audio/".$audio_name.' '.$resource_name.'.mp3'."\n";
			   $query = "select sentance_content,sentance_index, audio_begin,audio_stop". 
			            " from english_sentances where resource_id = '$resource_id' ".
			            "  order by sentance_index";
			   $result = mysql_query($query,$dbc);
			   $sentance_audio_names = array();
			   while($row = mysql_fetch_array($result))
			   {
				   $file_content .= $row['sentance_index'].". ";
				   $file_content .= $row['sentance_content']."\r\n";
				   $audio_begin = $row['audio_begin'];
				   $audio_stop  = $row['audio_stop'];
				   $index = $row['sentance_index'];
				   $begin_minute = (int)($audio_begin/60);
				   $begin_second = $audio_begin%60;
				   $stop_minute = (int)($audio_stop/60);
				   $stop_second = $audio_stop%60;
				   $sentance_audio_name = $resource_name.'No.'.$index;
				   $shell_command .= "mp3splt ../audio/".$audio_name.' '.$begin_minute.'.'.$begin_second.' '.$stop_minute.
				   '.'.$stop_second.' -o  ../temp_file/'.$sentance_audio_name."\n";
			   } 
			   $shell_command .= "zip -q Resource_".$resource_name.'.zip '.$resource_name."* \n";
			   $shell_command .= 'rm '.$resource_name.'*';
			   file_put_contents($file_name,$file_content);
			   
			   $numbytes = file_put_contents($shell_file,$shell_command); 
			   exec("chmod 777 /var/www/html/temp_file/split.sh");
			   exec("/var/www/html/temp_file/split.sh");
			   
			   
		    }
		  
	    }


		
		if(isset($_POST['pgee']))
	   {
		   $words = $_POST['words'];
		   foreach ($words as $word){
			   $query = "update english_words_lib set pgee_check = '1' where word_content = '$word'";
			   mysql_query($query,$dbc);
			   
		   }
		   
	    }
		
		if(isset($_POST['english_analysis_material']))
	   {
		   $material_id = $_POST['material_id'];
		   $content = $_POST['content'];
		   $query = "update english_material_analysis set material_content = '$content' where material_id = '$material_id'";
		   mysql_query($query,$dbc);
	    }
		

		if(isset($_POST['init_resource'])){
			$resource_id = $_POST['resource_id'];
			$query = "select file_name, resource_type, initialize_check from english_resource where resource_id = '$resource_id'";
			$result = mysql_query($query,$dbc);
			if(($row = mysql_fetch_array($result)) && $row['initialize_check'] == 0 ){
				$file_name = $row['file_name'];
				$resource_type = $row['resource_type'];
				$query = "select sentance_id from english_sentances where resource_id = '$resource_id'";
				$result = mysql_query($query,$dbc);
				while($row = mysql_fetch_array($result)){
					$sentance_id = $row['sentance_id'];
					$query = "delete from sentance_has_words where sentance_id = '$sentance_id'";
					mysql_query($query);
				}
				$query = "delete from english_sentances where resource_id = '$resource_id'";
				mysql_query($query);
				$file_path = '../files/'.$file_name;
				$fp = fopen($file_path,'r');
				$content = fread($fp,filesize($file_path));
				$content = trim_content($content,$resource_type);
                //$content = pre_edit($content);
                $content = str_replace("'","\'",$content);
				if($resource_type == 1 || $resource_type == 4 || $resource_type == 6 || $resource_type == 7 || $resource_type == 8 ){
					$sentances = get_sentances($content);
					//print_r($sentances);
				}else if($resource_type == 3){
					$sentances = get_lines($content);
					//print_r($sentances);
				}else if($resource_type == 9||$resource_type == 10){
					$subtitles = get_subtitles($content);
					$len = count($subtitles);
					for($i=0;$i<$len;$i++){
						$index = $i + 1;
						$sentance = $subtitles[$i]['sentance'];
						$start = $subtitles[$i]['start'];
						$end = $subtitles[$i]['end'];
						$query = "insert into english_sentances (sentance_index, sentance_content, resource_id, audio_begin,audio_stop)".
						         " values ('$index','$sentance','$resource_id','$start','$end') ";
						//echo $query;
						mysql_query($query);
					}
					//print_r($subtitles);
				}
               

				$len = count($sentances);
				for($i=0;$i<$len;$i++){
					$index = $i + 1;
					$sentance = $sentances[$i];
					$query = "insert into english_sentances (sentance_index, sentance_content, resource_id )".
					         " values ('$index','$sentance','$resource_id') ";
					mysql_query($query);
				}

				$query = "select sentance_id, sentance_content from english_sentances where resource_id = '$resource_id'";
				$result = mysql_query($query,$dbc);
				while($row = mysql_fetch_array($result)){
					$sentance = $row['sentance_content'];
					$sentance = strtolower($sentance);
					$sentance_id = $row['sentance_id'];
					$pattern = "/[a-z]+/i";
					preg_match_all($pattern, $sentance, $arry);
					foreach ($arry[0] as $word) {
						$id = quick_proto($word,$dbc);
						$query = "select rel_id from sentance_has_words where word_content = '$word' and lib_id = '$id' and sentance_id = '$sentance_id'";
						$r2 = mysql_query($query,$dbc);
						if(mysql_num_rows($r2)==0){
							$query = "insert into sentance_has_words (word_content, lib_id , sentance_id ) ".
									 " values ('$word','$id','$sentance_id') ";
							mysql_query($query);
						}
					}
					
				}



				$query = "select sentance_id from english_sentances where resource_id = '$resource_id' ";
				$result = mysql_query($query,$dbc);
				while($row = mysql_fetch_array($result)){
					$sentance_id = $row['sentance_id'];
					$query = "select rel_id from sentance_has_words where sentance_id = '$sentance_id'";
					$r2 = mysql_query($query,$dbc);
					$num = mysql_num_rows($r2);
					$query = "update english_sentances set word_count = '$num' where sentance_id = '$sentance_id'";
					mysql_query($query);
				}

				$query = "select rel_id from sentance_has_words inner join english_sentances on ".
					     " sentance_has_words.sentance_id = english_sentances.sentance_id ".
					     " where english_sentances.resource_id = '$resource_id' ".
					     " group by sentance_has_words.lib_id ";
				$result = mysql_query($query,$dbc);
				$num = mysql_num_rows($result);
				$query = "update english_resource set word_count = '$num', initialize_check = 1 where resource_id = '$resource_id'";
				mysql_query($query,$dbc);
			}
			
		}

		if(isset($_POST['love_sentance'])){
			$type = $_POST['search_type'];
			$id = $_POST['search_id'];
			$operation = $_POST['operation'];
			if($type == 1 ){
				$query = "update sentance_has_words set love_tag = '$operation' where rel_id = '$id'";
				mysql_query($query,$dbc);
			}

			if($type == 2){
				$query = "update sentance_has_expressions set love_tag = '$operation' where rel_id = '$id'";
				//echo $query;
				mysql_query($query,$dbc);
			}
		}

		if(isset($_POST['add_expression_to_sentance'])){
			$sentance_id = $_POST['sentance_id'];
			$expression_id = $_POST['expression_id'];
			$query = "select * from sentance_has_expressions where sentance_id = '$sentance_id' and expression_id = '$expression_id'";
			$result = mysql_query($query,$dbc);
			if(mysql_num_rows($result)==0){
				$query = "insert into sentance_has_expressions (sentance_id , expression_id ) values ('$sentance_id','$expression_id')";
				mysql_query($query,$dbc);
			}

			$query = "select rel_id from sentance_has_expressions where sentance_id = '$sentance_id' and expression_id = '$expression_id'";
			$result = mysql_query($query,$dbc);
			$row = mysql_fetch_array($result);
			$rel_id = $row['rel_id'];
			echo '{"rel_id" : "'.$rel_id.'"}';
		}

		if(isset($_POST['merge_sentances']))
	 	{
		 	$first_id = $_POST['first_id'];
		 	$last_id = $_POST['last_id'];
		 	$resource_id =0;
		 	$merge_to_id =0;
		 	$index_min = 0;
		 	$index_max = 0;
		 	$query = "select resource_id , sentance_index from english_sentances where sentance_id = '$first_id'";
		 	$result = mysql_query($query,$dbc);
		 	$row = mysql_fetch_array($result);
		 	$resource_id = $row['resource_id'];
		 	$merge_to_id = $first_id;
		 	$index_min = $row['sentance_index'];
		 	$index_max = $row['sentance_index'];
		 	$query = "select sentance_index from english_sentances where sentance_id = '$last_id'";
		 	$result = mysql_query($query,$dbc);
		 	$row = mysql_fetch_array($result);
		 	if($row['sentance_index']< $index_min){
		 		$index_min = $row['sentance_index'];
		 		$merge_to_id = $last_id;
		 	}else{
		 		$index_max = $row['sentance_index'];
		 	}
		 	$sentance ='';
		 	$query = "select sentance_id,sentance_content from english_sentances ".
		 	         " where resource_id = '$resource_id' and sentance_index >= '$index_min' ".
		 	         "and sentance_index <= '$index_max' order by sentance_index";
		 	$result = mysql_query($query,$dbc);
		 	$first = true;
		 	while($row = mysql_fetch_array($result)){
		 		if($first){
		 			$first = false;
		 		}else{
		 			$sentance_id = $row['sentance_id'];
		 			$query = "delete from english_sentances where sentance_id = '$sentance_id'";
		 			mysql_query($query,$dbc);
		 			//echo $query."\n";
		 			$query = "select * from sentance_has_words where sentance_id = '$sentance_id'";
		 			mysql_query($query,$dbc);
		 			//echo $query."\n";
		 			$r2 = mysql_query($query,$dbc);
		 			while($row2=mysql_fetch_array($r2)){
		 				$lib_id = $row2['lib_id'];
		 				$word_content = $row2['word_content'];
		 				$query = "select rel_id from sentance_has_words where sentance_id = '$merge_to_id' and word_content = '$word_content'";
		 				mysql_query($query,$dbc);
		 				$r3 = mysql_query($query,$dbc);
		 				if(mysql_num_rows($r3)==0){
		 					$query = "insert into sentance_has_words (sentance_id,lib_id, word_content) values ('$merge_to_id','$lib_id','$word_content')";
		 					mysql_query($query,$dbc);
		 					//echo $query."\n";
		 				}
		 			}
		 			$query = "delete from sentance_has_words where sentance_id = '$sentance_id'";
		 			mysql_query($query,$dbc);
		 			//echo $query."\n";
		 		}
		 		$sentance .= ' '. $row['sentance_content'];

		 	}
		 	$sentance = preg_replace("/[ ]+/"," ",$sentance);
		 	echo $sentance;
		 	$sentance = preg_replace("/'/","\'",$sentance);
		 	$query = "select rel_id from sentance_has_words where sentance_id = '$merge_to_id'";
		 	$result = mysql_query($query,$dbc);
		 	$num = mysql_num_rows($result);
		 	$query = "update english_sentances set sentance_content = '$sentance',word_count = '$num',audio_checked = '0' where sentance_id = '$merge_to_id'";
		 	mysql_query($query,$dbc);
		 	

	 	}
		
	   
	   
?>



<?php
   mysql_close($dbc);
?>

<?php

	function trim_content($content,$resource_type){
		$content = preg_replace('/[^(\x0A-\x7F)]*/',"", $content);
		$content = preg_replace("/\[[^\]]+\]/",' ',$content);
		$content = preg_replace("/\([^\)]+\)/",' ',$content);
		$content = preg_replace("/<[^>]+>/",' ',$content);
		if($resource_type != 9 && $resource_type!=10)
			$content = preg_replace("/\n[^a-zA-Z]+\n/","\n",$content);
		$content = str_replace("..."," ",$content);
		$content = str_replace("Mr.","Mr",$content);
		$content = str_replace("Dr.","Dr",$content);
		$content = preg_replace('/ +/',' ',$content);
		//echo $content;
		return $content;
	}

    function pre_edit($content)
    {
        $str = '';
        $start = 0;
        $max = strlen($content);
        while($start < $max){
            $line = get_a_line($content,$start,$max);
            if(preg_match("/^[A-Z0-9,\\' ]+$/", $line)){
                $str .= "\n".$line.':';
            }else $str .= ' '.$line;
        }
        return $str;

    }
	function get_subtitles(&$str){
		
		$lines = array();
		$len = strlen($str);
		$m = 0;
		while($m<$len){
			$line = get_a_line($str,$m,$len);
			if(strlen($line)>0)
				$lines[] = $line;
		}
		$state = 0;//0，初始状态，1，发现标号，2，发现时间，3，整理台词
		$subtitles = array();
		$subtitle = array();
		$len = count($lines);
		for($i=0;$i<$len;$i++){
			if(is_numeric($lines[$i])){
				if($state == 3)  $subtitles[] = $subtitle;
				$subtitle = array();
				$state= 1;
				continue;
			}

			if($state == 1){
				$matches = [];
				preg_match_all('/[0-9]+/',$lines[$i],$matches);
				if(count($matches[0])==8){
					$subtitle['start'] = $matches[0][0]*3600+$matches[0][1]*60+$matches[0][2];
					$subtitle['end'] = $matches[0][4]*3600+$matches[0][5]*60+$matches[0][6];
					$state = 2;
					//print_r($subtitle);
				}
				else{
					$subtitle = array();
					$state = 0;
				} 
				continue;
				
			}

			if($state == 2 || $state == 3){
				$subtitle['sentance'] .= ' '.$lines[$i];
				$state = 3;
			}
		}
		//print_r($subtitles);
		return $subtitles;
	}


	function get_a_line(&$str, &$start,$max){
        while($start<$max&&is_line_end($str[$start])) $start ++;
		$end = $start;
		while($end <$max && !is_line_end($str[$end])) $end ++;
		$line = substr($str, $start,$end-$start);
		$start = $end + 1;
		return $line;
	}

	function get_lines($str){
		$start = false;
		$find_colon = false;
		$sentances = array();
		$sentance = '';
		$len = strlen($str);
		for($i=0;$i<$len;$i++){
			$char = $str[$i];
			if($str[$i] == ':') $find_colon = true;
			if($start){
				if($char != "\r" && $char != "\n"){
					$sentance .= $str[$i];
				}else{
					if($find_colon){
						$sentance .= $str[$i];
						$sentances[] = $sentance;
						$sentance = '';
						$start = false;
					}
				}
			}else{
				if(!is_capital($str[$i])) continue;
				else {
					$start = true;
					$sentance = $str[$i];
					$find_colon = false;
				}
			}
		}
		if($sentance)
		     $sentances[] = $sentance;
		return $sentances;

	}
	function get_sentances($str){
		$start = false;
		$in_space = false;
		$sentances = array();
		$len = strlen($str);
		for($i=0;$i < $len; $i++){
			if($str[$i]>=128){
				if(!$in_space && $start){
					$sentance .= ' ';
					$in_space = true;
				}
				continue;
			}
			if($start){
				if(is_end($str[$i])){
					$sentance .= $str[$i];
					$sentances[] = $sentance;
					$sentance = "";
					$start = false;
					$in_space = false;
					continue;
				}

				if(is_space($str[$i])|| is_line_end($str[$i])){
					if($in_space) continue;
					else $in_space = true;
				}
				else{
					$in_space = false;
				}
				if(is_line_end($str[$i]))
					$sentance .= ' ';
				else
					$sentance .= $str[$i];


			}else{
				if(!is_capital($str[$i])) continue;
				else {
					$start = true;
					$sentance .= $str[$i];
				}
			}
		}
		if($sentance)
		     $sentances[] = $sentance;
		return $sentances;
	}
	function is_line_end($char){
		if($char=="\n"|| $char == "\r"|| $char == "\r\n" || $char == "\n\r"  )
			return true;
		else
			return false;
	}
	function is_space($char){
		if($char==' '|| $char == "\t" )
			return true;
		else
			return false;
	}

	function is_end($char){
		if($char=='.'|| $char == "?" || $char == "!")
			return true;
		else
			return false;
	}
	function is_capital($char){
		if($char>='A'&& $char <= 'Z')
			return true;
		else 
			return false;
	}

    function get_sentance_audio($sentance_id , $dbc )
	{
		   $query = "select english_resource.audio_name,english_sentances.audio_begin,english_sentances.audio_stop,english_sentances.sentance_audio_name ".
		   "  from english_sentances inner join english_resource ". 
		   "   on ( english_sentances.resource_id = english_resource.resource_id ) ".
		   "   where english_sentances.sentance_id = '$sentance_id'";
		   $result = mysql_query($query,$dbc);
		   if($row = mysql_fetch_array($result))
		   {
			   $audio_path = 'audio/'.$row['audio_name'];
			   $audio_start = $row['audio_begin'];
			   $audio_end = $row['audio_stop'];
			   $audio_name = '';
			   if($row['sentance_audio_name'])
			   		$audio_name = 'AF/audio/english_sentances/'.$row['sentance_audio_name'];
			   echo '{"audio_path":"'.$audio_path.'","audio_start":"'.$audio_start.'","audio_end":"'.$audio_end.'","audio_name":"'.$audio_name.'"}';
		   }
	}
	
	function quick_proto($word,$dbc){
		$word = strtolower($word);
	 	if(($id = check_proto($word,$dbc))>0) return $id;
	 	$query = "select lib_id from english_words where lower(word_content) = '$word' and lib_id != 0";
	 	$result = mysql_query($query,$dbc);
	 	if($row = mysql_fetch_array($result)){
	 		return $row['lib_id'];
	 	}
	 	else {
	 		$id = word_proto($word,$dbc);
	 		if($id > 0){
	 			$query = "insert into english_words ( word_content, lib_id) values ('$word','$id')";
	 			mysql_query($query,$dbc);
	 		}
	 		return $id;
	 	}
	 }


	 function word_proto($word,$dbc){
	 	$word = strtolower($word);
	 	if(($id = check_proto($word,$dbc))>0) return $id;

	 	$query = "select lib_id from english_words where lower(word_content) = '$word' and lib_id <> 0";
	 	$result = mysql_query($query,$dbc);
	 	if($row = mysql_fetch_array($result)){
	 		return $row['lib_id'];
	 	}
	 	$id = 0;
	 	$len = strlen($word);
	 	if($len>2 && $word[$len-1] == 's'){
	 		$temp = substr($word, 0, $len-1);
	 		if(($id = check_proto($temp,$dbc))>0) return $id;
	 		else if($word[$len-2] == 'e'){
	 			$temp = substr($word, 0, $len-2);
	 			if(($id = check_proto($temp,$dbc))>0) return $id;
	 			else if($word[$len -3] == 'i'){
	 				$temp = substr($word, 0, $len-3);
	 				$temp .= 'y';
	 				if(($id = check_proto($temp,$dbc))>0) return $id;
	 		    }
	 		}
	 	}
	 	else if($len > 2 && $word[$len -1] == 'd'){
	 		$temp = substr($word, 0, $len-1);
	 		if(($id = check_proto($temp,$dbc))>0) return $id;
	 		else if($word[$len -2] == 'e'){
	 			$temp = substr($word, 0, $len-2);
	 			if(($id = check_proto($temp,$dbc))>0) return $id;
	 			else if($word[$len -3] == 'i'){
	 				$temp = substr($word, 0, $len-3);
	 				$temp .= 'y';
	 				if(($id = check_proto($temp,$dbc))>0) return $id;
	 		    }
	 		}
	 	}
	 	else if($len > 4 && $word[$len-3]== 'i' && $word[$len-2] == 'n' && $word[$len-1] == 'g'){
	 		$temp = substr($word, 0, $len-3);
	 		if(($id = check_proto($temp,$dbc))>0) return $id;
	 		else if(($id = check_proto($temp . 'e',$dbc))>0) return $id;
	 		else if($word[$len-4] == 'y'){
	 			$temp = substr($word, 0,$len -4);
	 			$temp .= 'ie';
	 			if(($id = check_proto($temp,$dbc))>0) return $id;
	 		}
	 	}
	 	else if($len >2 && $word[$len -2] == 'l' && $word[$len-1] == 'y'){
	  	$temp = substr($word, 0, $len-2);
	  	if(($id = check_proto($temp,$dbc))>0) return $id;
	    }

	  	return $id;
	}

	function check_proto($word,$dbc){
	 	$word = strtolower($word);
        $query = "select word_id from english_words_lib where lower(word_content) = '$word'";
	 	$result = mysql_query($query,$dbc);
	 	if($row = mysql_fetch_array($result)){
	 		return $row['word_id'];
	 	}
	 	else return 0;
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
			 else
			 {
				 $cover_id = $row2['album_cover_id'];
				 $query = "select * from photos where photo_id = '$cover_id' and album_id = '$album_id'";
				 $result = $mysql_query($query,$dbc);
				 if(mysql_num_rows($result)==0) 
				 {
					 $query = "update albums set album_cover_id = '$photo_id' where album_id = '$album_id'";
				     mysql_query($query,$dbc);
				 }
			 }
		 }
		 else
		 {
			 $query = "update albums set album_cover_id = '444' where album_id = '$album_id'";
			 mysql_query($query,$dbc);
		 }
	 }
   function build_mistake_query($mistake)
   {
	   switch($mistake['mistake_type'])
	   {
		   case 0 :
		       $query = "select * from english_mistakes where mistake_type = 0".
						" and mistake_origin = '".$mistake['mistake_origin'].
						"' and mistake_content = '".$mistake['mistake_content']."'";
			    break;
		    case 1 :
			    $query = "select * from english_mistakes where mistake_type = 1".
						" and mistake_content = '".$mistake['mistake_content']."'";
			    break;
			case 2 :
			     $query = "select * from english_mistakes where mistake_type = 2".
						" and mistake_origin = '".$mistake['mistake_origin']."'";
			     break;
	    }
		return $query;
	   
    }
	
   
    function add_mistake_log($mistake_id , $sentance_id,$dbc)
	{
		      $date_now = get_date();
			  $query ="select * from english_mistakes_log where mistake_id = '$mistake_id'".
			  " and sentance_id = '$sentance_id' and log_date = '$date_now' ";
			  $result = mysql_query($query,$dbc);
			  if(mysql_num_rows($result)==0)
			  {
					$query = "insert into english_mistakes_log ( mistake_id , sentance_id , log_date)".
					" values('$mistake_id','$sentance_id',now())";
					mysql_query($query,$dbc);
			  }
	}
	function add_mistake($mistake,$dbc)
	{
		$mistake_type = $mistake['mistake_type'];
		$mistake_origin = $mistake['mistake_origin'];
		$mistake_content= $mistake['mistake_content'];
		$query = build_mistake_query($mistake);
		$result = mysql_query($query,$dbc);
		if($row=mysql_fetch_array($result))
		{
			return $row['mistake_id'];
			
			
		}
		else
		{
			$query = "insert into english_mistakes (mistake_type , mistake_origin , mistake_content)".
			" values ('$mistake_type','$mistake_origin','$mistake_content')";
			mysql_query($query,$dbc);
			$query = build_mistake_query($mistake);
		    $result = mysql_query($query,$dbc);
			$row=mysql_fetch_array($result);
			return $row['mistake_id'];
			
		}
						
	}
      
?>