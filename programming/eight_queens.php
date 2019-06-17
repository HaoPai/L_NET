<!DOCTYPE html>
<html>
<head>
    <title>八皇后问题</title>
    <style type="text/css">
        canvas {border: 1px #999 solid;}
        button {border:none; padding: 5px 15px; background: #999;color: #fff; border-radius: 5px; margin: 10px 50px;cursor: pointer;}
    </style>
</head>
<body>
        <h2>八皇后问题</h2>
        <h3><span id="index">0</span>/<span id="num">0</span></h3>
        <div class="board">
            <canvas id="chess_board" width="480" height="480"></canvas>
        </div>
        <div class="control">
            <button id="prev">上一个解</button><button id="next">下一个解</button>
        </div>
        <script type="text/javascript" src="js/eight_queens.js"></script>
</body>
</html>