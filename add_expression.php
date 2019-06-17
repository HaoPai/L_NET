<?php
	  //include "library/getid3/getid3.php";
	  require_once('module/getid3/getid3.php');
      $dbc = mysql_connect("localhost:3306","root","hunan2010");
	  mysql_select_db("l_net",$dbc);
	  mysql_query('SET NAMES UTF8');
	  
	  $show_warning = false;
      $success = ture;
	  $warning = "";
	  if(isset($_POST['add_expression'])){
	  	$show_warning = true;
	  	$expression_content = $_POST['expression_content'];
        $expression_content = strtolower($expression_content);
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
                $query = "select collection_base from word_primary_collection inner join word_in_collection  on word_in_collection.collection_id = word_primary_collection.collection_id where word_id = '".$min_ids[$i]."'";
                $result = mysql_query($query,$dbc);
                $row = mysql_fetch_array($result);
                if($initial){
                    $words = $row['collection_base'];
                    $initial = false;
                }
                else{
                    $words .= (" ".$row['collection_base']);
                }
            }
            $words = '['.$words.']';
            $query = "select * from english_expression where expression_hash = '$hash'";
            $result = mysql_query($query,$dbc);
            if($row = mysql_fetch_array($result)){
                $success = false;
            }else{
                $query = "insert into english_expression (expression_content,expression_hash,expression_words) values ('$expression_content','$hash','$words')";
                mysql_query($query,$dbc);
                $success = true;
            }
        }

        if($success){
            $warning = '<p class = "success">添加成功</p>';
        }
        else $warning = '<p class = "failed">添加失败</p>';
	  }
	  
?>
<!DOCTYPE html>
<html>
<head>
	   <meta charset="utf-8"/>
       <style type="text/css">
	        body { padding:50px;  }
			h4 { margin:30px 0px;}
			table { border-collapse:collapse;}
			td,th { padding:10px 20px; border:1px solid #aaa; border-collapse:collapse; }
			.missed_words { width:860px; }
			.initial { display:none; }
			span { width:100px;  margin:5px 20px; border-bottom: 1px solid #ccc; display:inline-block; }
			input { width: 300px; font-size:1em; border:none; outline:none;}
			
			 #english_nav { padding:5px 0px;margin:10px 0px 50px 0px;border-bottom:1px solid #ccc ;}
		   #english_nav li { padding:5px 10px; margin:0px 40px 0px 0px; display:inline; }
		   
		   #english_nav a,td a { border:none ; color:#666; font-size:1.2em; text-decoration:none;}
		   form select {font-size: 1em,padding:10px;height: 20px; }
		   .success{background: blue;color: #fff;}
		   .failed {background-color: red; color: #fff;}
	   </style>
</head>
<body>
    <?php require_once('struct_php/english_sys_nav.php'); ?>
    <h2>添加短语</h2>
    <?php
    	if($show_warning){
    		echo $warning;
    	}
    ?>
    <form method="post" action="add_expression.php">
    	<table>
    		<tr>
    			<td><label>短语内容：</label></td><td><input name="expression_content" id="expression_content" type="text" required="required"></td>
    		</tr>
    		
    		<tr><td></td><td><button name="add_expression" >添加</button></td></tr>
    	
        </table>
    </form>
</body>


<?php
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


	mysql_close($dbc);
?>