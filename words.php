
<?php

      $dbc = mysql_connect("localhost:3306","root","hunan2010");
	  mysql_select_db("l_net",$dbc);
	  mysql_query('SET NAMES UTF8');


	  // $query = "select word_content from english_words_lib where reference_count = 1 and word_frequency <= 10000 ";
	  // $result = mysql_query($query,$dbc);
	  // while($row = mysql_fetch_array($result)){
	  // 	echo '<p>'.$row['word_content'].'</p>';
	  // }
	  
	  //纠正搜索数据
	  
	 /* $query = "select word_content from sentance_has_words where lib_id = 0 group by word_content "; 
	  $result = mysql_query($query,$dbc);
	  while($row = mysql_fetch_array($result))
	  {
		  $word = $row['word_content'];
		  $query = "select lib_id from english_words where word_content = '$word'";
		  $result2 = mysql_query($query,$dbc);
		  if($row2 = mysql_fetch_array($result2))
		  {
			  if($row2['lib_id']>0)
			  {
				  $lib_id = $row2['lib_id'];
				  $query = "update sentance_has_words set lib_id = '$lib_id' where word_content = '$word'";
				  mysql_query($query,$dbc);
				  echo '<p>'.$query.'</p>';
			  }
		  }
	  }*/
	  
	 
	  
	  
	  //计算库单词引用次数
	  
	  // $query = "select word_id from english_words_lib where word_id   order by word_id ";
	  // $result = mysql_query($query,$dbc);
	  // while($row = mysql_fetch_array($result))
	  // {
		 //  $lib_id = $row['word_id'];
		 //  $query = "select sentance_id from sentance_has_words where lib_id = '$lib_id'";
		 //  $re2 = mysql_query($query,$dbc);
		 //  $num = mysql_num_rows($re2);
		 //  $query = "update english_words_lib set reference_count = '$num' where word_id = '$lib_id'";
		 //  mysql_query($query,$dbc);
		 //  echo '<p>'.$query.'</p>';
	  // }



	  // $query = "select word_id , word_content from english_words_lib where word_id > 70000 ";
	  // $result = mysql_query($query,$dbc);
	  // while($row = mysql_fetch_array($result)){
	  // 	$word_id = $row['word_id'];
	  // 	$word = $row['word_content'];
	  // 	$query = "select collection_id from word_in_collection where word_id = '$word_id' ";
	  // 	$r2 = mysql_query($query,$dbc);
	  // 	if(mysql_num_rows($r2)==0){
	  // 		echo '<p>'.$row['word_content'].'</p>';
	  // 		$query = "select collection_id from word_primary_collection where collection_base_id = '$word_id'";
	  // 		echo '<p>'.$query.'</p>';
	  // 		$r3 = mysql_query($query,$dbc);
	  // 		if($row3 = mysql_fetch_array($r3))
	  // 		{
	  // 			$collection_id = $row3['collection_id'];
		 //  		$query = "insert into word_in_collection (word_id,collection_id) values ('$word_id','$collection_id')";
		 //  		echo '<p>'.$query.'</p>';
		 //  		mysql_query($query,$dbc);
	  // 		}
	  		
	  // 	}
	  // }


	  // $query = "select word_content from sentance_has_words where lib_id = 0 group by word_content";
	  // $result = mysql_query($query,$dbc);
	  // while($row = mysql_fetch_array($result)){
	  // 	$word = $row['word_content'];
	  // 	$id = quick_proto($word,$dbc);
	  // 	if($id != 0){
	  // 		$query = "update sentance_has_words set lib_id = '$id' where word_content = '$word'";
	  // 		mysql_query($query,$dbc);
	  // 		echo '<p>'.$query.'</p>';
	  // 	}
	  // }



	  
	  
	  
	  	  

	  
	  
	 
