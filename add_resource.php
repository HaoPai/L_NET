<?php
	  //include "library/getid3/getid3.php";
	  require_once('module/getid3/getid3.php');
      $dbc = mysql_connect("localhost:3306","root","hunan2010");
	  mysql_select_db("l_net",$dbc);
	  mysql_query('SET NAMES UTF8');
      $collection_id = 0;
      $type_id = 0;	  
	  $show_warning = false;
	  $warning = "";
	  if(isset($_POST['add_resource'])){
	  	$show_warning = true;
	  	$resource_name = $_POST['resource_name'];
	  	$collection_id = $_POST['collection_id'];
	  	$type_id = $_POST['type_id'];
	  	$file_name = $_POST['file_name'];
	  	$audio_name = $_POST['audio_name'];
	  	$file_path = 'files/'.$file_name;
	  	$audio_path = 'audio/'.$audio_name;
	  	if(is_file($file_path)&&is_file($audio_path)){
	  		$query = "insert into english_resource ".
	  		          " (resource_name, resource_type, resource_book_id, file_name, audio_name )".
	  		          " values ('$resource_name','$type_id','$collection_id','$file_name','$audio_name')";
	  		mysql_query($query);
	  		$warning = '<p class = "success">添加成功</p>';
	  	}
	  	else
	  	{
	  		$warning = '<p class = "failed">添加失败</p>';
	  	}
	  }
	  
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
			.initial { display:none; }
			span { width:100px;  margin:5px 20px; border-bottom: 1px solid #ccc; display:inline-block; }
			input { width: 300px; font-size:1em; border:none; outline:none;}
			
			 #english_nav { padding:5px 0px;margin:10px 0px 50px 0px;border-bottom:1px solid #ccc ;}
		   #english_nav li { padding:5px 10px; margin:0px 40px 0px 0px; display:inline; }
		   
		   #english_nav a,td a { border:none ; color:#666; font-size:1.2em; text-decoration:none;}
		   form select {font-size: 1em,padding:10px;height: 20px; }
		   .success{background: blue;color: #fff;}
		   .failed {background-color: red; color: #fff;}
	   </style>
</head>
<body>
    <?php require_once('struct_php/english_sys_nav.php'); ?>
    <h2>添加资源</h2>
    <?php
    	if($show_warning){
    		echo $warning;
    	}
    ?>
    <form method="post" action="add_resource.php">
    	<table>
    		<tr>
    			<td><label>资源名：</label></td><td><input name="resource_name" id="resource_name" type="text" required="required"></td>
    		</tr>
    		<tr> 
    			<td><label>资源集：</label></td><td>
    				<select id = "collection_id" name="collection_id">
    					<?php
    						$query = "select * from english_collection order by collection_id desc";
    						$result = mysql_query($query,$dbc);
    						while($row = mysql_fetch_array($result)){
                                if($collection_id == $row['collection_id'])
    							     echo '<option selected = "selected" value = "'.$row['collection_id'].'">'.$row['collection_name'].'</option>';
                                else
                                     echo '<option value = "'.$row['collection_id'].'">'.$row['collection_name'].'</option>';
    						}
    					?>
    				</select>
    			</td>
    		</tr>
    		<tr>
    			<td><label>资源类型：</label></td>
    			<td>
    				<select id="type_id" name="type_id">
    					<?php
    						$query = "select * from english_resource_type";
    						$result = mysql_query($query,$dbc);
    						while($row = mysql_fetch_array($result)){
                                if($type_id == $row['type_id'])
    							     echo '<option selected = "selected" value = "'.$row['type_id'].'">'.$row['type_name'].'</option>';
                                 else 
                                    echo '<option value = "'.$row['type_id'].'">'.$row['type_name'].'</option>';
    						}
    					?>
    				</select>
    			</td>
    		</tr>
    		<tr>
    			<td><label>文本路径：</label></td><td><input id="file_name" name="file_name" type="text" required="required" name=""></td>
    		</tr>
    		<tr>
    			<td><label>音频路径：</label></td><td><input id="audio_name" name="audio_name" type="text" required="required" name=""></td>
    		</tr>

    		<tr><td></td><td><button name="add_resource" id="add_resource">添加</button></td></tr>
    	
        </table>
    </form>
</body>


<?php
	mysql_close($dbc);
?>