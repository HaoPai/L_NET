<?php require_once('../struct_php/global.php'); ?>
<?php
  
	  $query = "";
	  if(isset($_GET['target']))
	  {
		    
		    $target= $_GET['target'];
		    switch($target)
			{
				case 'sentances':
				{
					if(isset($_GET['resourceIds']))
					{
						$resource_ids = $_GET['resourceIds'];
						$where_list = array();
						foreach($resource_ids as $resource_id)
						{
							$where_list[] = "  resource_id = '$resource_id' ";
						}
						$where_clause = implode('or',$where_list);
						$query = "select * from english_sentances where".$where_clause."order by sentance_id ";
					}
					else
					{
						$query = "select * from english_sentances  order by sentance_id ";
					}
					
				}
				break;
				case 'query_word':
				{
				   $word = $_GET['word'];
				   if($word)
				   {
					   $query = "select * from english_words where word_content like '$word%'  ";
					   $result = mysql_query($query,$dbc);
					   if($row = mysql_fetch_array($result))
					   {
						     
							 if($user_id>0)
							 {
								 $query = "insert into english_query (user_id , query_content , query_time) ".
								 " values ('$user_id','$word',now())";
							 }
							 else
							 {
								 $query = "insert into english_query ( query_content , query_time) ".
								 " values ('$word',now())";
								  
							 }
							 mysql_query($query,$dbc);
					    }
						
						
					   
				   }
				   
				   $like = "sentance_content like '% $word%' ";
				   $query = "select * from english_sentances where " .$like;
				 } 
				break;
				case 'user_info':
				    if(isset($_COOKIE['user_id']))
					{
						$user_id = $_COOKIE['user_id'];
						$query = "select user_id , user_name , portrait_id  from users where user_id = '$user_id'  ";
					}
				break;
				case 'manager_messages':
				     $query = "select * from page_messages inner join users ".
					 "on ( page_messages.user_id_from = users.user_id )".
					 "inner join user_portrait on (users.portrait_id = user_portrait.portrait_id )".
					 " where page_id = 1 order by message_id desc ";
				break;
				case 'set_portrait_id':
				     if(isset($_COOKIE['user_id'])&&isset($_GET['portrait_id']))
					 {
						 $user_id = $_COOKIE['user_id'];
						 $portrait_id = $_GET['portrait_id'];
				         $query = "update users set portrait_id = '$portrait_id' where user_id = '$user_id'  ";
						 mysql_query($query,$dbc);
						 $query ="";
					 }
				break;
				case 'delete_message':
				     if(isset($_COOKIE['user_id'])&&isset($_GET['id']))
					 {
						 $user_id = $_COOKIE['user_id'];
						 $id = $_GET['id'];
				         $query = "delete from page_messages where message_id = '$id' and user_id_from = '$user_id'";
						 mysql_query($query,$dbc);
						 $query ="";
					 }
				break;
				case 'english_list':
				      if($user_id>0)
					  {
							$query = "select * from english_play_list where user_id = '$user_id' and list_type = '1'";
							$result = mysql_query($query,$dbc);
							if(mysql_num_rows($result) <=0 )
							{
								$query = "insert into english_play_list ".
								" (user_id , list_name , list_use_time , list_create_time , list_type) ".
								" values ('$user_id','临时列表',now(),now(),'1')"; 
								mysql_query($query,$dbc);
							}
							$query = "select * from english_play_list where user_id = '$user_id' order by list_type";
					  }
					  else
					  $query="";
				break;
				case 'english_list_items':
				      if(isset($_GET['para']))
					  {
						 $list_id = $_GET['para'];
				         $query = "select * from english_list_item inner join english_resource  ".
									" on (english_list_item.source_id = english_resource.resource_id ) ".
									" where english_list_item.list_id = '$list_id' order by resource_name ;";
					  }
				break;
				case 'list_sentances':
				      if(isset($_GET['para']))
					  {
						 $list_id = $_GET['para'];
						 $query = "select * from english_list_item inner join english_resource ".
						 " on (english_list_item.source_id = english_resource.resource_id ) ".
						 " where list_id = '$list_id' order by resource_name ";
						 $result = mysql_query($query,$dbc);
						 $sentances = array();
						 while($row = mysql_fetch_array($result))
						 {
							 $source_id = $row['source_id'];
							 $query = "select * from english_sentances where resource_id = '$source_id' order by sentance_id ";
							 $result2 = mysql_query($query,$dbc);
							 while($row2 = mysql_fetch_array($result2))
							 {
								 $sentances[] = $row2 ;
							 }
						 }
						 echo json_encode($sentances);
						 $query="";

					  }
				break;
				case 'hint_english_query':
				      if(isset($_GET['para']))
					  {
						  $hints = array();
						  $pre = $_GET['para'];
						  while( ($pre)  && (count($hints)<5) )
						  {
							    $new = get_query_hint_strict( $pre,$dbc ) ;
								while(count($new)>0)
								{
									$item = array_shift($new);
									if(!in_array($item,$hints))
									{
										$hints[]= $item;
									 }
								 }
								 $pre = substr($pre,0,strlen($pre)-1); 
						    }
							if(count($hints)<5)
							{
								if($user_id>0)
								{
									$new = get_query_hint_user($user_id , $dbc );
									while(count($new)>0)
									{
										$item = array_shift($new);
										if(!in_array($item,$hints))
										{
											$hints[]= $item;
										 }
									 }
									 if(count($hints)<5)
									 {
										  $new = get_query_hint($dbc);
										  while(count($new)>0)
										  {
											  $item = array_shift($new);
											  if(!in_array($item,$hints))
											  {
												  $hints[]= $item;
											   }
										    }
									    }
								    }
									else
									{
										  $new = get_query_hint($dbc);
										  while(count($new)>0)
										  {
											  $item = array_shift($new);
											  if(!in_array($item,$hints))
											  {
												  $hints[]= $item;
											   }
										    }
									 }
							 }
						
						     echo json_encode($hints);
					  }
					  $query= "";
				break;
				case 'english_resources':
				       $query = "select * from english_resource order by resource_name";
				break;
				case 'get_photo':
				      if(isset($_GET['photo_ids']))
					  {
						  $photo_ids = $_GET['photo_ids'];
						  $parts = array();
						  foreach($photo_ids as $photo_id)
						  {
							  $parts[] = "photo_id = '$photo_id'";
						  }
						  $where_clause = " where " . implode(' or ',$parts);
						  $query = "select photo_id , photo_path from photos " . $where_clause;
					  }
				break;
				
				case 'check_word':
				     if(isset($_GET['word']))
					 {
						 $word = $_GET['word'];
						 $query = "select * from english_words_lib where word_content = '$word'";
					 }
				break;
				
				case 'material_words':
					if(isset($_GET['material_id'])&& isset($_GET['type'])){
						$material_id = $_GET['material_id'];
						$type = $_GET['type'];
						switch($type){
							case 'pgee':
								$and_clause = "and english_words_lib.pgee_check = 1";
							break;
							case 'high':
								$and_clause = "and english_words_lib.avg_rate > 5";
							break;
							case 'middle':
								$and_clause = "and english_words_lib.avg_rate =5 or english_words_lib.avg_rate = 4";
							break;
							case 'low':
								$and_clause = "and english_words_lib.avg_rate < 4";
							break;
							case 'all':
								$and_clasue = "";
							break;
						}
						$query = "select english_words_lib.word_content, english_words_lib.avg_rate,english_words_lib.nce_count ,".
						" english_words_lib.pgee_check  ".
						"from material_has_words ".
						" inner join english_words_lib on material_has_words.lib_id = english_words_lib.word_id ".
						" where material_has_words.material_id = '$material_id'".$and_clause.
						"   group by english_words_lib.word_id order by english_words_lib.avg_rate desc";
					}
				break;
				
				
			}
			
			
			if($query)
			{
				 
				  $result=mysql_query($query,$dbc);
				  $query_results = array();
				  while($result&&($row=mysql_fetch_array($result)))
				  {
					 $query_results[] = $row;
				  }
				  header('content-type:application/json;charset=UTF-8');
				  if(count($query_results)>=1)
				  {
				       echo json_encode($query_results);
				  }
				  else
				  {
					  echo '[]';
				  }
			 }
			
	  }
	  
	 
	  
	  
	 
