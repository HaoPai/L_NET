<?php require_once('../struct_php/global.php'); ?>
<?php require_once('../function_php/lnet.php'); ?>




<?php

    $query = "select resource_id from english_resource where initialize_check = 0 order by resource_id";
    $RESULT = mysql_query($query,$dbc);
    while($row = mysql_fetch_array($RESULT)){

            $resource_id = $row['resource_id'];
            echo "resource_id = ".$resource_id. "\n";
            $query = "select file_name, resource_type, initialize_check from english_resource where resource_id = '$resource_id'";
            $result = mysql_query($query,$dbc);
            if(($row = mysql_fetch_array($result)) && $row['initialize_check'] == 0 ){
                $file_name = $row['file_name'];
                $resource_type = $row['resource_type'];
                $query = "select sentance_id from english_sentances where resource_id = '$resource_id'";
                $result = mysql_query($query,$dbc);
                while($row = mysql_fetch_array($result)){
                    $sentance_id = $row['sentance_id'];
                    $query = "delete from sentance_has_words where sentance_id = '$sentance_id'";
                    mysql_query($query);
                }
                $query = "delete from english_sentances where resource_id = '$resource_id'";
                mysql_query($query);
                $file_path = '../files/'.$file_name;
                $fp = fopen($file_path,'r');
                $content = fread($fp,filesize($file_path));
                $content = trim_content($content,$resource_type);
                //$content = pre_edit($content);
                $content = str_replace("'","\'",$content);
                if($resource_type == 1 || $resource_type == 4 || $resource_type == 6 || $resource_type == 7 || $resource_type == 8 ){
                    $sentances = get_sentances($content);
                    //print_r($sentances);
                }else if($resource_type == 3){
                    $sentances = get_lines($content);
                    //print_r($sentances);
                }else if($resource_type == 9||$resource_type == 10){
                    $subtitles = get_subtitles($content);
                    $len = count($subtitles);
                    for($i=0;$i<$len;$i++){
                        $index = $i + 1;
                        $sentance = $subtitles[$i]['sentance'];
                        $start = $subtitles[$i]['start'];
                        $end = $subtitles[$i]['end'];
                        $query = "insert into english_sentances (sentance_index, sentance_content, resource_id, audio_begin,audio_stop)".
                                 " values ('$index','$sentance','$resource_id','$start','$end') ";
                        //echo $query;
                        mysql_query($query);
                    }
                    //print_r($subtitles);
                }
               

                $len = count($sentances);
                for($i=0;$i<$len;$i++){
                    $index = $i + 1;
                    $sentance = $sentances[$i];
                    $query = "insert into english_sentances (sentance_index, sentance_content, resource_id )".
                             " values ('$index','$sentance','$resource_id') ";
                    mysql_query($query);
                }

                $query = "select sentance_id, sentance_content from english_sentances where resource_id = '$resource_id'";
                $result = mysql_query($query,$dbc);
                while($row = mysql_fetch_array($result)){
                    $sentance = $row['sentance_content'];
                    $sentance = strtolower($sentance);
                    $sentance_id = $row['sentance_id'];
                    $pattern = "/[a-z]+/i";
                    preg_match_all($pattern, $sentance, $arry);
                    foreach ($arry[0] as $word) {
                        $id = quick_proto($word,$dbc);
                        $query = "select rel_id from sentance_has_words where word_content = '$word' and lib_id = '$id' and sentance_id = '$sentance_id'";
                        $r2 = mysql_query($query,$dbc);
                        if(mysql_num_rows($r2)==0){
                            $query = "insert into sentance_has_words (word_content, lib_id , sentance_id ) ".
                                     " values ('$word','$id','$sentance_id') ";
                            mysql_query($query);
                        }
                    }
                    
                }



                $query = "select sentance_id from english_sentances where resource_id = '$resource_id' ";
                $result = mysql_query($query,$dbc);
                while($row = mysql_fetch_array($result)){
                    $sentance_id = $row['sentance_id'];
                    $query = "select rel_id from sentance_has_words where sentance_id = '$sentance_id'";
                    $r2 = mysql_query($query,$dbc);
                    $num = mysql_num_rows($r2);
                    $query = "update english_sentances set word_count = '$num' where sentance_id = '$sentance_id'";
                    mysql_query($query);
                }

                $query = "select rel_id from sentance_has_words inner join english_sentances on ".
                         " sentance_has_words.sentance_id = english_sentances.sentance_id ".
                         " where english_sentances.resource_id = '$resource_id' ".
                         " group by sentance_has_words.lib_id ";
                $result = mysql_query($query,$dbc);
                $num = mysql_num_rows($result);
                $query = "update english_resource set word_count = '$num', initialize_check = 1 where resource_id = '$resource_id'";
                mysql_query($query,$dbc);
            }
    }
            

       
        
       
       
