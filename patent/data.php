<?php
    $dbc = mysql_connect("localhost:3310","root","hunan2010");
	mysql_select_db("patent",$dbc);
	mysql_query('SET NAMES UTF8');
    date_default_timezone_set("Asia/Shanghai");

    $logged = false;
    $user_id = 0;
    $user_name = "";
    if(isset($_COOKIE['user_name']) && isset($_COOKIE['user_id']))
    {
        $user_name = $_COOKIE['user_name'];
        $user_id= $_COOKIE['user_id'];
        $logged = true;
    }


 
    if(isset($_GET['get_patent'])&&$logged){
        $query =  "";
        $id = 0;
        $result ;
        if(isset($_GET['id'])){
            $id  = $_GET['id'];
            $query = "select  patent_id  from  patent where  patent_id = '$id' ";
        }else       $query = "select   patent_id  from  patent  ".  
                               "where  ( user_id =  '$user_id' or  user_id = '0' )  and  green_tag = '-1'  order by  user_id  desc , key_num desc  limit 1";
                             
        $result =   mysql_query($query,$dbc);    
        if($row = mysql_fetch_array($result)){
            $id = $row['patent_id'];

            $query = "update patent set user_id  =0 , user_name = ''   where   user_id = '$user_id' and  green_tag = '-1'  ";
            mysql_query($query,$dbc);
            $query = "update patent  set  user_id = '$user_id' , user_name =  '$user_name'   ".
                                "where  user_id = '0' and  patent_id =   '$id' and  green_tag = '-1' ";
            mysql_query($query,$dbc);

            $query = "select  patent_id , stock_code ,ap_company,patent_title,patent_abstract ,user_id,user_name,green_tag,check_time ,".
                              " sector_code, sector_name  from  patent where  patent_id = '$id' ";
            $result = mysql_query($query,$dbc);
            $row = mysql_fetch_array($result);

            $words = array();
            $query = "select keywords.word_content from  keywords ".
                                   "   inner join patent_has_word  on keywords.word_id  = patent_has_word.word_id  ".
                                     "   where  patent_has_word.patent_id  = '$id'";
            $result = mysql_query($query,$dbc);
            while($R = mysql_fetch_array($result)){
                $word = $R['word_content'];
                $words[] = $R['word_content'];
                $row['patent_abstract'] =  str_replace($word,'<span>'.$word.'</span>', $row['patent_abstract'] );
            }
            $row['keywords'] = $words;
            $row['your_id']  = $user_id;
           echo json_encode($row);
        }
    }

    if(isset($_GET['get_words'])){
        $query = "select  *  from keywords order by reference_count desc";
        $data = array();
         $result = mysql_query($query,$dbc);
         while($row = mysql_fetch_array($result)){
             $data[] = $row;
         }
         echo json_encode($data);
     }


     if(isset($_GET['get_all_patents'])){
         $page = $_GET['page'];
         $low = 30*($page -1);
        $query = "select patent_id, patent_title , ap_company, key_num,sector_code  from patent  order by  temp_index  limit  $low ,30";
        $data = array();
         $result = mysql_query($query,$dbc);
         while($row = mysql_fetch_array($result)){
             $data[] = $row;
         }
         echo json_encode($data);
     }



     if(isset($_GET['get_green_patents'])){
        $page = $_GET['page'];
        $low = 30*($page -1);
       $query = "select patent_id, patent_title , ap_company, key_num  from patent  order by  key_num desc  limit  $low ,30";
       $data = array();
        $result = mysql_query($query,$dbc);
        while($row = mysql_fetch_array($result)){
            $data[] = $row;
        }
        echo json_encode($data);
    }

    if(isset($_GET['log_in']) ){
        if(isset($_COOKIE['user_name']) && isset($_COOKIE['user_id']))
        {
            $user = array();
            $user['user_name'] = $_COOKIE['user_name'];
            $user['user_id'] = $_COOKIE['user_id'];
            echo json_encode($user);
        }
    }




    if(isset($_GET['add_word'])&&$logged){
        $word = $_GET['word'];
        $query = "select  * from keywords where word_content = '$word'";
        $result = mysql_query($query,$dbc);
        if(mysql_num_rows ($result) == 0){
            $query = "insert into  keywords  (word_content, reference_count ) values ('$word','0') ";
            mysql_query($query,$dbc);
            $query = "select  * from keywords where word_content = '$word'";
            $result = mysql_query($query,$dbc);
            $row = mysql_fetch_array($result);
            $word_id  = $row['word_id'];
            $query = "select patent_id from patent where  patent_abstract like '%$word%'  ".
                              " or patent_abstract like '$word%' or   patent_abstract like '%$word' ";
            $result = mysql_query($query,$dbc);
            $num =  mysql_num_rows($result);
            $query = "update  keywords set reference_count = '$num' where  word_id = '$word_id'";
            mysql_query($query,$dbc);
            while($row = mysql_fetch_array($result)){
                $patent_id  = $row['patent_id'];
                $query = "insert into  patent_has_word (patent_id, word_id ) values ('$patent_id','$word_id') ";
                 mysql_query($query,$dbc);
                 $query = "update patent set  key_num = key_num +1  where patent_id = '$patent_id'";
                 mysql_query($query,$dbc);
            }
        }else echo "重复添加关键词！";
    
     }

     if(isset($_GET['remove_word'])&&$logged){
        $id = $_GET['id'];
        $query = "select  patent_id from patent_has_word  where  word_id = '$id'";
        $result = mysql_query($query);
        while($row = mysql_fetch_array($result)){
            $patent_id  = $row['patent_id'];
            $query = "update patent set  key_num = key_num -1  where patent_id = '$patent_id'";
            mysql_query($query,$dbc);
        }
        $query = "delete from  patent_has_word where  word_id = '$id'";
        mysql_query($query,$dbc);
        $query = "delete from  keywords where  word_id = '$id'";
        mysql_query($query,$dbc);
    }

    if(isset($_GET['set_green_tag'])&&$logged){
        $patent_id = $_GET['patent_id'];
        $green_tag = $_GET['green_tag'];
        $query = "update  patent set  green_tag = '$green_tag' , user_id = '$user_id', user_name = '$user_name', check_time = now()  ".
                           "where patent_id  = '$patent_id'";
        echo $query;
        mysql_query($query,$dbc);
    }
    ?>