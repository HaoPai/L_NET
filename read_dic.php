
<?php require_once('function_php/lnet.php'); ?>
<?php require_once('struct_php/global.php'); ?>

<?php 
    $root = "dic/";
    $pres = array();
    $pres = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
    //$pres = ['Z'];
    for($i=0;$i<count($pres);$i++){
        $path = $root.$pres[$i];
        $files = scandir($path);
        for($j = 0; $j < count($files); $j++)
            if(!is_dir($path."/".$files[$j])){
                $file_path = $path."/".$files[$j];
                echo $file_path ."-----------------/n";
                $words = get_words($file_path);
                $words_explanation = get_words_and_explanation($words);
                for($k=0;$k<count($words_explanation);$k++){
                    $word = $words_explanation[$k]['word_content'];
                    echo $word."\n\n";
                    $word_id = get_word_id($word,$dbc);
                    for($l = 0;$l < count($words_explanation[$k]['explanations']);$l++){
                        $explanation_type = $words_explanation[$k]['explanations'][$l]['type'];
                        $explanation_english = preg_replace("/'/","\\'",$words_explanation[$k]['explanations'][$l]['en']);
                        $explanation_chinese = preg_replace("/'/","\\'",$words_explanation[$k]['explanations'][$l]['ch']);
                        $explanation_example = preg_replace("/'/","\\'",$words_explanation[$k]['explanations'][$l]['example']);
                        $query = "insert into english_word_explanation ".
                                 " (word_id,explanation_type,explanation_english,explanation_chinese,explanation_example) ".
                                 " values ('$word_id','$explanation_type','$explanation_english','$explanation_chinese','$explanation_example')";
                        echo $query."\n\n";
                        mysql_query($query,$dbc);
                    }
                }
            }
    }
?>


