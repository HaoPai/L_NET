<?php

      $dbc = mysql_connect("localhost:3306","root","hunan2010");
	  mysql_select_db("l_net",$dbc);
	  $query = "select * from english_words order by word_content";
	  $result = mysql_query($query,$dbc);
	  while($row =mysql_fetch_array($result))
	  {
		  echo "<p>".$row['word_content']."</p>";
	  }
?>
