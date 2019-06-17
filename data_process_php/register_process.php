<?php require_once('../struct_php/global.php'); ?>

<?php
    if(isset($_POST['add_user']))
	{
		$user_name_add = $_POST['user_name'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$query = "select * from users where user_name = '$user_name_add'";
		$result = mysql_query($query,$dbc);
		if(mysql_num_rows($result))
		{
			echo '{"status": 1 }';
		}
		else
		{
			$query = "insert into users ( portrait_id , user_name , email, password , join_date)".
			"values ('4' , '$user_name_add','$email',SHA('$password'),NOW())";
			mysql_query($query,$dbc);
			$query = "select * from users where user_name = '$user_name_add'";
			$result = mysql_query($query,$dbc);
			$row = mysql_fetch_array($result);
			$user_id = $row['user_id'];
			$query = "insert into user_info ( user_id ) values ('$user_id')";
			mysql_query($query,$dbc);
			echo '{"status": 0 }';
		}
	}
     
?>