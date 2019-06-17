var board = document.getElementById("chess_board");
var prev = document.getElementById("prev");
var next = document.getElementById("next");
var index = document.getElementById("index");
var num = document.getElementById("num");
var board_context = board.getContext("2d");
var solutions = null;
var solution_index =0;
draw_board();
get_solutions();


prev.onclick = function(){
    draw_board();
    solution_index  = (solution_index - 1 + solutions.length)%solutions.length;
    draw_solution(solution_index);
    index.innerHTML = solution_index+1;
}

next.onclick = function(){
    draw_board();
    solution_index  = (solution_index + 1 )%solutions.length;
    draw_solution(solution_index);
    index.innerHTML = solution_index+1;
}



function draw_board()
{
    for(var i=0;i<8;i++)
        for(var j=0;j<8;j++)
            if((i+j)%2 == 0){
                board_context.fillStyle = "#fff";
                board_context.fillRect(i*60,j*60,i*60+60,j*60+60);
            }else{
                board_context.fillStyle = "#ccc";
                board_context.fillRect(i*60,j*60,i*60+60,j*60+60);
            }
}

function draw_solution(index)
{
    if(solutions&&index>=0&&index<solutions.length)
        for(var i =0;i<solutions[index].length;i++)
            draw_queen(i+1,solutions[index][i]);
}

function draw_queen(row,col)
{
    board_context.fillStyle = "#000000";
    board_context.font = "60px serif";
    board_context.fillText("♕",60*(col-1),50+(row-1)*60);
}

function set_solutions(response)
{
    solutions = JSON.parse(response);
    index.innerHTML = "1";
    num.innerHTML = solutions.length;
    draw_solution(0);
}

function get_solutions()
{
    ajax_get("get_data.php?target=eight_queens",set_solutions);
}

function ajax_get(url,fun)
{
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function(){
    if(xhr.readyState ==4)
    {
        if(xhr.status >= 200 && xhr.status <300 )
        {
            if(fun)
                fun(xhr.responseText);
             }
            else
            {
                alert("ajax 失败！");
            }
        }
    }
 
    xhr.open("GET",url,true);
    xhr.send();
}

