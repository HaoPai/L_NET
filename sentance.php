<?php
	  
      $dbc = mysql_connect("localhost:3306","root","hunan2010");
	  mysql_select_db("l_net",$dbc);
	  mysql_query('SET NAMES UTF8');
	  $mode = 0;
	  $resource_id = 10;
	  $sentance_id = 100;
	  $filter = 0;
	  
	  $resource_name ='';
	  $resource_alias = '';
	  $audio_name = '';
	  
	  if(isset($_GET['resource_id']))
	  {
		  $mode = 2;
		  $resource_id = $_GET['resource_id'];
	  }
	  if(isset($_GET['filter']))
	  {
		  $filter = $_GET['filter'];
	  }

	  if(isset($_GET['sentance_id']))
	  {
		  $mode = 2;
		  $sentance_id = $_GET['sentance_id'];
		  $query ="select resource_id from english_sentances where sentance_id = '$sentance_id'";
		  $result = mysql_query($query,$dbc);
		  if($row = mysql_fetch_array($result))
		  {
			  $resource_id = $row['resource_id'];
			  $mode = 2;
			  
		  }
	  }
	  $query = "select resource_name,resource_alias,audio_name from english_resource where resource_id = '$resource_id'";
	  $result = mysql_query($query,$dbc);
	  if($row = mysql_fetch_array($result))
	  {
		  $resource_name = $row['resource_name'];
		  $resource_alias = $row['resource_alias'];
		  $audio_name = $row['audio_name'];
		 
	  }