<?php

    function get_word_id($word,$dbc)
    {
        $query = "select word_id from english_words_lib where word_content = '$word'";
        $result = mysql_query($query,$dbc);
        if($row = mysql_fetch_array($result)){
            return $row['word_id'];
        }

        $query = "insert into english_words_lib (word_content) values ('$word')";
        echo $query."\n\n";
        mysql_query($query,$dbc);

        $query = "select word_id from english_words_lib where word_content = '$word'";
        $result = mysql_query($query,$dbc);
        if($row = mysql_fetch_array($result)){
            return $row['word_id'];
        }
        return 0;
    }

    function get_words($file_path)
    {
        $pattern = "/^[a-zA-Z-\s]+$/";
        $file = fopen($file_path,"r");
        $words = array();
        $word = array();
        $word['word_content'] = "";
        $word['lines'] = array();
        while(!feof($file)){
            $line = fgets($file,2048);
            $line = trim($line);
            if(strlen($line)==0) continue;
            if(preg_match($pattern,$line)){
                if($line == $word['word_content']) continue;
                $words[] = $word;
                $word['word_content'] = $line;
                $word['lines'] = array();
            }else{
                $response = check_line($line);
                if($response['type']) $word['lines'][] = $response;
            } 
        }
        fclose($file);
        return $words;
    }

    function check_line($line)
    {
        $types = array();
        $types = ['n','v','adj','adv','prep','pron','abbr','conj','int','num'];
        $response = array();
        $response['type'] = "";
        $response['line'] = "";
        for($i=0;$i<count($types);$i++){
            $pattern = "/\b".$types[$i]."[\s\.]/";
            if(preg_match($pattern,$line)){
                $response['type'] = $types[$i];
                $line = preg_replace($pattern,"",$line);
                $response['line'] = $line;
                break;
            }

        }
        return $response;
    }

    function get_words_and_explanation($words)
    {
        $words_explanation = array();
        for($i = 0; $i<count($words);$i++){
            if(count($words[$i]['lines']) == 0) continue;
            $word_explantion = array();
            $word_explantion['word_content'] = $words[$i]['word_content'];
            $word_explantion['explanations'] = array();
            for($j=0;$j < count($words[$i]['lines']);$j++){
                $type = $words[$i]['lines'][$j]['type'];
                $explanations = get_explanation($words[$i]['lines'][$j]['line']);
                if(count($explanations) == 0)
                    $explanations[] = $words[$i]['lines'][$j]['line'];
                for($k=0;$k<count($explanations);$k++){
                    $explanations[$k] = preg_replace("/\/.+\//","",$explanations[$k]);
                    $matches;
                    $example = "";
                    preg_match_all("/:.+/",$explanations[$k],$matches);
                    if(count($matches[0])>0) {
                        $example = $matches[0][0];
                        $example = substr($example,1);
                        $example = trim($example);
                    }
                    $explanations[$k] = preg_replace("/:.+/","",$explanations[$k]);
                    $explanations[$k] = preg_replace("/[0-9]+/","",$explanations[$k]);
                    $explanations[$k] = trim($explanations[$k]);
                    $response = divide_expanation($explanations[$k]);
                    $explanation = array();
                    $explanation['type'] = $type;
                    $explanation['en'] = $response['en'];
                    $explanation['ch'] = $response['ch'];
                    $explanation['example'] = $example;
                    $word_explantion['explanations'][] = $explanation;
                }
            }
            $words_explanation[] = $word_explantion;
        }
        return $words_explanation;
    }

    function get_explanation($line)
    {
        $pattern = "/\b[1-9]+\s[^0-9]+/";
        $matches;
        preg_match_all($pattern,$line,$matches);
        return $matches[0];
    }

    function divide_expanation($explanation)
    {
        $response = array();
        $response['en'] = "";
        $response['ch'] = "";
        $pattern = "/\([a-z]{1,}[^\)]+[^\x01-\x7F]{1,}\)/";
        $matches;
        $en_string = "";
        $ch_string = "";
        preg_match_all($pattern,$explanation,$matches);
        if(count($matches[0])){
            $in_string = $matches[0][0];
            $explanation = preg_replace($pattern,"",$explanation);
            $pattern = "/[\x01-\x7F]+/";
            preg_match_all($pattern,$in_string,$matches);
            $en_string = trim($matches[0][0]).")";
            $pattern = "/[^\x01-\x7F]{1,}.+/";
            preg_match_all($pattern,$in_string,$matches);
            $ch_string = "(".$matches[0][0];
            $response['en'].=$en_string." ";
            $response['ch'].=$ch_string." ";
        }
        $pattern = "/\[[a-z]{1,}[^\)]+[^\x01-\x7F]{1,}\]/";
        $matches;
        $en_string = "";
        $ch_string = "";
        preg_match_all($pattern,$explanation,$matches);
        if(count($matches[0])){
            $in_string = $matches[0][0];
            $explanation = preg_replace($pattern,"",$explanation);
            $pattern = "/[\x01-\x7F]+/";
            preg_match_all($pattern,$in_string,$matches);
            $en_string = trim($matches[0][0])."]";
            $pattern = "/[^\x01-\x7F]{1,}.+/";
            preg_match_all($pattern,$in_string,$matches);
            $ch_string = "[".$matches[0][0];
            $response['en'].=$en_string." ";
            $response['ch'].=$ch_string." ";
            
        }
        //echo $explanation."\n";
        $pattern = $pattern = "/[\x01-\x7F]+/";
        $en_string = "";
        $ch_string = "";
        preg_match_all($pattern,$explanation,$matches);
        if(count($matches[0])>0)
            $en_string = trim($matches[0][0]);
        $pattern = "/[^\x01-\x7F]{1,}.+/";
        preg_match_all($pattern,$explanation,$matches);
        if(count($matches[0])>0)
            $ch_string = $matches[0][0];
        $response['en'].=$en_string." ";
        $response['ch'].=$ch_string." ";
        return $response;
    }
?>