?>



<?php
   mysql_close($dbc);
?>

<?php

    function trim_content($content,$resource_type){
        $content = preg_replace('/[^(\x0A-\x7F)]*/',"", $content);
        $content = preg_replace("/\[[^\]]+\]/",' ',$content);
        $content = preg_replace("/\([^\)]+\)/",' ',$content);
        $content = preg_replace("/<[^>]+>/",' ',$content);
        if($resource_type != 9 && $resource_type!=10)
            $content = preg_replace("/\n[^a-zA-Z]+\n/","\n",$content);
        $content = str_replace("..."," ",$content);
        $content = str_replace("Mr.","Mr",$content);
        $content = str_replace("Dr.","Dr",$content);
        $content = preg_replace('/ +/',' ',$content);
        //echo $content;
        return $content;
    }

    function pre_edit($content)
    {
        $str = '';
        $start = 0;
        $max = strlen($content);
        while($start < $max){
            $line = get_a_line($content,$start,$max);
            if(preg_match("/^[A-Z0-9,\\' ]+$/", $line)){
                $str .= "\n".$line.':';
            }else $str .= ' '.$line;
        }
        return $str;

    }
    function get_subtitles(&$str){
        
        $lines = array();
        $len = strlen($str);
        $m = 0;
        while($m<$len){
            $line = get_a_line($str,$m,$len);
            if(strlen($line)>0)
                $lines[] = $line;
        }
        $state = 0;//0，初始状态，1，发现标号，2，发现时间，3，整理台词
        $subtitles = array();
        $subtitle = array();
        $len = count($lines);
        for($i=0;$i<$len;$i++){
            if(is_numeric($lines[$i])){
                if($state == 3)  $subtitles[] = $subtitle;
                $subtitle = array();
                $state= 1;
                continue;
            }

            if($state == 1){
                $matches = [];
                preg_match_all('/[0-9]+/',$lines[$i],$matches);
                if(count($matches[0])==8){
                    $subtitle['start'] = $matches[0][0]*3600+$matches[0][1]*60+$matches[0][2];
                    $subtitle['end'] = $matches[0][4]*3600+$matches[0][5]*60+$matches[0][6];
                    $state = 2;
                    //print_r($subtitle);
                }
                else{
                    $subtitle = array();
                    $state = 0;
                } 
                continue;
                
            }

            if($state == 2 || $state == 3){
                $subtitle['sentance'] .= ' '.$lines[$i];
                $state = 3;
            }
        }
        //print_r($subtitles);
        return $subtitles;
    }


    function get_a_line(&$str, &$start,$max){
        while($start<$max&&is_line_end($str[$start])) $start ++;
        $end = $start;
        while($end <$max && !is_line_end($str[$end])) $end ++;
        $line = substr($str, $start,$end-$start);
        $start = $end + 1;
        return $line;
    }

    function get_lines($str){
        $start = false;
        $find_colon = false;
        $sentances = array();
        $sentance = '';
        $len = strlen($str);
        for($i=0;$i<$len;$i++){
            $char = $str[$i];
            if($str[$i] == ':') $find_colon = true;
            if($start){
                if($char != "\r" && $char != "\n"){
                    $sentance .= $str[$i];
                }else{
                    if($find_colon){
                        $sentance .= $str[$i];
                        $sentances[] = $sentance;
                        $sentance = '';
                        $start = false;
                    }
                }
            }else{
                if(!is_capital($str[$i])) continue;
                else {
                    $start = true;
                    $sentance = $str[$i];
                    $find_colon = false;
                }
            }
        }
        if($sentance)
             $sentances[] = $sentance;
        return $sentances;

    }
    function get_sentances($str){
        $start = false;
        $in_space = false;
        $sentances = array();
        $len = strlen($str);
        for($i=0;$i < $len; $i++){
            if($str[$i]>=128){
                if(!$in_space && $start){
                    $sentance .= ' ';
                    $in_space = true;
                }
                continue;
            }
            if($start){
                if(is_end($str[$i])){
                    $sentance .= $str[$i];
                    $sentances[] = $sentance;
                    $sentance = "";
                    $start = false;
                    $in_space = false;
                    continue;
                }

                if(is_space($str[$i])|| is_line_end($str[$i])){
                    if($in_space) continue;
                    else $in_space = true;
                }
                else{
                    $in_space = false;
                }
                if(is_line_end($str[$i]))
                    $sentance .= ' ';
                else
                    $sentance .= $str[$i];


            }else{
                if(!is_capital($str[$i])) continue;
                else {
                    $start = true;
                    $sentance .= $str[$i];
                }
            }
        }
        if($sentance)
             $sentances[] = $sentance;
        return $sentances;
    }
    function is_line_end($char){
        if($char=="\n"|| $char == "\r"|| $char == "\r\n" || $char == "\n\r"  )
            return true;
        else
            return false;
    }
    function is_space($char){
        if($char==' '|| $char == "\t" )
            return true;
        else
            return false;
    }

    function is_end($char){
        if($char=='.'|| $char == "?" || $char == "!")
            return true;
        else
            return false;
    }
    function is_capital($char){
        if($char>='A'&& $char <= 'Z')
            return true;
        else 
            return false;
    }

    function get_sentance_audio($sentance_id , $dbc )
    {
           $query = "select english_resource.audio_name,english_sentances.audio_begin,english_sentances.audio_stop,english_sentances.sentance_audio_name ".
           "  from english_sentances inner join english_resource ". 
           "   on ( english_sentances.resource_id = english_resource.resource_id ) ".
           "   where english_sentances.sentance_id = '$sentance_id'";
           $result = mysql_query($query,$dbc);
           if($row = mysql_fetch_array($result))
           {
               $audio_path = 'audio/'.$row['audio_name'];
               $audio_start = $row['audio_begin'];
               $audio_end = $row['audio_stop'];
               $audio_name = '';
               if($row['sentance_audio_name'])
                    $audio_name = 'AF/audio/english_sentances/'.$row['sentance_audio_name'];
               echo '{"audio_path":"'.$audio_path.'","audio_start":"'.$audio_start.'","audio_end":"'.$audio_end.'","audio_name":"'.$audio_name.'"}';
           }
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
        if($row = mysql_fetch_array($result)){
            return $row['word_id'];
        }
        else return 0;
     }





    function set_album_cover($album_id,$dbc){
         $query = "select * from photos where album_id = '$album_id'";
         $result = mysql_query($query,$dbc);
         if($row = mysql_fetch_array($result))
         {
             $photo_id = $row['photo_id'];
             $query = "select * from albums where album_id = '$album_id'";
             $result2 = mysql_query($query,$dbc);
             $row2 = mysql_fetch_array($result2);
             if($row2['album_cover_id']==444)
             {
                 $query = "update albums set album_cover_id = '$photo_id' where album_id = '$album_id'";
                 mysql_query($query,$dbc);
             }
             else
             {
                 $cover_id = $row2['album_cover_id'];
                 $query = "select * from photos where photo_id = '$cover_id' and album_id = '$album_id'";
                 $result = $mysql_query($query,$dbc);
                 if(mysql_num_rows($result)==0) 
                 {
                     $query = "update albums set album_cover_id = '$photo_id' where album_id = '$album_id'";
                     mysql_query($query,$dbc);
                 }
             }
         }
         else
         {
             $query = "update albums set album_cover_id = '444' where album_id = '$album_id'";
             mysql_query($query,$dbc);
         }
     }
   function build_mistake_query($mistake)
   {
       switch($mistake['mistake_type'])
       {
           case 0 :
               $query = "select * from english_mistakes where mistake_type = 0".
                        " and mistake_origin = '".$mistake['mistake_origin'].
                        "' and mistake_content = '".$mistake['mistake_content']."'";
                break;
            case 1 :
                $query = "select * from english_mistakes where mistake_type = 1".
                        " and mistake_content = '".$mistake['mistake_content']."'";
                break;
            case 2 :
                 $query = "select * from english_mistakes where mistake_type = 2".
                        " and mistake_origin = '".$mistake['mistake_origin']."'";
                 break;
        }
        return $query;
       
    }
    
   
    function add_mistake_log($mistake_id , $sentance_id,$dbc)
    {
              $date_now = get_date();
              $query ="select * from english_mistakes_log where mistake_id = '$mistake_id'".
              " and sentance_id = '$sentance_id' and log_date = '$date_now' ";
              $result = mysql_query($query,$dbc);
              if(mysql_num_rows($result)==0)
              {
                    $query = "insert into english_mistakes_log ( mistake_id , sentance_id , log_date)".
                    " values('$mistake_id','$sentance_id',now())";
                    mysql_query($query,$dbc);
              }
    }
    function add_mistake($mistake,$dbc)
    {
        $mistake_type = $mistake['mistake_type'];
        $mistake_origin = $mistake['mistake_origin'];
        $mistake_content= $mistake['mistake_content'];
        $query = build_mistake_query($mistake);
        $result = mysql_query($query,$dbc);
        if($row=mysql_fetch_array($result))
        {
            return $row['mistake_id'];
            
            
        }
        else
        {
            $query = "insert into english_mistakes (mistake_type , mistake_origin , mistake_content)".
            " values ('$mistake_type','$mistake_origin','$mistake_content')";
            mysql_query($query,$dbc);
            $query = build_mistake_query($mistake);
            $result = mysql_query($query,$dbc);
            $row=mysql_fetch_array($result);
            return $row['mistake_id'];
            
        }
                        
    }
      
?>