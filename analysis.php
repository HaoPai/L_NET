<?php
      //定义数据库连接信息
	 $dbc=mysql_connect("localhost:3308","root","hunan2010");
      mysql_select_db("l_net",$dbc);
	  mysql_query('SET NAMES UTF8');
	  
	 /* 
	  $query = "select * from english_material_analysis where material_id  >= 12";
	  $result = mysql_query($query,$dbc);
	  $regx = '/[a-z]+/i';
	  while($row = mysql_fetch_array($result)){
		  $material_id = $row['material_id'];
		  $material_content = $row['material_content'];
		  $matchs = array();
		  preg_match_all($regx,$material_content,$matchs);
		  foreach($matchs[0] as $match){
			  $match = strtolower($match);
			  $lib_id = word_proto($match,$dbc);
			  $query = "select rel_id from material_has_words where material_id = '$material_id' ".
			  " and lib_id = '$lib_id' and word_content = '$match'";
			  $r = mysql_query($query,$dbc);
			  if(mysql_num_rows($r)==0){
				  $query = "insert into material_has_words ".
				           " (material_id , lib_id , word_content )".
						   " values ('$material_id','$lib_id','$match')";
				  mysql_query($query,$dbc);  
			  }
			  
		  }
	  }*/
	  
	  
	  
	 /* 
	  $query = "select * from english_material_analysis ";
	  $result = mysql_query($query,$dbc);
	  while($row = mysql_fetch_array($result)){
		  $material_id = $row['material_id'];
		  $material_name = $row['material_name'];
		  $query = "select * from material_has_words where material_id = '$material_id' group by lib_id";
		  $r = mysql_query($query,$dbc);
		  $all_word_count = mysql_num_rows($r);
		  $query = "select material_has_words.rel_id  from material_has_words ".
		  "inner join english_words_lib on material_has_words.lib_id = english_words_lib.word_id ".
		  " where material_id = '$material_id' and english_words_lib.pgee_check = 1 ".
		  "  group by material_has_words.lib_id";
		  $r = mysql_query($query,$dbc);
		  $pgee_word_count = mysql_num_rows($r);
		   $query = "select material_has_words.rel_id  from material_has_words ".
		  "inner join english_words_lib on material_has_words.lib_id = english_words_lib.word_id ".
		  " where material_id = '$material_id' and english_words_lib.avg_rate > 5 ".
		  "  group by material_has_words.lib_id";
		  $r = mysql_query($query,$dbc);
		  $high_frequncy_count = mysql_num_rows($r);
		   $query = "select material_has_words.rel_id  from material_has_words ".
		  "inner join english_words_lib on material_has_words.lib_id = english_words_lib.word_id ".
		  " where material_id = '$material_id' and english_words_lib.avg_rate <= 5 and english_words_lib.avg_rate >= 4  ".
		  "  group by material_has_words.lib_id";
		  $r = mysql_query($query,$dbc);
		  $middle_frequncy_count = mysql_num_rows($r);
		   $query = "select material_has_words.rel_id  from material_has_words ".
		  "inner join english_words_lib on material_has_words.lib_id  = english_words_lib.word_id ".
		  " where material_id = '$material_id'  and english_words_lib.avg_rate < 4  ".
		  "  group by material_has_words.lib_id";
		  $r = mysql_query($query,$dbc);
		  $low_frequncy_count = mysql_num_rows($r);
		  $query = "update english_material_analysis set pgee_word_count = '$pgee_word_count' ,".
		           "  high_frequency_count = '$high_frequncy_count', ".
				   "  middle_frequency_count = '$middle_frequncy_count' ,".
				   "  low_frequency_count = '$low_frequncy_count', ".
				   " all_word_count = '$all_word_count' where material_id = '$material_id'";
		 mysql_query($query,$dbc);
	  }*/
	  
	  
	 

