<?php require_once('data_process_php/authorize.php'); ?>
<?php session_start(); ?>
<?php require_once('function_php/lnet.php'); ?>
<?php require_once('struct_php/global.php'); ?>

<?php 


$query = "select word_id from english_words_lib where word_id   order by word_id ";
$result = mysql_query($query,$dbc);
while($row = mysql_fetch_array($result))
{
  $lib_id = $row['word_id'];
  $query = "select sentance_id from sentance_has_words where lib_id = '$lib_id'";
  $re2 = mysql_query($query,$dbc);
  $num = mysql_num_rows($re2);
  $query = "update english_words_lib set reference_count = '$num' where word_id = '$lib_id'";
  mysql_query($query,$dbc);
  echo '<p>'.$query.'</p>';
}

    // $query = "select word_content from words_web_search where word_content > 'P' and  bing_tag = '1'";
    // $result = mysql_query($query,$dbc);
    // while($row = mysql_fetch_array($result)){
    //     $word_content = $row['word_content'];
    //     $lib_id = quick_proto($word_content,$dbc);
    //     $query = "update sentance_has_words set lib_id = '$lib_id' where word_content = '$word_content'";
    //     mysql_query($query,$dbc);
    //     echo '<p>'.$query.'</p>';
    // }
    
    
    // $query = "select bing_return from words_web_search  where bing_tag = '1' group by bing_return";
    // $result = mysql_query($query,$dbc);
    // while ($row = mysql_fetch_array($result)) {
    //     $word = $row['bing_return'];
    //     $word_lower = strtolower($word);
    //     $query = "select word_id from english_words_lib where lower(word_content) = '$word_lower'";
    //     $R = mysql_query($query,$dbc);
    //     if(mysql_num_rows($R)==0){
    //         $query = "insert into english_words_lib (word_content) values ('$word')";
    //         mysql_query($query,$dbc);
    //         $query = "select word_id from english_words_lib where word_content = '$word'";
    //         $R2 = mysql_query($query,$dbc);
    //         $row = mysql_fetch_array($R2);
    //         $word_id = $row['word_id'];
    //         $query = "insert into word_primary_collection (collection_base,collection_base_id,collection_content,collection_count) ".
    //                   " values ('$word','$word_id','[$word]','1')";
    //         mysql_query($query,$dbc);
    //         $query = "select collection_id from word_primary_collection where collection_base_id = '$word_id'";
    //         $R2 = mysql_query($query,$dbc);
    //         $row = mysql_fetch_array($R2);
    //         $collection_id = $row['collection_id'];
    //         $query = "insert into word_in_collection (collection_id,word_id) values ('$collection_id','$word_id')";
    //         mysql_query($query,$dbc);
    //         echo '<p>'.$word.'</p>';
    //     }
    // }



      // $query = "select word_id from english_words_lib where word_id > 35990  order by word_id ";
      // $result = mysql_query($query,$dbc);
      // while($row = mysql_fetch_array($result))
      // {
      //     $lib_id = $row['word_id'];
      //     $query = "select sentance_id from sentance_has_words where lib_id = '$lib_id'";
      //     $re2 = mysql_query($query,$dbc);
      //     $num = mysql_num_rows($re2);
      //     $query = "update english_words_lib set reference_count = '$num' where word_id = '$lib_id'";
      //     mysql_query($query,$dbc);
      //     echo '<p>'.$query.'</p>';
      // }
    

    // $query = "select sentance_id   from english_sentances order by sentance_id ";
    // $result = mysql_query($query,$dbc);
    // while($row=mysql_fetch_array($result)){
    //     $id = $row['sentance_id'];
    //     $query = "select english_words_lib.word_frequency from sentance_has_words inner join english_words_lib "."
    //               on (sentance_has_words.lib_id = english_words_lib.word_id) where sentance_has_words.sentance_id = '$id' ".
    //              " and english_words_lib.name_tag = 0  ";
    //     //echo '<p>'.$query.'</p>';
    //     $r2 = mysql_query($query,$dbc);
    //     $total = 0;
    //     $c = 0;
    //     while($row2 = mysql_fetch_array($r2)){
    //         $total += $row2['word_frequency'];
    //         $c ++;
    //     }
    //     $difficulty = (int) ($total / $c );
    //     $query = "update english_sentances set difficulty = '$difficulty' where sentance_id = '$id'";
    //     mysql_query($query,$dbc);
    //     echo '<p>'.$query.'</p>';
    // }


    // $query = "select sentance_id,word_count from english_sentances ";
    // $result = mysql_query($query,$dbc);
    // while($row = mysql_fetch_array($result)){
    //     $id = $row['sentance_id'];
    //     $word_count = $row['word_count'];
    //     $diff = abs($word_count - 40);
    //     $query = "update english_sentances set word_count_diff = '$diff' where sentance_id = '$id'";
    //     mysql_query($query,$dbc);
    //     echo '<p>'.$query.'</p>';
    // }


