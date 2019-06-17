<?php 
    session_start();
	$name = ini_get("session.upload_progress.name");
	$response = array();
	if( (isset($_GET['target']))&&(isset($_GET[$name])))
	{
		$target = $_GET['target'];
		$key = ini_get("session.upload_progress.prefix").$_GET[$name];
		if(isset($_SESSION[$target]))
		{
			if($_SESSION[$target][0])
			{
				$response['status'] = 'ok';
				$response['path'] = $_SESSION[$target][2];
				unset($_SESSION[$target]);
			}
			else
			{
				$response['status'] = 'mistake';
				$response['error'] = $_SESSION[$target][1];
				unset($_SESSION[$target]);
			}
		}
		else
		{
			
			if(isset($_SESSION[$key]))
			{
				$processed = $_SESSION[$key]['bytes_processed'];
				$total = $_SESSION[$key]['content_length'];
				$rate = ceil($processed/$total * 100);
				$response['status'] = 'loading';
				$response['stage'] = $rate;
				
			}
			else
			{
				$response['status'] = 'mistake';
		        $response['error'] = '不能处理结果！';
				
			}
		}
	}
	else
	{
		$response['status'] = 'mistake';
		$response['error'] = '参数传递有误！';
	}
	echo json_encode($response);

?>