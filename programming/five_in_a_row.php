<?php 
            exec("killall -9 five_in_a_row.o",$response);
?>
<!DOCTYPE html>
<html>
<head>
    <title>五子棋游戏</title>
    <style type="text/css">
        canvas {border: 1px #eee solid; background: #d2b48c;}
        button {border:none; padding: 5px 15px; background: #999;color: #fff; border-radius: 5px; margin: 10px 50px;cursor: pointer;}
    </style>
</head>
<body>
        <h2>五子棋游戏</h2>
        <!-- <h3><span id="index">0</span>/<span id="num">0</span></h3> -->
        <div class="board">
            <canvas id="chess_board" width="640" height="640" ></canvas>
        </div>
        <!-- <div class="control">
            <button id="prev">上一个解</button><button id="next">下一个解</button>
        </div> -->
        <script type="text/javascript" src="js/five_in_a_row.js"></script>
</body>
</html>