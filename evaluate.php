<?php require_once('struct_php/global.php'); ?>

<?php
      $query = "delete from album_evalueate ";
	  mysql_query($query,$dbc);
      $query = "select * from albums";
	  $result = mysql_query($query,$dbc);
	  while($row = mysql_fetch_array($result))
	  {
		  $album_id = $row['album_id'];
		  $query = "select * from photos where album_id = '$album_id'";
		  $result2= mysql_query($query,$dbc);
		  $number = mysql_num_rows($result2);
		  switch ($album_id)
		  {
			  default:
			     $album_comment_number = 0;
		  }
		  $album_comment_number = 0;
		  $album_primary_score = 100;
		  $album_final_score = $album_primary_score + $number + $album_comment_number*10;
		  $query = "insert into album_evaluate ( album_id , album_photo_number , album_comment_number ".
		  " , album_primary_score , album_final_score , evaluate_date ) values ".
		  " ( '$album_id','$number','$album_comment_number','$album_primary_score','$album_final_score', now() )";
		  mysql_query($query,$dbc);
	  }
?>

<?php
       mysql_close($dbc);
?>