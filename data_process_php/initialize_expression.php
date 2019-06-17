<?php require_once('../struct_php/global.php'); ?>
<?php require_once('../function_php/lnet.php'); ?>


<?php

    $query = "select expression_content,expression_hash from english_expression";
    $result = mysql_query($query,$dbc);
    while($row = mysql_fetch_array($result)){
        $new_hash = create_hash($row['expression_content'],$dbc);
        if($new_hash!=$row['expression_hash'])
            echo $row['expression_content']." ".$row['expression_hash']." ".$new_hash."\n";
    }
?>


<?php 
    function create_hash($expression_content,$dbc){
        $success = true;
        $expression_content = preg_replace("/[^a-z\.\-\/]/"," ",$expression_content);
        $expression_content = preg_replace("/\s+/"," ",$expression_content);
        $expression_content = trim($expression_content);
        $temp = str_replace("sb", "", $expression_content);
        $temp = str_replace("sth", "", $temp);
        preg_match_all("/[a-z]+/", $temp,$result);
        $ids = array();
        if(count($result[0])==0) $success = false;
        foreach ($result[0] as $key => $raw_word) {
            $id = quick_proto($raw_word,$dbc);
            if($id > 0){
                $ids[] = get_collection($id,$dbc);
            } 
            else {
                $success = false;
                break;
            }
        }

        $min_ids = array();
        $hash = "";
        $words = "";
        $initial = true;
        if($success){
            for($i=0;$i<count($ids);$i++){
                $temp = $ids[$i][0];
                for($j=1;$j<count($ids[$i]);$j++){
                    if($ids[$i][$j] < $temp) $temp = $ids[$i][$j];
                }
                $min_ids[] = $temp;
            }
            sort($min_ids);
            
            for($i=0;$i<count($min_ids);$i++){
                $hash  .= $min_ids[$i];
            }
        }

        if($success){
           return $hash;
        }
        else return "000000";
    }

     function get_collection($id,$dbc){
        $query = "select collection_id from word_in_collection where word_id = '$id'";
        $result = mysql_query($query,$dbc);
        $row = mysql_fetch_array($result);
        $collection_id = $row['collection_id'];
        $query = "select word_id from word_in_collection where collection_id = '$collection_id'";
        $result = mysql_query($query,$dbc);
        $item = array();
        while($row = mysql_fetch_array($result)){

            $item[] = $row['word_id'];
        }
        return $item;
    }


    function quick_proto($word,$dbc){
        $word = strtolower($word);
        if(($id = check_proto($word,$dbc))>0) return $id;
        $query = "select lib_id from english_words where lower(word_content) = '$word' and lib_id != 0";
        $result = mysql_query($query,$dbc);
        if($row = mysql_fetch_array($result)){
            return $row['lib_id'];
        }
        else {
            $id = word_proto($word,$dbc);
            if($id > 0){
                $query = "insert into english_words ( word_content, lib_id) values ('$word','$id')";
                mysql_query($query,$dbc);
            }
            return $id;
        }
     }


     function word_proto($word,$dbc){
        $word = strtolower($word);
        if(($id = check_proto($word,$dbc))>0) return $id;

        $query = "select lib_id from english_words where lower(word_content) = '$word' and lib_id <> 0";
        $result = mysql_query($query,$dbc);
        if($row = mysql_fetch_array($result)){
            return $row['lib_id'];
        }
        $id = 0;
        $len = strlen($word);
        if($len>2 && $word[$len-1] == 's'){
            $temp = substr($word, 0, $len-1);
            if(($id = check_proto($temp,$dbc))>0) return $id;
            else if($word[$len-2] == 'e'){
                $temp = substr($word, 0, $len-2);
                if(($id = check_proto($temp,$dbc))>0) return $id;
                else if($word[$len -3] == 'i'){
                    $temp = substr($word, 0, $len-3);
                    $temp .= 'y';
                    if(($id = check_proto($temp,$dbc))>0) return $id;
                }
            }
        }
        else if($len > 2 && $word[$len -1] == 'd'){
            $temp = substr($word, 0, $len-1);
            if(($id = check_proto($temp,$dbc))>0) return $id;
            else if($word[$len -2] == 'e'){
                $temp = substr($word, 0, $len-2);
                if(($id = check_proto($temp,$dbc))>0) return $id;
                else if($word[$len -3] == 'i'){
                    $temp = substr($word, 0, $len-3);
                    $temp .= 'y';
                    if(($id = check_proto($temp,$dbc))>0) return $id;
                }
            }
        }
        else if($len > 4 && $word[$len-3]== 'i' && $word[$len-2] == 'n' && $word[$len-1] == 'g'){
            $temp = substr($word, 0, $len-3);
            if(($id = check_proto($temp,$dbc))>0) return $id;
            else if(($id = check_proto($temp . 'e',$dbc))>0) return $id;
            else if($word[$len-4] == 'y'){
                $temp = substr($word, 0,$len -4);
                $temp .= 'ie';
                if(($id = check_proto($temp,$dbc))>0) return $id;
            }
        }
        else if($len >2 && $word[$len -2] == 'l' && $word[$len-1] == 'y'){
        $temp = substr($word, 0, $len-2);
        if(($id = check_proto($temp,$dbc))>0) return $id;
        }

        return $id;
    }

    function check_proto($word,$dbc){
        $word = strtolower($word);
        $query = "select word_id from english_words_lib where lower(word_content) = '$word'";
        $result = mysql_query($query,$dbc);
        if($row = mysql_fetch_array($result)){
            return $row['word_id'];
        }
        else return 0;
     }


?>