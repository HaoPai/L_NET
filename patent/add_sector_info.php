<?php
    $dbc = mysql_connect("localhost:3310","root","hunan2010");
	mysql_select_db("patent",$dbc);
	mysql_query('SET NAMES UTF8');
    date_default_timezone_set("Asia/Shanghai");

    $query = "select  stock_code  from   patent   group by stock_code order by stock_code ";
    $result = mysql_query($query,$dbc);
    while($row = mysql_fetch_array($result)){
        $stock_code = $row['stock_code'];
        $query = "select sector_code , sector_name from companies where  stock_code = '$stock_code'  group by  stock_code ";
        $R =  mysql_query($query,$dbc);
        if(mysql_num_rows($R) == 1){
            $row2 = mysql_fetch_array($R);
            $sector_code = $row2['sector_code'];
            $sector_name = $row2['sector_name'];
            $query = "update patent set sector_code =  '$sector_code' , sector_name = '$sector_name' where stock_code = '$stock_code'";
            echo '<p>'.$query.'</p>';
            mysql_query($query,$dbc);
        }
    }
?>