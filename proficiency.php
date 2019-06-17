<?php

      $dbc = mysql_connect("localhost:3306","root","hunan2010");
      mysql_select_db("l_net",$dbc);
      mysql_query('SET NAMES UTF8');
      
?>
<!DOCTYPE html>
<html>
<head>
       <meta charset="utf-8"/>
       <style type="text/css">
            body { padding:50px;  }
            h4 { margin:30px 0px;}
            h2{ text-align: center; }
            table { width:  600px; margin: auto; border:1px solid #ccc; }
            table { padding: 0px 50px 50px; border-radius: 10px; }
            td { text-align: center; }
            button { width: 80px; padding: 5px 0px; margin: 0px 0px; font-size: 18px; border:  1px #ccc solid; background: #fff; border-radius: 8px; cursor: pointer; -webkit-user-select: none; }
            #audio { text-align: center; margin-bottom: 50px;line-height:  100px; }
            #word_td{padding: 100px 0px;}
            #control {height: 35px; cursor: pointer;display:inline-block; vertical-align: bottom; -webkit-user-select: none;  }
            table td div {  margin: 0px 5px 30px;}
            #word { display: inline-block; line-height: 70px; font-size: 70px; text-decoration:none;color: #333; font-weight: 300;}
            .missed_words { width:860px; }
            .initial { display:none; }
            span { width:100px;  margin:5px 20px; border-bottom: 1px solid #ccc; display:inline-block; }
            input { width: 200px; font-size:1em; border:none; outline:none;}
            
             #english_nav { padding:5px 0px;margin:10px 0px 50px 0px;border-bottom:1px solid #ccc ;}
           #english_nav li { padding:5px 10px; margin:0px 40px 0px 0px; display:inline; }
           
           #english_nav a { border:none ; color:#666; font-size:1.2em; text-decoration:none;}
           form select {font-size: 1em,padding:10px;height: 20px; }
           .success{background: blue;color: #fff;}
           .failed {background-color: red; color: #fff;}
           .knew{ background: rgb(40, 182, 157);color: #fff; }
           .uncertain{ background: #FF9900;color: #fff; }
           .alien{ background: #FF6666;color: #fff; }
           .next{float: right;width: 150px;}
           #set_test_list table  {
                font-size: 1.2em;
                padding: 30px 30px;
           }
           #set_test_list td{
                padding: 18px 0px;
           }
           #set_test_list input {
                border-bottom: 1px solid #ccc;padding: 0px 18px; font-size: 1em;
           }
           #set_test_list select {
                width: 240px; height: 40px; border: none;border-bottom: 1px solid #ccc;font-size: 0.9em;;padding: 0px 18px;
           }
           #set_test_list button {
                width: 100px;
           }
           @media only screen and (max-device-width: 600px) {
                table {
                    width: 900px; 
                    font-size: 100px;
                    padding: 100px 0px;
                }
                button {
                    font-size: 40px;
                    padding: 15px 0px;
                    margin: 300px 0px 30px;
                    border-radius: 15px;
                    width: 200px;
                }

                #word{
                    font-size: 120px;
                    font-family: "宋体";
                    font-weight: 300;
                }

                #control{
                    height: 80px;
                }

                table td div{
                    margin: 0px 20px;
                }

              
           }
       </style>
</head>
<body>
    <?php require_once('struct_php/english_sys_nav.php'); ?>
    <?php 
        if(isset($_POST['knew'])){
            $id =  $_POST['word_id'];
            $query = "update english_words_lib set word_proficiency = '3' , proficiency_tag = '1' ".
                     "where word_id = '$id' ";
            mysql_query($query,$dbc);
        }

         if(isset($_POST['uncertain'])){
           $id =  $_POST['word_id'];
            $query = "update english_words_lib set word_proficiency = '2' , proficiency_tag = '1' ".
                     "where word_id = '$id' ";
            mysql_query($query,$dbc);
        }

         if(isset($_POST['alien'])){
            $id =  $_POST['word_id'];
            $query = "update english_words_lib set word_proficiency = '1' , proficiency_tag = '1' ".
                     "where word_id = '$id' ";
            mysql_query($query,$dbc);
        }

        if(isset($_POST['next'])){
            $id =  $_POST['word_id'];
            $query = "update english_words_lib set  proficiency_tag = '1' ".
                     "where word_id = '$id' ";
            mysql_query($query,$dbc);
        }

        if(isset($_POST['name'])){
            $id =  $_POST['word_id'];
            $query = "update english_words_lib set name_tag = '1' , proficiency_tag = '1', word_proficiency = '0' ".
                     "where word_id = '$id' ";
            mysql_query($query,$dbc);
        }


        if(isset($_POST['set_test_list'])){
            $low = $_POST['low'];
            $hign = $_POST['hign'];
            $proficiency = $_POST['proficiency'];
            $query = "";
            if($proficiency >0) 
                $query = "update english_words_lib set proficiency_tag = 0 where ".
                         " word_frequency >= '$low' and word_frequency <= '$hign' ".
                         " and word_proficiency = '$proficiency' and reference_count > 0";
            else 
                $query = "update english_words_lib set proficiency_tag = 0 where ".
                         " word_frequency >= '$low' and word_frequency <= '$hign' ".
                         " and reference_count > 0";
            mysql_query($query,$dbc);
            //echo '<p>'.$query.'</p>';
        }


        $query = " select word_id , word_content , audio_path, word_frequency ".
                  "from english_words_lib where proficiency_tag = 0 and valid_check = 1 and name_tag = 0 order by word_frequency ";
        //echo $query;
        $result = mysql_query($query,$dbc);
        $num = mysql_num_rows($result);
        if($num == 0){
    ?>
   
    <form method="post" action="proficiency.php" id="set_test_list">
         <h2>设置测试范围</h2>
        <table>
            <tr>
                <td>词频下限：</td><td><input type="number" name="low" required="required" /></td>
            </tr>
            <tr>
                <td>词频上限：</td><td><input type="number" name="hign" required="required" /></td>
            </tr>
            <tr>
                <td>熟悉程度：</td>
                <td>
                <select name="proficiency">
                    <option value="1">不认识</option>
                    <option value="2">模糊</option>
                    <option value="0">全部</option>
                </select>
                </td>
            </tr>
            <tr>
                <td><button type="submit" name="set_test_list">设置</button></td>
            </tr>
        </table>
    </form>
<?php
    }else{
        $row =  mysql_fetch_array($result); 
        $word_id = $row['word_id'];
        $word_content = $row['word_content'];

?>
    <!-- <h2>单词评估</h2> -->
    <form method="post" action="proficiency.php">
        <table>
            <tr>
                <td colspan="4" id="word_td">
                    <div><a id="word" target="blank " href="words.php?search_words=<?php echo $word_content; ?>"><?php echo $word_content; ?></a></div>
                    <div><img id="control" src="img/368904.jpg"/></div>
                    
                </td>
            </tr>
            <tr>
                <td colspan="4" ><audio id="audio" src="<?php echo $row['audio_path']; ?>"></audio></td>
            </tr>
            <tr>
                <!-- <td><button type="submit" name="knew" class="knew" >认识</button></td>
                <td><button type="submit" name="uncertain" class="uncertain">模糊</button></td>
                <td><button type="submit" name="alien" class="alien" >不认识</button></td> -->
                <!-- <td><button type="submit" name="name" >人名</button></td> -->
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td><button type="submit" name="next" class="next">Next</button></td>
            </tr>
            
        </table>
        <p>当前词频:<?php echo $row['word_frequency']; ?>,剩余单词：<?php echo $num; ?></p>
        <input type="hidden" name="word_id" value="<?php echo $word_id; ?>"/>
        
    </form>
    <script type="text/javascript">
        var audio = document.getElementById('audio');
        var control = document.getElementById('control');
        control.onclick = function(){
            audio.play();
        }
    </script>
<?php
    }
 ?>
</body>


<?
    mysql_close($dbc);
?>