?>
<html>
<head>
       <style type="text/css">
	        body { padding:50px; font-size: 12px;  }
			h4 { margin:30px 0px;}
			table { border-collapse:collapse;}
			td,th { padding:10px 20px; border:1px solid #eee; border-collapse:collapse; }
			.missed_words { width:860px; }
			span { padding: 0px 2px;  margin:5px 20px; border-bottom: 1px solid #ccc; display:inline-block; }
			input { font-size:1em; border:none; outline:none;}
			
			 #english_nav { padding:5px 0px;margin:10px 0px 50px 0px;border-bottom:1px solid #ccc ;}
		   #english_nav li { padding:5px 10px; margin:0px 40px 0px 0px; display:inline; }
		   
		   #english_nav a { border:none ; color:#666; font-size:1.2em; text-decoration:none;}
		   #word_count { display:none;}
		   #word_search { width:300px; margin:auto;}
		   #word_search input { width:200px; height:32px; line-height:32px; padding:0px 10px; border:1px solid #ccc; border-right:none;}
		   #word_search button {height:32px; width:50px; }
		   .searched {color:red;  margin:0px; border-bottom:none;}
		   .play { width:30px; cursor:pointer;}
		    .play_word { width:30px; cursor:pointer;}
		   #search_result { width:960px; font-size:24px; margin:auto;  }
		   #search_result td { border:none; line-height:32px;}
		   #search_result li { border-bottom:1px solid #ddd; list-style:none; }
		   .adjust { text-decoration:none;  height:24px; border-radius:3px; font-size:14px; line-height:24px; text-align:center; }
		   #show_words { width:960px; background-color:#eee; color:#fff;   text-align:center; margin:50px auto; padding-top: 30px;}   
		   #show_words table { border:none;}
		    #show_words table td { border:none;}
			#show_words .search_word { display: inline-block;}
			#show_words table p {margin: 0px;}
			.sentance_resource {text-align: right;font-size: 0.5em;color: #aaa; margin: 0px;padding-right: 10px;}
			.operate{ min-width: 40px; }
			.love_sentance , .add_expression {width: 20px; cursor: pointer;}
			#expression_content{ padding: 15px 0px; color: #000; font-size: 1.2em; }
            .word_explanation{color: #333;font-size: 1.3em;text-align: left;padding: 0px 50px 30px;}
            .word_explanation span { text-decoration: none; padding: 5px; margin: 0px; border-bottom: none; line-height: 25px; }
            .explanation_type { font-size: 1.4em; width: 40px; padding: 5px; text-align: center; padding-left: 10px; line-height: 20px;  background: #ccc; color: #fff; }
			@media handled{
				
			}
	   </style>
</head>
<body>
      <?php require_once('struct_php/english_sys_nav.php'); ?> 
     
      </div>
<?php
		  $seaarch_words = '';
		  $search_item_id = 0;
		  $expression_content = "";
		  $search_type = 0;
		  $ids = array();
		  $sentences = array();
		  $collection = array();
		  $names = array();
		  $audio_checked = array();
		  $tag_lists = array();
		  $loved_tags = array();
		  $has_expression_check = array();
		  $search_id = 0;
          if(isset($_GET['search_words']))
			{
				$search_words = $_GET['search_words'];
				$search_words = strtolower($search_words);
				$raw_words = preg_match_all("/[a-z]+/",$search_words, $result);
				$search_id = quick_proto($result[0][0],$dbc);
				$id = 0;
				foreach ($result[0] as $word) {
					$id = quick_proto($word,$dbc);
					if($id) $ids[] = get_collection($id,$dbc);
				}
				if(count($ids)==1){
					$search_type = 1;
				}
				if(count($ids) > 1){
					$search_type = 2;
				}
				if(count($ids)>0 && count($ids[0])>0){
					$id = $ids[0][0];
					$query = "select english_sentances.sentance_id , word_content , rel_id, love_tag,lib_id from sentance_has_words inner join english_sentances on english_sentances.sentance_id = sentance_has_words.sentance_id where lib_id = '$id'";
					for($j = 0; $j <count($ids[0]);$j++)
						$query .= "  or lib_id = '".$ids[0][$j]."'";
					$query .= "group by sentance_has_words.sentance_id order by english_sentances.word_count_diff ";
					$result = mysql_query($query,$dbc);
					$tag_lists_temp = array();
					$loved_tags_temp = array();
					$collection_temp = array();
					while($row = mysql_fetch_array($result)){
						if($row['lib_id'] == $search_id){
							$item = array();
							$item[] = $row['sentance_id'];
							$item[] = $row['word_content'];
							$tag_lists[] = $row['rel_id'];
							$loved_tags[] = $row['love_tag'];
							$collection[] = $item;
						}else{
							$item = array();
							$item[] = $row['sentance_id'];
							$item[] = $row['word_content'];
							$tag_lists_temp[] = $row['rel_id'];
							$loved_tags_temp[] = $row['love_tag'];
							$collection_temp[] = $item;
						}
					}
					for($i=0;$i<count($tag_lists_temp);$i++) $tag_lists[] = $tag_lists_temp[$i];
					for($i=0;$i<count($love_tags_temp);$i++) $loved_tags[] = $love_tags_temp[$i];
					for($i=0;$i<count($collection_temp);$i++) $collection[] = $collection_temp[$i];

				}
				for($i=1;$i<count($ids);$i++){
					$id = $ids[$i][0];
					$temp = array();
					$query = "select sentance_id ,  word_content  from sentance_has_words where lib_id = '$id' ";
					for($j = 1; $j <count($ids[$i]);$j++)
						$query .= "  or lib_id = '".$ids[$i][$j]."'";
					$query .= "  order by sentance_id";
					//echo $query;
					$result = mysql_query($query,$dbc);
					while($row = mysql_fetch_array($result)){
						$item = array();
						$item[] = $row['sentance_id'];
						$item[] = $row['word_content'];
						$temp[] = $item;
					}

					foreach ($collection as $key => $item) {
						$sentance_id = $item[0];
						for($j = 0; $temp[$j][0] < $sentance_id && $j < count($temp);$j++)
						;
						if($j<count($temp) && $sentance_id == $temp[$j][0]){
							$collection[$key][] = $temp[$j][1];
						}else unset($collection[$key]);
					}
				}

				$collection = array_values($collection);
				//print_r($collection);
				foreach ($collection as $key => $item) {
					$sentance_id = $item[0];
					$query = "select sentance_content , resource_name , audio_checked from english_sentances inner join english_resource on (english_sentances.resource_id = english_resource.resource_id ) where sentance_id = '$sentance_id'";
					$result = mysql_query($query,$dbc);
					$row = mysql_fetch_array($result);
					$sentance = $row['sentance_content'];
					for($i = 0; $i < count($ids);$i++){
						$word = $item[$i+1];
						$sentance =preg_replace("/\b".$word."\b/",'<span class = "searched">'.$word."</span>", $sentance);
					}
					$sentances[] = $sentance;
					$names[] = $row['resource_name'];
					$audio_checked[] = $row['audio_checked'];
				}
				if(count($ids)>1){

					$min_ids = array();
					$loved_tags = array();
					for($i=0;$i<count($ids);$i++){
		                $temp = $ids[$i][0];
		                for($j=1;$j<count($ids[$i]);$j++){
		                    if($ids[$i][$j] < $temp) $temp = $ids[$i][$j];
		                }
		                $min_ids[] = $temp;
		            }
		            sort($min_ids);
		            for($i=0;$i<count($min_ids);$i++){
		                $hash  .= $min_ids[$i];
		            }

		            $query = "select expression_id , expression_content  from english_expression where expression_hash = '$hash' ";
		            $result = mysql_query($query,$dbc);
		            if($row = mysql_fetch_array($result)){
		            	$expression_id = $row['expression_id'];
		            	$expression_content = $row['expression_content'];
		            	$search_item_id = $expression_id;
		            	for($i = 0; $i < count($collection); $i++){
		            		$sentance_id = $collection[$i][0];
		            		$query = "select rel_id , love_tag from sentance_has_expressions where sentance_id = '$sentance_id' and expression_id = '$expression_id' ";
		            		$r2 = mysql_query($query,$dbc);
		            		if($row2 = mysql_fetch_array($r2)){
		            			$has_expression_check[] = $row2['rel_id'];
		            			$loved_tags[] = $row2['love_tag'];
		            		}else{
		            			$has_expression_check[] = 0;
		            			$loved_tags[] = 0;
		            		}
		            	}
		            	//print_r($has_expression_check);
		            }

				}
				

			}
	function get_collection($id,$dbc){
		$query = "select collection_id from word_in_collection where word_id = '$id'";
		$result = mysql_query($query,$dbc);
		$row = mysql_fetch_array($result);
		$collection_id = $row['collection_id'];
		$query = "select word_id from word_in_collection where collection_id = '$collection_id'";
		$result = mysql_query($query,$dbc);
		$item = array();
		while($row = mysql_fetch_array($result)){

			$item[] = $row['word_id'];
		}
		return $item;
	}

?>

     <div id="word_search">
      		<form method="get" action="words.php">
      		<div><input type="text" id="search_words" name="search_words"  value="<?php echo $seaarch_words;?>"/><button type="submit">搜索</button></div>
            </form>
      </div>
      <div id="show_words">
 <?php
 		if($search_item_id == 0 ){

 			$this_id = $search_id;
			$query = "select word_content,audio_path,explanation_tag from english_words_lib where word_id = '$this_id'";
			$result = mysql_query($query,$dbc);
			$row = mysql_fetch_array($result);
			print_search_word($this_id,$row['word_content'],$row['audio_path'],$row['explanation_tag']);

 			for($i = 1; $i < count($ids);$i ++){
			$this_id = $ids[$i][0];
			$query = "select word_content,audio_path from english_words_lib where word_id = '$this_id'";
			$result = mysql_query($query,$dbc);
			$row = mysql_fetch_array($result);
			print_search_word($this_id,$row['word_content'],$row['audio_path']);
		 	}

            if(count($ids) == 1){
                echo '<div class = "word_explanation">';
                $this_id = $search_id;
                $query = "select explanation_type from english_word_explanation where word_id = '$this_id' group by explanation_type";
                $result = mysql_query($query,$dbc);
                while ($row = mysql_fetch_array($result)) {
                    $type = $row['explanation_type'];
                    echo '<p><span class = "explanation_type">'.$row['explanation_type'].'<span></p>';
                    echo '<ol class = "ex_li">';
                    $query = "select explanation_chinese , explanation_english from english_word_explanation ".
                             " where word_id = '$this_id' and explanation_type = '$type'";
                    $r2 = mysql_query($query,$dbc);
                    while ($row2 = mysql_fetch_array($r2)) {
                        echo '<li class = "ex_item"><span class = "explanation_chinese">'.$row2['explanation_chinese'].
                             ' </span> <span class = "explanation_english">'.$row2['explanation_english'].'</span></li>';
                    }
                    echo '</ol>';
                }
                echo "</div>";
            }
 		}
 			
		else {
		 	echo '<p id = "expression_content">'.$expression_content.'</p>';
		 }
 ?>
      </div>
      <ul id="search_result">
      <audio id="myaudio" src=""></audio>


<?php
			if($search_type == 1){
				for($i=0;$i<count($sentances);$i++)
					if($loved_tags[$i] == 1)
					    print_sentance($collection[$i][0],$sentances[$i],$names[$i],$audio_checked[$i],$tag_lists[$i],$search_type,$loved_tags[$i]);

				for($i=0;$i<count($sentances);$i++)
					if($loved_tags[$i] == 0 && $audio_checked[$i] == 1)
					    print_sentance($collection[$i][0],$sentances[$i],$names[$i],$audio_checked[$i],$tag_lists[$i],$search_type,$loved_tags[$i]);

				for($i=0;$i<count($sentances);$i++)
					if($audio_checked[$i] == 0)
					    print_sentance($collection[$i][0],$sentances[$i],$names[$i],$audio_checked[$i],$tag_lists[$i],$search_type,$loved_tags[$i]);

			}else if($search_type == 2){
				for($i=0;$i<count($sentances);$i++)
					if($loved_tags[$i] == 1)
						print_sentance($collection[$i][0],$sentances[$i],$names[$i],$audio_checked[$i],$search_item_id,$search_type,$loved_tags[$i],$has_expression_check[$i]);

				for($i=0;$i<count($sentances);$i++)
					if($loved_tags[$i] == 0 && $has_expression_check[$i]>0)
						print_sentance($collection[$i][0],$sentances[$i],$names[$i],$audio_checked[$i],$search_item_id,$search_type,$loved_tags[$i],$has_expression_check[$i]);
				for($i=0;$i<count($sentances);$i++)
					if($has_expression_check[$i] == 0 && $audio_checked[$i] == 1)
						print_sentance($collection[$i][0],$sentances[$i],$names[$i],$audio_checked[$i],$search_item_id,$search_type,$loved_tags[$i],$has_expression_check[$i]);

				for($i=0;$i<count($sentances);$i++)
					if($audio_checked[$i] == 0)
						print_sentance($collection[$i][0],$sentances[$i],$names[$i],$audio_checked[$i],$search_item_id,$search_type,$loved_tags[$i],$has_expression_check[$i]);


			}

?>
      </ul>
      
      
      
      
      <script type="text/javascript">
	  			
	             /*var refreshButtons = document.getElementsByClassName('refresh');
				 for(var i =0;i< refreshButtons.length;i++)
				 {
					 refreshButtons[i].onclick = function(){
						 var input = this.parentNode.parentNode.getElementsByTagName('input')[0];
						 var env = this.parentNode.parentNode;
						 var path = "data_process_php/get_data.php?target=check_word&word="+input.value;
						 ajax_send("",path,env,changeWord,false);
						 
					 }
				 }
				 
				 var invalidButtons = document.getElementsByClassName('invalid');
				 for(var i=0; i< invalidButtons.length;i++)
				 {
					 invalidButtons[i].onclick = function(){
						 var parts = new Array();
						 parts.push(encodeURIComponent("invalid_word")+"="+"");
						 parts.push(encodeURIComponent("word_id")+"="+encodeURIComponent(this.value));
						 var path = "data_process_php/receive_data.php";
						 ajax_send(parts.join("&"),path,this.parentNode.parentNode,deleteTableRow,true);
					 }
					 
				 }
				 
				 var confirmButtons = document.getElementsByClassName('confirm');
				 for(var i = 0; i< confirmButtons.length;i++)
				 {
					 confirmButtons[i].onclick = function(){
						 var libId = this.parentNode.parentNode.getElementsByTagName('td')[1].innerHTML;
						 if(libId>0)
						 {
						      var parts = new Array();
							  parts.push(encodeURIComponent("set_word")+"="+"");
							  parts.push(encodeURIComponent("word_id")+"="+encodeURIComponent(this.value));
							  parts.push(encodeURIComponent("lib_id")+"="+encodeURIComponent(libId));
							  var path = "data_process_php/receive_data.php";
						      ajax_send(parts.join("&"),path,this.parentNode.parentNode,deleteTableRow,true);
						 }
						
					 }
				 }
				 
				 var inputs  = document.getElementsByClassName('input');
				 for(var i = 0; i<inputs.length;i++)
				 {
					 inputs[i].index = i;
					 inputs[i].onkeydown = function(event){
						 if(event.keyCode==13)
						 {
							 this.parentNode.parentNode.getElementsByClassName('refresh')[0].click();
							 inputs[this.index +1].focus();
						 }
					 }
				 }*/
				 var love_sentance = document.getElementsByClassName('love_sentance');
				 for(var i =0 ; i< love_sentance.length; i ++){
				 	love_sentance[i].onclick = love_sentance_click;
				 }

				 function love_sentance_click(){
				 	var state = this.getAttribute('data-state');
			 		var search_id = this.getAttribute('data-search-id');
			 		var type = this.getAttribute('data-search-type');

			 		var liNode = this.parentNode.parentNode.parentNode.parentNode.parentNode;
			 		var ulNode = liNode.parentNode;
			 		ulNode.removeChild(liNode);
			 		var parts = new Array();

				    parts.push(encodeURIComponent("love_sentance")+"="+"");
				    parts.push(encodeURIComponent("search_id")+"="+encodeURIComponent(search_id));
				    parts.push(encodeURIComponent("search_type")+"="+encodeURIComponent(type));
				    var path = "data_process_php/receive_data.php";
				    
			 		if(state == 1){
			 			this.src = "img/ico/heart-love.png";
			 			this.setAttribute('data-state','0');
			 			parts.push(encodeURIComponent("operation")+"="+encodeURIComponent("1"));
			 			ulNode.insertBefore(liNode,ulNode.childNodes[0]);
			 		}else{
			 			this.src = "img/ico/heart-normal.png";
			 			this.setAttribute('data-state','1');
			 			parts.push(encodeURIComponent("operation")+"="+encodeURIComponent("0"));
			 			ulNode.appendChild(liNode);
			 		}
			 		ajax_send(parts.join("&"),path,null,null,true);
				 }

				 var add_expression = document.getElementsByClassName('add_expression');
				 for(var i =0 ; i < add_expression.length ; i++){
				 	add_expression[i].onclick = function(){
				 		var sentance_id = this.getAttribute('data-sentance-id');
				 		var expression_id = this.getAttribute('data-expression-id');
				 		var parts = new Array();
					    parts.push(encodeURIComponent("add_expression_to_sentance")+"="+"");
					    parts.push(encodeURIComponent("sentance_id")+"="+encodeURIComponent(sentance_id));
					    parts.push(encodeURIComponent("expression_id")+"="+encodeURIComponent(expression_id));
					    var path = "data_process_php/receive_data.php";
					    ajax_send(parts.join("&"),path,this,change_sentance_status,true);
				 	}
				 }

				 function change_sentance_status(env,response){
				 	result = JSON.parse(response);
				 	env.setAttribute('data-search-type','2');
				 	env.setAttribute('data-state','1');
				 	env.setAttribute('data-search-id',result.rel_id);
				 	env.src = "img/ico/heart-normal.png";
				 	env.onclick = love_sentance_click;
				 }


				 var myAudio = document.getElementById('myaudio');
				 myAudio.start = 0;
				 myAudio.end = 0;
				 myAudio.sentance_id = 0;
				 myAudio.timer = setInterval(function(){
					if(myAudio.currentTime > myAudio.end)
					{
						myAudio.pause();
					}
				
			     },100);
 				 myAudio.onloadedmetadata = function(){
				      myAudio.currentTime = myAudio.start;

				 }  
				 var playWordButs = document.getElementsByClassName('play_word');
				 for(var i =0 ; i < playWordButs.length;i ++)
				 {
					  playWordButs[i].onclick = function(){
						 var wordAudio = this.parentNode.getElementsByTagName('audio')[0];
						 wordAudio.play();
					 }
				 }
				 var playButs = document.getElementsByClassName('play');
				 var adjustButs = document.getElementsByClassName('adjust');
				 for(var i =0 ;i<playButs.length;i++)
				 {
					 playButs[i].onclick = function(){
						 playSentance(this.id);
					 }
				 }
				 for(var i =0; i< adjustButs.length ; i++ )
				 {
					 adjustButs[i].onclick = function(){
						 myAudio.pause();
					 }
				 }
				 function playSentance(sentance_id)
				{
				   myAudio.sentance_id = sentance_id;
				   var parts = new Array();
				   parts.push(encodeURIComponent("get_sentance_audio")+"="+"");
				   parts.push(encodeURIComponent("sentance_id")+"="+encodeURIComponent(sentance_id));
				   var path = "data_process_php/receive_data.php";
				   ajax_send(parts.join("&"),path,myAudio,modify,true);
				}
				
				function modify(env,responseText)
				{
					  var response = JSON.parse(responseText);
					  myAudio.src = response.audio_path;
					  myAudio.start = response.audio_start;
					  myAudio.end = response.audio_end;
					  myAudio.play();
				}
				
				
				 function deleteTableRow(env)
				 {
					 env.parentNode.removeChild(env);
				 }
				 
				 function changeWord(env,response)
				 {
					 var word_lib = JSON.parse(response);
					 if( typeof(word_lib)!= "undefined" )
					 {
						   var id = env.getElementsByTagName('td')[1];
						   var word = env.getElementsByTagName('td')[2];
						   id.innerHTML = word_lib[0].word_id;
						   word.innerHTML = word_lib[0].word_content;
					 }
				 }
				 
				 
				 function ajax_send( sendData,path, en , fun,method )
				 {
					 var xhr = new XMLHttpRequest();
					 xhr.onreadystatechange = function(){
						 if(xhr.readyState ==4)
						 {
							 if(xhr.status >= 200 && xhr.status <300 )
							 {
								 if(fun)
								 	fun(en,xhr.responseText);
							 }
							 else
							 {
								 alert("ajax 失败！");
							 }
						 }
					  }
					  if(method)
					  {
						  
					       xhr.open("POST",path,true);
						   xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); 
					  }
					  else
					       xhr.open("GET",path,true);
					  xhr.send(sendData);
				 }
	  </script>
      
 </body>     
 </html>    
<?php
	  function print_sentance($sentance_id,$sentance,$name,$audio_checked,$search_item_id,$search_type,$love_tag,$has_expression_id)
	  {
?>
			 <li>
                <table>
                 <tr>
                    <td class="to_play">
                        <img src="img/sound.png" id="<?php echo $sentance_id; ?>" class="play"/>
                     <td>
                     <p class="sentance"><?php echo $sentance; ?> </p>
                    </td>
                    <td class="operate">
                    <?php
                    	if(!$audio_checked)
                    	{
                    ?>
                          <a target="_blank" title="<?php echo $name; ?>" href="sentance.php?sentance_id=<?php echo $sentance_id; ?>#<?php echo $sentance_id; ?>" class="adjust"><img src="img/ico/edit_32px_1116104_easyicon.net.png"/></a>
                    <?php
                    	}else {
                    		if($search_type ==1){
	                    		$src = ($love_tag == 1)? "heart-love.png" : "heart-normal.png";
	                    		$state = ($love_tag == 1)? "0" : "1";
	                    		echo '<img class = "love_sentance" data-sentance-id = "'.$sentance_id.'" data-state = "'.$state.'" data-search-id = "'.$search_item_id.'" data-search-type = "'.$search_type.'" src = "img/ico/'.$src.'"/>';
                    		}else if($search_type == 2 && $search_item_id != 0){
                    			if($has_expression_id >0 ){
                    				$src = ($love_tag == 1)? "heart-love.png" : "heart-normal.png";
		                    		$state = ($love_tag == 1)? "0" : "1";
		                    		echo '<img class = "love_sentance" data-sentance-id = "'.$sentance_id.'" data-state = "'.$state.'" data-search-id = "'.$has_expression_id.'" data-search-type = "'.$search_type.'" src = "img/ico/'.$src.'"/>';
                    			}else{
                    				echo '<img class = "add_expression" data-sentance-id = "'.$sentance_id.'"  data-expression-id = "'.$search_item_id.'"  src = "img/ico/add.png"/>';
                    			}

                    		}
                    	}
                    ?>
                    </td>
                   </tr>
                </table>
                <p class="sentance_resource"><?php echo $name; ?></p>
             </li>
<?php
	  }
	  
	  function print_search_word($word_id,$word_content,$audio_path,$explanation_tag)
	  {
?>
			<div class="search_word">
                <table>
                 <tr>
                    <td class="">
                        <img src="img/sound.png"  class="play_word"/>
                        <audio src="<?php echo $audio_path; ?>" class="word_audio" id="<?php echo $word_id;?>"></audio>
                     </td>
                     <td>
                     <p class="sentance"><?php echo $word_content; ?> </p>
                    </td>
					<?php
						if($explanation_tag == 0){

					?>
					<td>
						<a target = "_blank" href="https://fanyi.sogou.com/#auto/zh-CHS/<?php echo $word_content;?>">查词</a>
					</td>
                    <?php
						}
					?>
                   </tr>
                </table>
             </div>

<?php
	  }
	  
?>

<?php

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



?>


<?php
       mysql_close($dbc);
?>