?>
<html>
	<head>
    	<style>
			body { width:1060px; margin:auto; }
		    #material_accounting {border-collapse:collapse;}
			#material_accounting td { border:1px solid #aaa; border-collapse:collapse; padding:10px 15px;}
			 #material_accounting button {  padding:5px 5px; cursor:pointer; background-color:#fff; border:1px solid #ddd; }
			 #words {  border-collapse:collapse; width:570px; margin:auto; margin-top:50px; display:none; }
			 #words th, #words td { border:1px solid #aaa; border-collapse:collapse; padding:10px 0px; width:120px; padding-left:20px;}
			 .got_words { font-size:1.2em;}
			 h2 {margin-top: 80px;}
		</style>
    </head>
    <body>
    	<h2>材料库概览</h2>
    	<table id="material_accounting">
        	
<?php 
      $query = "select * from english_material_analysis where material_id <>3  order by all_word_count desc";
	  $result = mysql_query($query,$dbc);
	  while($row = mysql_fetch_array($result)){
		  $material_id = $row['material_id'];
		  $material_name = $row['material_name'];
		  $pgee_count = $row['pgee_word_count'];
		  $high_frequency_count = $row['high_frequency_count'];
		  $middle_frequency_count = $row['middle_frequency_count'];
		  $low_frequency_count = $row['low_frequency_count'];
		  $all_word_frequency_count = $row['all_word_count'];

?>
            <tr>
            	<td><?php echo $material_name; ?></td>
                <td><button class="get_pgee" id="<?php echo $material_id; ?>">考研单词（<?php echo $pgee_count; ?>）</button></td>
                <td>考研适应率:（<?php echo (int)(100*$pgee_count/$all_word_frequency_count); ?>%)</td>
                <td><button class="get_high" id="<?php echo $material_id; ?>">高频词（<?php echo $high_frequency_count; ?>）</button></td>
                <td><button class="get_middle" id="<?php echo $material_id; ?>">中频词（<?php echo $middle_frequency_count; ?>）</button></td>
                <td><button class="get_low" id="<?php echo $material_id; ?>">低频词（<?php echo $low_frequency_count; ?>）</button></td>
                <td><button class="get_all" id="<?php echo $material_id; ?>">所有单词（<?php echo $all_word_frequency_count; ?>）</button></td>
            </tr>
<?php
	  }
	
