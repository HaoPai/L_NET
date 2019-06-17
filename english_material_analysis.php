<?php require_once('struct_php/global.php'); ?>
<?php
  $show_analysis = false;
  if(isset($_POST['analysis'])){
    $show_analysis = true;
  }

?>
<!DOCTYPE html>
<html>
      <head>
            <title>英语材料分析</title>
            <style type="text/css">
			         body {padding-left:50px;  }
					 .page_content {width:1360px; margin:auto;}
					 td { padding:10px;}
           .show_words{border-collapse:collapse;}
           .show_words td {border:  1px solid #ddd; padding: 5px 20px;}
           .word {color: #000; }
           .word a {text-decoration: none; color: #333;}
           .index {color: #999;}
           .uncertain {background: #FF9900; color: #fff;}
           .alien {background: #FF6666;color: #fff;}

           .uncertain a,.alien a { color: #fff; }
					 input { width:100px; height:32px; margin:20px 0px; }
					 select { width:250px; height:32px; font-size:1em; }
					 textarea { width:600px; height:200px; }
					 h3 {color:red; font-weight:normal;}
					 
					 #response {width:800px; min-height:800px; margin:100px 0px 500px 0px; position:relative; }
					 
					 #word_box {width:500px; height:800px; border:2px dashed #B5D2F2 ; position:absolute; top:10px; left:830px; display:none;}
					 
					 #response p {font-family: "Segoe UI","Segoe WP",Arial,Sans-Serif; font-size:1.1em; line-height:2em; text-indent:2em; margin:10px 0px;}
					 #response a {text-decoration:none; color:#333; }
					 
					 #response .rate_5 ,#response .rate_6   { color:red;  }
					 #response .rate_9 {color:red;}
					 
					 
					
					 
					 #english_nav { padding:5px 0px;margin:50px 0px;border-bottom:1px solid #ccc ;}
					 #english_nav li { padding:5px 10px; margin:0px 40px 0px 0px; display:inline; }
					 
					 #english_nav a { border:none ; color:#000; font-size:1.2em; text-decoration:none;}


                     #uncertain_div  ,#alien_div {display: none; background: #eee; width: 1200px; padding: 20px;}
                     #uncertain_div div ,#alien_div div{display: inline-block;width: 200px;height: 30px;padding: 10px 20px; margin:10px;border: 1px solid #ccc;
                     border-radius: 5px; background: #fff; }

                     #uncertain_div a ,#alien_div a {text-decoration: none; color: #000; font-size: 1.2em; font-weight: 400;}
                     #uncertain_div span ,#alien_div span {width: 50px; float: right;color: #999; font-size: 0.8em;}

                     #reorganize {padding: 5px 30px; border: 1px solid #ccc ; border-radius: 5px; background: #fff;color: #333;cursor: pointer;font-size: 1em}
					 
					 
			</style>
      </head>
      <body>
             <?php require_once('struct_php/english_sys_nav.php');
                if($show_analysis){
                  $words = $_POST['words'];
                  $words = strtolower($words);
                  $pattern = "/[a-z]+/i";
                  $ids = array();
                  preg_match_all($pattern,$words,$array);
                  $array[0] = array_unique($array[0]);
                  $array[0] = array_merge($array[0]);
                  for($i = 0; $i < count($array[0]);$i++){
                    $word = $array[0][$i];
                    $id = quick_proto($word,$dbc);
                    if($id >0) $ids[] = $id;
                  }
                  $ids = array_unique($ids);
                  $words = array();
                  foreach ($ids as $id) {
                    $query = "select word_content, web_collins_rate, coca_index ,word_frequency,word_proficiency from english_words_lib where word_id = '$id'";
                    $result = mysql_query($query,$dbc);
                    $row = mysql_fetch_array($result);
                    $item = array();
                    $item['word'] = $row['word_content'];
                    $item['index'] = $row['word_frequency'];
                    $item['proficiency'] = $row['word_proficiency'];
                    $words[] = $item;
                  }
                  array_multisort(array_column($words,'index'),SORT_ASC,SORT_NUMERIC,$words);
                  $rate_five = array();
                  $rate_four = array();
                  $rate_three = array();
                  $rate_two = array();
                  $rate_one = array();
                  $uncertain = array();
                  $alien = array();
                  for($i=0;$i<count($words);$i++){
                    if($words[$i]['index'] <= 1000){
                      $rate_five[] = $words[$i];
                    }else if($words[$i]['index'] <= 5000){
                      $rate_four[] = $words[$i];
                    }
                    else if($words[$i]['index'] <= 10000){
                      $rate_three[] = $words[$i];
                    }
                    else if($words[$i]['index'] <= 30000){
                      $rate_two[] = $words[$i];
                    } else $rate_one[] = $words[$i];

                    if($words[$i]['proficiency'] == '1') $alien[] = $words[$i];
                    else if($words[$i]['proficiency'] == '2') $uncertain[] = $words[$i];
                  }
                  
                  echo '<div id = "uncertain_div">';
                  echo '<h4>模糊单词'.count($uncertain).'个</h4>';
                  for( $i=0;$i<count($uncertain);$i++){
                        echo '<div><span>'.$uncertain[$i]['index'].'</span><a target = "blank" href ="words.php?search_words='.$uncertain[$i]['word'].'" >'.$uncertain[$i]['word'].'</a></div>';
                  }
                  echo '</div>';
                  echo '<div id = "alien_div">';
                  echo '<h4>不认识单词'.count($alien).'个</h4>';
                  for($i=0;$i<count($alien);$i++){
                        echo '<div><span>'.$alien[$i]['index'].'</span><a target = "blank" href = "words.php?search_words='.$alien[$i]['word'].'">'.$alien[$i]['word'].'</a></div>';
                  }
                  echo '</div>';
                  echo '<div id = "found_words">';
                  
                  echo '<h4>共发现单词'.count($words).'个</h4>';
                  echo '<button id = "reorganize">整理</button>';
                  echo '<p>发现1000以内的单词'.count($rate_five).'个</p>';
                  print_rate($rate_five,5);
                  echo '<p>发现1000 - 5000 的单词'.count($rate_four).'个</p>';
                  print_rate($rate_four,5);
                  echo '<p>发现5000 - 10000 的单词'.count($rate_three).'个</p>';
                  print_rate($rate_three,5);
                  echo '<p>发现10000 - 30000 的单词'.count($rate_two).'个</p>';
                  print_rate($rate_two,5);
                  echo '<p>发现30000以上的单词'.count($rate_one).'个</p>';
                  print_rate($rate_one,5);
                  echo '</div>';
                }
                else
                {
             ?>
                    <div class="page_content">
                           <h2>请输入分析内容：</h2>
                           <form id="analysis" method="post" action="english_material_analysis.php">
                                 <table>
                                        
                                         <tr>
                                              <td>请输入内容：</td><td><textarea id="input_words" name="words"></textarea></td>
                                         </tr>
                                         <!-- <tr>
                                            <td>分析文件：</td><td><input type="file" name="file"></td>
                                         </tr> -->
                                         <tr>
                                               <td></td><td><input type="submit" value="提交" name="analysis" /></td>
                                         </tr>
                                 </table>
                           </form>
                           <div id="response">
                                    <div class="statistics">
                                    </div>
                                    <div class="passage">
                                    </div>
                                    <div id="word_box">
                                    </div>
                           </div>
                     </div>
                     <?php
                        }
                     ?> 
            <script type="text/javascript">
                var reorganize_button = document.getElementById("reorganize");
                var found_words = document.getElementById("found_words");
                var alien = document.getElementById("alien_div");
                var uncertain = document.getElementById("uncertain_div");
                reorganize_button.onclick = function(){
                    found_words.style.display = "none";
                    alien.style.display = "block";
                    uncertain.style.display = "block";
                }
            </script>     
      </body>
</html>


<?php 
  function print_rate($rate,$row){
      $i = 0;
      echo '<table class = "show_words">';
      while(($i + $row)<count($rate)){
        echo '<tr>';
        for($j= 0 ;$j <$row ; $j++){
          $proficiency = '';
          switch($rate[$i]['proficiency']){
            case '1':
              $proficiency = "alien";
            break;
            case '2':
              $proficiency = "uncertain";
            break;
            case '3':
              $proficiency = 'knew';
            break;
          }
          echo '<td class= "word '.$proficiency.'"><a target = "blank" href = "words.php?search_words='.$rate[$i]['word'].'"">'.$rate[$i]['word'].'</a></td><td class = "index">'.$rate[$i++]['index'].'</td>';
        }
        echo '</tr>';
      }
      echo '<tr>';
      while($i<count($rate)){
          $proficiency = '';
          switch($rate[$i]['proficiency']){
            case '1':
              $proficiency = "alien";
            break;
            case '2':
              $proficiency = "uncertain";
            break;
            case '3':
              $proficiency = 'knew';
            break;
          }
        echo '<td class = "word '.$proficiency.'"><a target = "blank" href = "words.php?search_words='.$rate[$i]['word'].'">'.$rate[$i]['word'].'</td><td class = "index">'.$rate[$i]['index'].'</a></td>';
        $i++;
      }
      echo '</tr>';
      echo '</table>';
  }

  function quick_proto($word,$dbc){
    if(($id = check_proto($word,$dbc))>0) return $id;
    $query = "select lib_id from english_words where word_content = '$word' and lib_id != 0";
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

    if(($id = check_proto($word,$dbc))>0) return $id;

    $query = "select lib_id from english_words where word_content = '$word' and lib_id <> 0";
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
    $query = "select word_id from english_words_lib where word_content = '$word'";
    $result = mysql_query($query,$dbc);
    if($row = mysql_fetch_array($result)){
      return $row['word_id'];
    }
    else return 0;
   }
?>

<?php mysql_close($dbc); ?>