?>
<html>
<head>
       <style type="text/css">
	   		*{margin:0; padding:0; }
	        body { padding:50px;  }
			h4 { margin:30px 0px;}
			h2 { text-align:center; font-weight:normal; margin:30px 0px;}
			table { border-collapse:collapse;}
			td,th { padding:10px 20px; border:1px solid #aaa; border-collapse:collapse; }
			.missed_words { width:860px; }
			span { width:100px;  margin:5px 20px; border-bottom: 1px solid #ccc; display:inline-block; }
			input { font-size:1em; border:none; outline:none;}
			
			 #english_nav { padding:5px 0px;margin:10px 0px 50px 0px;border-bottom:1px solid #ccc ;}
		   #english_nav li { padding:5px 10px; margin:0px 40px 0px 0px; display:inline; }
		   
		   #english_nav a { border:none ; color:#666; font-size:1.2em; text-decoration:none;}
		   
		   #audio_adjust { width:960px; font-size:24px;  }
		   .aujust_right,.adjust_left { width: 250px; height:50px;  text-align:center; line-height:50px; }
		   .time_line {  height:50px; width:10px; margin:auto; line-height:50px; }
		   .adjust_right { float:right;}
		   .adjust_left { float:left; width:300px;}
		   .adjust_button { margin:5px; padding:5px 8px; border-radius:5px; border:none;
		                   background-color:#8FA9BE; color:#fff; cursor:pointer;-webkit-user-select:none; }
			.control { display:none;}
			.play { width:30px; cursor:pointer;}
			.to_adjust,.to_confirm { width:20px; cursor:pointer;}
			#audio_adjust table,#audio_adjust td { border:none;}
			#audio_adjust li { border-bottom:1px solid #ccc ; list-style:none; padding:10px 0px;}
			#audio_adjust .not_checked {  border-bottom:1px solid #FEBECD;}
			#empty_bottom { height:600px; }
			.customize { width:40px; height:24px; line-height:24px; padding:0px 3px; border:1px solid #ccc ; font-size:14px;}
			#download_resource { text-align:center; }
			#download_resource button, #download_resource a { padding:5px 10px; margin:5px 10px; font-size:14px; 
			          border:none; text-decoration: none; background-color:#ccc; color:#fff; cursor:pointer;}
			#download_link { display:none;}
			.checked .to_adjust {
				display: none;
			}
			.checked .to_confirm {
				display: none;
			}
			.radio_check img{width: 18px; cursor: pointer;}
			.radio_check {display: none;}
	   </style>
</head>
<body>
      <?php require_once('struct_php/english_sys_nav.php'); ?>
      <audio id="audio" src=""></audio>
      <h2><?php echo $resource_name;?></h2>
      <p id="download_resource"><button  id="prepare_resource">准备资源</button><a target="_blank" id="download_link" href="">下载资源</a></p>
      <ul id="audio_adjust">
<?php

		//句子时间调整
		
		if($mode ==1)
		{
			$query = "select * from english_sentances where sentance_id = '$sentance_id'";
			$result = mysql_query($query,$dbc);
			$row = mysql_fetch_array($result);
?>
		
<?php 
		}
		else if($mode == 2)
		{
			$query = "select * from english_sentances where resource_id = '$resource_id' and word_count >= '$filter' order by sentance_index";
			$result = mysql_query($query,$dbc);
			while($row = mysql_fetch_array($result))
			{
				  $audio_checked = $row['audio_checked'];
?>
                  <li <?php if($audio_checked==0) echo 'class = "not_checked"'; else echo 'class = "checked"';?>>
                      <table>
                       <tr>
                          <td class="to_play">
                              <img src="img/sound.png" id="<?php echo $row['sentance_id']; ?>" class="play"/>
                           </td>
                           <td class="radio_check"><img class="select" id="<?php echo $row['sentance_id']; ?>"  src = "img/ico/radio-unchecked.png" /></td>
                          <td>
                           <p class="sentance"><?php echo $row['sentance_content']; ?> </p>
                          </td>
                          <td>
                              <img src="img/ico/two_end_arrow.png" id="<?php echo $row['sentance_id']; ?>"  class="to_adjust"/>
                          </td>
                          <td>
                              <img src="img/ico/accept.png" id="<?php echo $row['sentance_id']; ?>"  class="to_confirm"/>
                          </td>
                         </tr>
                       </table>
                       <div class="control" id="controls_<?php echo $row['sentance_id']; ?>">
                       	           
                              <div class="adjust_right">
                                  <button id="<?php echo $row['sentance_id']; ?>"  class="adjust_button end_sub_five ">&lt;&lt;5s</button>
                                  <button id="<?php echo $row['sentance_id']; ?>"  class="adjust_button end_sub_one ">&lt;1s</button>
                                  <button id="<?php echo $row['sentance_id']; ?>"  class="adjust_button end_add_one">&gt;1s</button>
                                  <button id="<?php echo $row['sentance_id']; ?>"  class="adjust_button end_add_five">&gt;&gt;5s</button>
                                  
                              </div>
                              
                              <div class="adjust_left">
                                  <button id="<?php echo $row['sentance_id']; ?>" class="adjust_button start_sub_five ">&lt;&lt;5s</button>
                                  <button id="<?php echo $row['sentance_id']; ?>"  class="adjust_button start_sub_one ">&lt;1s</button>
                                  <button id="<?php echo $row['sentance_id']; ?>"  class="adjust_button start_add_one">&gt;1s</button>
                                  <button id="<?php echo $row['sentance_id']; ?>"  class="adjust_button start_add_five">&gt;&gt;5s</button>
                                  <input type="text" id="<?php echo $row['sentance_id']; ?>" class="customize"/>
                              </div>
                              <div class="time_line" id="time_line_<?php echo $row['sentance_id']; ?>">12</div>
                       </div>
                   </li>
<?php 
		      }
	    }
    	//句子统一编号
		
?>
		</ul>
<?php 		


?> 
		<div id="empty_bottom">
        </div>
		<script>
			var resourceId = <?php echo $resource_id; ?>;
			var myAudio = document.getElementById('audio');
			myAudio.start = 0;
			myAudio.end = 0;
			myAudio.sentance_id = 0;
			myAudio.timer = setInterval(function(){
				if(myAudio.currentTime > myAudio.end)
				{
					myAudio.pause();
				}
				var time_line = 'time_line_'+myAudio.sentance_id;
				var oTimeLine = document.getElementById(time_line);
				if(oTimeLine)
				     oTimeLine.innerHTML = parseInt(myAudio.end - myAudio.currentTime);
				
			},100);
		        myAudio.onloadedmetadata = function(){
			     myAudio.currentTime = myAudio.start;
			}  

			var selected = 0;
			var first = 0;
			var first_id = 0;
			var last_id = 0;
			var last = 0;


			var playButs = document.getElementsByClassName('play');
			var toPlays =  document.getElementsByClassName('to_play');
			var adjustButs = document.getElementsByClassName('to_adjust');
			var confirmButs = document.getElementsByClassName('to_confirm');
			var controls = document.getElementsByClassName('control');
			var radioChecks = document.getElementsByClassName('radio_check');
			var selects = document.getElementsByClassName('select');
			
			var startSubFive = document.getElementsByClassName('start_sub_five');
			var startSubOne = document.getElementsByClassName('start_sub_one');
			var startAddFive = document.getElementsByClassName('start_add_five');
			var startAddOne = document.getElementsByClassName('start_add_one');
			
			var startCustomize = document.getElementsByClassName('customize');
			
			
			var endSubFive = document.getElementsByClassName('end_sub_five');
			var endSubOne = document.getElementsByClassName('end_sub_one');
			var endAddFive = document.getElementsByClassName('end_add_five');
			var endAddOne = document.getElementsByClassName('end_add_one');
			
			for(var i =0;i< startSubFive.length;i++)
			{
				startSubFive[i].onclick = function(){
					changeStart(this.id,-5);
				}
			}
			
			for(var i =0;i< startSubOne.length;i++)
			{
				startSubOne[i].onclick = function(){
					changeStart(this.id,-1);
				}
			}
			
			for(var i =0;i< startAddFive.length;i++)
			{
				startAddFive[i].onclick = function(){
					changeStart(this.id,5);
				}
			}
			
			for(var i =0;i< startAddOne.length;i++)
			{
				startAddOne[i].onclick = function(){
					changeStart(this.id,1);
				}
			}
			
			for(var i = 0; i< startCustomize.length ; i ++)
			{
				startCustomize[i].onkeydown = function(event){
					if(event.keyCode==13)
				   {
					   changeStart(this.id,parseInt(this.value));
					   this.value = "";
				   }
				}
			}
			
				for(var i =0;i< endSubFive.length;i++)
			{
				endSubFive[i].onclick = function(){
					changeEnd(this.id,-5);
				}
			}
			
			for(var i =0;i< endSubOne.length;i++)
			{
				endSubOne[i].onclick = function(){
					changeEnd(this.id,-1);
				}
			}
			
			for(var i =0;i< endAddFive.length;i++)
			{
				endAddFive[i].onclick = function(){
					changeEnd(this.id,5);
				}
			}
			
			for(var i =0;i< endAddOne.length;i++)
			{
				endAddOne[i].onclick = function(){
					changeEnd(this.id,1);
				}
			}
			
			
			
			
			for(var i =0; i< playButs.length ;i++)
			{
				playButs[i].onclick = function(){
					 var sentance_id = this.id;
					 playSentance(sentance_id);
				}
			}

			for(var i =0; i< toPlays.length ;i++)
			{
				toPlays[i].ondblclick = function(){
					for(var j=0;j<radioChecks.length;j++){
						radioChecks[j].style.display = "table-cell";
					}
				}
			}
			for(var i=0;i<selects.length;i++){
				selects[i].state = 0;
				selects[i].num = i;
				selects[i].onclick = function(){
					if(selected == 0){
						selected =1;
						first = this.num;
						first_id = this.id;
						last = this.num;
						last_id = this.id;
						this.parentNode.nextSibling.nextSibling.style.background = "#999";
						this.parentNode.nextSibling.nextSibling.style.color = "#ffffff";
						this.src = "img/ico/radio-checked.png";

					}else if(last!=this.num){
						if(selected ==2 ) {
							first = last;
							first_id = last_id;
						}
						last = this.num;
						last_id = this.id;
						selected = 2;
						var min,max;
						if(first<last){
							min = first;
							max = last;
						}else{
							min = last;
							max = first;
						}
						for(var j=0;j<selects.length;j++){
							if ((selects[j].num >= min) && (selects[j].num<= max)) {
								selects[j].src = "img/ico/radio-checked.png";
								selects[j].state = 1;
								selects[j].parentNode.nextSibling.nextSibling.style.background = "#999";
								selects[j].parentNode.nextSibling.nextSibling.style.color = "#ffffff";
							}else{
								selects[j].src = "img/ico/radio-unchecked.png";
								selects[j].state =0;
								selects[j].parentNode.nextSibling.nextSibling.style.background = "#fff";
								selects[j].parentNode.nextSibling.nextSibling.style.color = "#000";
							}

						}
					}
					
				}
			}

			for(var i=0;i<radioChecks.length;i++){
				radioChecks[i].ondblclick = function(){
					var parts = new Array();
					parts.push(encodeURIComponent("merge_sentances")+"="+"");
					parts.push(encodeURIComponent("first_id")+"="+encodeURIComponent(first_id));
					parts.push(encodeURIComponent("last_id")+"="+encodeURIComponent(last_id));
					var path = "data_process_php/receive_data.php";
				    if(window.confirm("确认合并所选择句子？"))
				    {
					  ajax_send(parts.join("&"),path,document,merge,true);
				    }
				    else
				    {
					   alert("你已经取消确认！");
				    }
				}
			}
			
			for(var i =0; i< adjustButs.length ; i++)
			{
				adjustButs[i].onclick = function(){
					for(var j=0;j<controls.length;j++)
					{
						controls[j].style.display ="none";
					}
				   var sentance_id = this.id;
				   myAudio.sentance_id = sentance_id;
				   
				   var parts = new Array();
				   parts.push(encodeURIComponent("auto_change_audio")+"="+"");
				   parts.push(encodeURIComponent("sentance_id")+"="+encodeURIComponent(sentance_id));
				   var path = "data_process_php/receive_data.php";
				   ajax_send(parts.join("&"),path,myAudio,modify,true);
				   
					var myControl = this.parentNode.parentNode.parentNode.parentNode.parentNode.getElementsByClassName('control')[0];
					myControl.style.display ="block";
					
				}
			}
			for(var i =0; i< confirmButs.length ; i++)
			{
				confirmButs[i].onclick = function(){
				   var parts = new Array();
				   parts.push(encodeURIComponent("confirm_sentance")+"="+"");
				   parts.push(encodeURIComponent("sentance_id")+"="+encodeURIComponent(this.id));
				   var path = "data_process_php/receive_data.php";
				   if(window.confirm("确认此句已调整无误？"))
				   {
					  ajax_send(parts.join("&"),path,this.parentNode.parentNode,doNothing,true);
					  this.parentNode.parentNode.parentNode.parentNode.parentNode.className="";
				   }
				   else
				   {
					   alert("你已经取消确认！");
				   }
				}
			}
			var toGo = 'controls_'+parseInt(location.hash.substr(1));
			var myControl = document.getElementById(toGo);
			if(myControl)
			        myControl.style.display="block";
			var download = document.getElementById('download');
			var prepare = document.getElementById('prepare_resource');
			prepare.onclick = function(){
				 var parts = new Array();
				 parts.push(encodeURIComponent("prepare_resource")+"="+"");
				 parts.push(encodeURIComponent("resource_id")+"="+encodeURIComponent(resourceId));
				 var path = "data_process_php/receive_data.php";
				 ajax_send(parts.join("&"),path,download,downloadReady,true);
			}


			function merge(env,responseText){
				var min,max;
				if(first<last){
					min = first;
					max = last;
				}else{
					min = last;
					max = first;
				}
				for(var i=0;i<selects.length;i++){
					if(selects[i].num <min || selects[i].num >max) continue;
					else if(selects[i].num == min){
						selects[i].parentNode.nextSibling.nextSibling.children[0].innerHTML = responseText;
						selects[i].parentNode.nextSibling.nextSibling.style.background = "#fff";
						selects[i].parentNode.nextSibling.nextSibling.style.color = "#000";
						selects[i].src = "img/ico/radio-unchecked.png";
                        var to_change = selects[i].parentNode.parentNode.parentNode.parentNode.parentNode;
                        to_change.className = "not_checked";
					}else{
                        //alert('delete');
						var to_delete = selects[i].parentNode.parentNode.parentNode.parentNode.parentNode;
						to_delete.parentNode.removeChild(to_delete);
                        i --;
					}
				}
				selected = 0;
				first = 0;
				first_id = 0;
				last_id = 0;
				last = 0;

			}
			
			function downloadReady(env)
			{
				var download = document.getElementById('download_link');
				download.href="temp_file/Resource_<?php echo $resource_alias; ?>.zip";	
				download.style.display ="inline";	
			}
			function doNothing()
			{
			}
			
			function changeStart(sentance_id,num)
			{
			   var parts = new Array();
			   parts.push(encodeURIComponent("change_audio_begin")+"="+"");
			   parts.push(encodeURIComponent("sentance_id")+"="+encodeURIComponent(sentance_id));
			   parts.push(encodeURIComponent("num")+"="+encodeURIComponent(num));
			   var path = "data_process_php/receive_data.php";
			   ajax_send(parts.join("&"),path,myAudio,modify,true);
			}
			
			function changeEnd(sentance_id,num)
			{
			   var parts = new Array();
			   parts.push(encodeURIComponent("change_audio_stop")+"="+"");
			   parts.push(encodeURIComponent("sentance_id")+"="+encodeURIComponent(sentance_id));
			   parts.push(encodeURIComponent("num")+"="+encodeURIComponent(num));
			   var path = "data_process_php/receive_data.php";
			   ajax_send(parts.join("&"),path,myAudio,modify,true);
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


<?php


     function clean_word( $word_in)
	 {
		 $word_out= '';
		 $word = strtolower($word_in);
		 $regex = '/[a-z ]{1}/';
		 $regex2 = '/-/';
		 for($i=0 ; $i < strlen($word) ; $i ++)
		 {
			 if(preg_match($regex,$word[$i])||preg_match($regex2,$word[$i]))
			 {
				 $word_out= $word_out.$word[$i];
			 }
		 }
		 return $word_out;
		 
	 }
	 
	
	function word_proto( $word , $dbc )
	{
		
		$query = "select * from english_words where word_content = '$word'";
		$result =mysql_query($query,$dbc);
		if($row = mysql_fetch_array($result))
		{
			if($row['lib_id']>0)
			     return $row['lib_id'];
		}
		
		
		$lib_id = check_proto($word,$dbc);
		
		if($lib_id > 0)
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