?>
		</table>
        <table id="words">
        	<thead>
            	<tr><th colspan="4">查询得到的单词</th></tr>
            	<tr>
                  	<th>单词</th>
                    <th>频率分级</th>
                    <th>考研单词</th>
                    <th>新概念引用</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <script type="text/javascript">
			var get_pgee_buttons = document.getElementsByClassName('get_pgee');
			var get_high_buttons = document.getElementsByClassName('get_high');
			var get_middle_buttons = document.getElementsByClassName('get_middle');
			var get_low_buttons = document.getElementsByClassName('get_low');
			var get_all_buttons = document.getElementsByClassName('get_all');
			var words = document.getElementById('words');
			for(var i = 0; i < get_pgee_buttons.length ; i ++ )
			{
				get_pgee_buttons[i].onclick = function(){
					get_words(this.id,'pgee');
					words.getElementsByTagName('th')[0].innerHTML = this.parentNode.parentNode.getElementsByTagName('td')[0].innerHTML+'-考研词汇';
				}
			}
			
			for(var i = 0; i < get_high_buttons.length ; i ++ )
			{
				get_high_buttons[i].onclick = function(){
					get_words(this.id,'high');
					words.getElementsByTagName('th')[0].innerHTML = this.parentNode.parentNode.getElementsByTagName('td')[0].innerHTML+'-高频词汇';
				}
			}
			
			for(var i = 0; i < get_middle_buttons.length ; i ++ )
			{
				get_middle_buttons[i].onclick = function(){
					get_words(this.id,'middle');
					words.getElementsByTagName('th')[0].innerHTML = this.parentNode.parentNode.getElementsByTagName('td')[0].innerHTML+'-中频词汇';
				}
			}
			for(var i = 0; i < get_low_buttons.length ; i ++ )
			{
				get_low_buttons[i].onclick = function(){
					get_words(this.id,'low');
					words.getElementsByTagName('th')[0].innerHTML = this.parentNode.parentNode.getElementsByTagName('td')[0].innerHTML+'-低频词汇';
				}
			}
			for(var i = 0; i < get_all_buttons.length ; i ++ )
			{
				get_all_buttons[i].onclick = function(){
					get_words(this.id,'all');
					words.getElementsByTagName('th')[0].innerHTML = this.parentNode.parentNode.getElementsByTagName('td')[0].innerHTML+'-所有词汇';
				}
			}
			
			function get_words(materialId,type)
			{
				words.style.display="block";
				 words.getElementsByTagName('tbody')[0].innerHTML= "";
				 var parts = new Array();
				 parts.push(encodeURIComponent("target")+"="+"material_words");
				 parts.push(encodeURIComponent("material_id")+"="+encodeURIComponent(materialId));
				 parts.push(encodeURIComponent("type")+"="+encodeURIComponent(type));
				 var path = "data_process_php/get_data.php?target=material_words&material_id="+materialId+"&type="+ type ;
				 ajax_send(parts.join("&"),path,words.getElementsByTagName('tbody')[0],show_words,false);
			}
			
			function show_words(env,response)
			{
				
				var words = JSON.parse(response);
				for(var i = 0; i <words.length; i++){
					var OTr = document.createElement('tr');
					OTr.innerHTML = '<td class = "got_words">'+words[i].word_content+"</td><td>"+words[i].avg_rate+"</td><td>"+((words[i].pgee_check == 1)? "是" : "否" )+"</td><td>"+words[i].nce_count+"</td>"
					env.appendChild(OTr);
				}
			}
			function ajax_send( sendData,path, en , fun,method )
				 {
					 var xhr = new XMLHttpRequest();
					 xhr.onreadystatechange = function(){
						 if(xhr.readyState ==4)
						 {
							 if(xhr.status >= 200 && xhr.status <300)
							 {
								 
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
      
	   
	 function word_proto( $word , $dbc )
	{ 
		$query = "select * from english_words where word_content = '$word'";
		$result = mysql_query($query,$dbc);
		if($row = mysql_fetch_array($result))
		{
			if($row['lib_id']>0)
				return $row['lib_id'];
		}
		$lib_id = check_proto($word,$dbc);
		if($lib_id >0)
		{
		    return $lib_id;
		}
		
		$temp = $word.' ';
		$temp = str_replace('s ','',$temp);
		$lib_id = check_proto($temp,$dbc);
		if($lib_id > 0)
		{
		    return $lib_id;
		}
		
		$temp = $word.' ';
		$temp = str_replace('es ','',$temp);
		$lib_id = check_proto($temp,$dbc);
		if($lib_id > 0)
		{
		    return $lib_id;
		}
		
		$temp = $word.' ';
		$temp = str_replace('d ','',$temp);
		$lib_id = check_proto($temp,$dbc);
		if($lib_id > 0 )
		{
		    return $lib_id;
		}
		
		$temp = $word.' ';
		$temp = str_replace('ed ','',$temp);
		$lib_id = check_proto($temp,$dbc);
		if($lib_id > 0 )
		{
		    return $lib_id;
		}
		$temp = str_replace('ing ','',$temp);

		$lib_id = check_proto($temp,$dbc);
		if($lib_id >0)
		{
		    return $lib_id;
		}
		
		$temp = $word.' ';
		$temp = str_replace('ing ','e',$temp);
		$lib_id = check_proto($temp,$dbc);
		if($lib_id > 0)
		{
		    return $lib_id;
		}
		
		$temp = $word.' ';
		$temp = str_replace('ly ','',$temp);
		$lib_id = check_proto($temp,$dbc);
		if($lib_id > 0)
		{
		    return $lib_id;
		}
		
		$temp = $word.' ';
		$temp = str_replace('in ','',$temp);
		$lib_id = check_proto($temp,$dbc);
		if($lib_id > 0)
		{
		    return $lib_id;
		}
		
		return 0;
	}
	
	function check_proto($word,$dbc)
	{
		$query ="select * from english_words_lib where word_content = '$word'";
		$result = mysql_query($query,$dbc);
		if($row = mysql_fetch_array($result))
		{
			return $row['word_id'];
		}
		else
		{
			return 0 ;
		}
	}

?>


<?
   
	  mysql_close($dbc);
?>