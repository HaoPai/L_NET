<?php
    if(isset($_POST['gobang_move'])){
        $row = $_POST['row'];
        $col = $_POST['col'];
        exec("../bin/pipe.o ".$row." ".$col,$response);
    }

    if(isset($_POST['get_bogang_frame'])){
        $file = fopen("../bin/gobang_board.txt", "r");
        echo fread($file,filesize("../bin/gobang_board.txt"));
        fclose($file);
    }
?>