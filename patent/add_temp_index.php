<?php
    $dbc = mysql_connect("localhost:3310","root","hunan2010");
	mysql_select_db("patent",$dbc);
	mysql_query('SET NAMES UTF8');
    date_default_timezone_set("Asia/Shanghai");

    $query = "select  sector_code   from   patent   group by sector_code order by sector_code ";
    $result = mysql_query($query,$dbc);
    $num = 1;
    while($row = mysql_fetch_array($result)){
        $sector_code = $row['sector_code'];
        $query = "select  patent_id from patent where  sector_code = '$sector_code'  order by key_num desc  limit 30 ";
        $R =  mysql_query($query,$dbc);
        while($row2 = mysql_fetch_array($R)){
            $patent_id = $row2['patent_id'];
            $query = "update patent set temp_index  =  '$num'  where patent_id = '$patent_id'";
            echo '<p>'.$query.'</p>';
            mysql_query($query,$dbc);
            $num ++;
        }
        
    }
?>