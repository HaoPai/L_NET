<?php require_once('../struct_php/global.php'); ?>
<?php require_once('../function_php/lnet.php'); ?>




<?php

   for($resource_id = 732; $resource_id < 744; $resource_id ++){
        $query = "select sentance_id from english_sentances where resource_id = '$resource_id'";
        $result = mysql_query($query,$dbc);
        while($row = mysql_fetch_array($result)){
            $sentance_id = $row['sentance_id'];
            $query = "delete from sentance_has_words where sentance_id = '$sentance_id'";
            echo $query."\n";
            mysql_query($query);
        }
        $query = "delete from english_sentances where resource_id = '$resource_id'";
        echo $query."\n";
        mysql_query($query);
        $query = "delete from english_resource where resource_id = '$resource_id'";
        echo $query."\n";
        mysql_query($query);
    }
            
       
?>



<?php
   mysql_close($dbc);
?>

