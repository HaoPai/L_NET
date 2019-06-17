<?php 
    $dbc =mysql_connect("localhost:3310","root","hunan2010");
	mysql_select_db("haobai_web",$dbc);
    if(isset($_GET['data']))
	{
		$data = $_GET['data'];
		if(($data=='photos')&&isset($_GET['album_id']))
		{
			$album_id = $_GET['album_id'];
			$query ="select * from photos where album_id = '$album_id' order by photo_id";
			echo '[';
			$result= mysql_query($query,$dbc);
			$num = mysql_num_rows($result);
			
			for($i=1;$i<$num;$i++)
			{
				
				$row =mysql_fetch_array($result);
				$size = @getimagesize($row['photo_path']);
				echo '{"photo_id":"'.$row['photo_id'].'","photo_path":"'.$row['photo_path'];
				echo  '","width":"'.$size[0].'","height":"'.$size[1].'"} , ';
			}
			$row =mysql_fetch_array($result);
			$size = @getimagesize($row['photo_path']);
			echo '{"photo_id":"'.$row['photo_id'].'","photo_path":"'.$row['photo_path'];
			echo  '","width":"'.$size[0].'","height":"'.$size[1].'"} ] ';
			
		}
	}
	
?>