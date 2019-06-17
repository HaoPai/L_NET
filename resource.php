<?php
	  //include "library/getid3/getid3.php";
	  require_once('module/getid3/getid3.php');
      $dbc = mysql_connect("localhost:3306","root","hunan2010");
	  mysql_select_db("l_net",$dbc);
	  mysql_query('SET NAMES UTF8');

	  // $resource_id = 377;
	  // $leap = -1;
	  // $query = "select sentance_id , audio_begin, audio_stop from english_sentances where resource_id = '$resource_id'";
	  // $result = mysql_query($query,$dbc);
	  // while($row = mysql_fetch_array($result)){
	  // 		$sentance_id = $row['sentance_id'];
	  // 		$begin = $row['audio_begin'];
	  // 		$stop = $row['audio_stop'];
	  // 		$begin = $begin + $leap;
	  // 		$stop = $stop + $leap;
	  // 		$query = "update english_sentances set audio_begin = '$begin',audio_stop = '$stop' where sentance_id = '$sentance_id'";
	  // 		mysql_query($query,$dbc);
	  // }
	  
	  
?>
<!DOCTYPE html>
<html>
<head>
	   <meta charset="utf-8"/>
       <style type="text/css">
	        body { padding:50px;  }
			h4 { margin:30px 0px;}
			table { border-collapse:collapse;}
			td,th { padding:10px 20px; border:1px solid #aaa; border-collapse:collapse; }
			.missed_words { width:860px; }
			.initial_td {  }
			.play {width: 50px;}
			span { width:100px;  margin:5px 20px; border-bottom: 1px solid #ccc; display:inline-block; }
			input { font-size:1em; border:none; outline:none;}
			
			 #english_nav { padding:5px 0px;margin:10px 0px 50px 0px;border-bottom:1px solid #ccc ;}
		   #english_nav li { padding:5px 10px; margin:0px 40px 0px 0px; display:inline; }
		   
		   #english_nav a,td a { border:none ; color:#666; font-size:1.2em; text-decoration:none;}
	   </style>
</head>
<body>
      <?php require_once('struct_php/english_sys_nav.php'); ?>
	  <audio id="audio" src=""></audio>
      <table>
      <tr><th>资源名称</th><th>资源序号</th><th>音频名称</th><th>文本名称</th><th></th></tr>
<?php
		
			$query = "select * from english_resource  order by resource_id desc";
			$result = mysql_query($query,$dbc);
			while($row = mysql_fetch_array($result))
			{
				echo '<tr id="'.$row['resource_id'].'">';
				echo '<td><a target = "blank" href = "sentance.php?resource_id='.$row['resource_id'].'">'.$row['resource_name'].'</a></td>';
				echo '<td>'.$row['resource_index'].'</td>';
				echo '<td class="resource_name">'.$row['audio_name'].'</td>';
				echo '<td>'.$row['file_name'].'</td>';
				echo '<td><button class = "play">播放</button></td>';
				echo '<td class = "initial_td"><button class = "initial" id = "'.$row['resource_id'].'">初始化</button</td>';
				echo '</tr>';
			}
	
		
		
		//设置资源音频长度
	  $query = "select * from english_resource where resource_id > 340  order by resource_id desc";
	  $result = mysql_query($query,$dbc);
	  while($row=mysql_fetch_array($result))
	  {
		  $audio_name = 'audio/'.$row['audio_name'];
		  $resource_id = $row['resource_id'];
		  if(is_file($audio_name))
		  {
			  $getID3 = new getID3;
			  $file_path = '/var/www/html/'.$audio_name;
			  $file_info = $getID3->analyze($file_path);
			  $audio_length = (int)($file_info['playtime_seconds']);
			  $query = "update english_resource set audio_length = '$audio_length' where resource_id = '$resource_id'";
			  mysql_query($query,$dbc);
		  }
	  }
?>

    <script>
		var myAudio = document.getElementById('audio');
		var fileNames = document.getElementsByClassName('files')
		var playButs = document.getElementsByClassName('play');
		var initButs = document.getElementsByClassName('initial');
		for(var i =0; i< initButs.length ; i++)
		{
			initButs[i].onclick = function(){
				 var parts = new Array();
				 parts.push(encodeURIComponent("init_resource")+"="+"");
				 parts.push(encodeURIComponent("resource_id")+"="+encodeURIComponent(this.id));
				 var path = "data_process_php/receive_data.php";
				 ajax_send(parts.join("&"),path,this.parentNode.parentNode,doNothing,true);
			}
		}
		for(var i=0; i< playButs.length;i++)
		{
			playButs[i].onclick = function()
			{
				myAudio.src = 'audio/'+this.parentNode.parentNode.getElementsByClassName('resource_name')[0].innerHTML;
				myAudio.play();
				
			}
		}
	
		
		
		function modify(env,responseText)
		{
			var response = JSON.parse(responseText);
			if(response.status == 'success')
			{
				var resourceName = env.getElementsByClassName('resource_name')[0];
				resourceName.innerHTML = response.file;
				
			}
			else
			{
				alert("error");
			}
		}
		 function doNothing()
		 {
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


<?php
	mysql_close($dbc);
?>