?>
<?php

	   function get_query_hint_user($user_id , $dbc )
	   {
		   $hints = array();
		   $query = "select * from english_query  where user_id = '$user_id'  order by query_time desc  ";
		   $result = mysql_query($query,$dbc);
		   while(($row = mysql_fetch_array($result))&&( count($hints) < 5 ))
		   {
			   $temp = $row['query_content'];
			  if(in_array($temp,$hints))
			  {
				  continue;
			  }
			  else
			  {
				  $hints[]= $temp;
			  }
		   }
		   return $hints;
	   }
	   function get_query_hint_strict($words  ,$dbc)
	   {
		   $hints = array();
		   $query = "select * from english_query  where query_content like '$words%'  order by query_time desc";
		   $result = mysql_query($query,$dbc);
		   while(($row = mysql_fetch_array($result))&&( count($hints) < 5 ))
		   {
				   $temp = $row['query_content'];
					if(in_array($temp,$hints))
					{
						continue;
					}
					else
					{
						$hints[]= $temp;
					}
		   }
		   return $hints ;
	   }
	   function get_query_hint($dbc)
	   {
		   $hints = array();
		   $query = "select * from english_query   order by query_time desc";
		   $result = mysql_query($query,$dbc);
		   while(($row = mysql_fetch_array($result))&&( count($hints) < 5 ))
		   {
				   $temp = $row['query_content'];
					if(in_array($temp,$hints))
					{
						continue;
					}
					else
					{
						$hints[]= $temp;
					}
		   }
		   return $hints ;
		   
	   }
?>


<?php 
          mysql_close($dbc);
?>