// for($index = 1; $index <= 12; $index ++){
//     $file_index = ($index < 10)? "0".$index : "".$index;
//     $resource_name = "神秘博士第9季第".$file_index."集";
//     $audio_name = "Doctor.Who.S09E".$file_index.".mp3";
//     $file_name = "Doctor.Who.S09E".$file_index.".txt";
//     $query = "insert into english_resource (resource_name,resource_type,resource_book_id,file_name,audio_name) ".
//             " values('$resource_name','3','130','$file_name','$audio_name')";
//     mysql_query($query,$dbc);
//     echo '<p>'.$query.'</p>';

// }

    // $fd = fopen("GRE.txt","r");
    // $content = fread($fd,filesize("GRE.txt"));
    // $pattern = "/[a-z]+/i";
    // preg_match_all($pattern, $content, $arry);
    // foreach ($arry[0] as $word) {
    //     $query = "select word_id from english_words_lib where word_content = '$word' limit 1";
    //     $result = mysql_query($query,$dbc);
    //     if(!($row = mysql_fetch_array($result))){
    //         $query = "insert into english_words_lib (word_content) values ('$word')";
    //         mysql_query($query,$dbc);
    //         $query = "select word_id from english_words_lib where word_content = '$word'";
    //         $result = mysql_query($query,$dbc);
    //         $row = mysql_fetch_array($result);
    //         $word_id = $row['word_id'];
    //         $query = "insert into word_primary_collection (collection_base,collection_base_id,collection_content,collection_count) ".
    //                   " values ('$word','$word_id','[$word]','1')";
    //         mysql_query($query,$dbc);
    //         $query = "select collection_id from word_primary_collection where collection_base_id = '$word_id'";
    //         $result = mysql_query($query,$dbc);
    //         $row = mysql_fetch_array($result);
    //         $collection_id = $row['collection_id'];
    //         $query = "insert into word_in_collection (collection_id,word_id) values ('$collection_id','$word_id')";
    //         mysql_query($query,$dbc);
    //         echo '<p>'.$word.'</p>';
    //      }  
    // }

?>







<?php
    function check_word($word,&$out){
        $len = strlen($word);
        $r = false;
        $pre = $word[2];
        for($i=3;$i<$len;$i++){
            if($word[$i] ==$pre){
                if($word[$len-2] == 'e' && $word[$len-1] == 'd'&& $i == $len -3){
                     $r = true;
                     break;
                } 
                if($word[$len-2] == 'e' && $word[$len-1] == 'n'&& $i == $len -3){
                     $r = true;
                     break;
                } 

                if($word[$len-3] == 'i' && $word[$len-2] == 'n' && $word[$len-1] == 'g' && $i == $len -4){
                     $r = true;
                     break;
                }     
            }
            $pre = $word[$i];
        }
        $out = substr($word,0,$i);
        return $r;
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
                echo $query;
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
            else if($word[$len-4] == y){
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


    mysql_close($dbc);
?>