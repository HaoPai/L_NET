var board = document.getElementById("chess_board");
var board_context = board.getContext("2d");
var game_ended = false;

set_board();
draw_board();

var r = 1, c = 1;

setInterval(function(){
    if(!game_ended)
        make_move(r,c);
    r ++;
    c ++;
},2000);

get_frame();



function do_nothing()
{

}

function get_frame()
{
    var path = "data_process/receive_data.php";
    var parts = new Array();
    parts.push(encodeURIComponent("get_bogang_frame")+"="+"");
    ajax_post(parts.join('&'),path,draw_frame);     
}

function draw_frame(board)
{
    var board_frame = JSON.parse(board);
    draw_board();
    for(var i=0;i<board_frame.length;i++) if(board_frame[i]!=0){
        var row = parseInt(i/15);
        var col = i%15;
        //alert("row= "+row+ " col = "+ col);
        draw_move(row,col,board_frame[i],0);
    }
    if(!game_ended)
        setTimeout(get_frame,1000);
       
}




function draw_board()
{
    board_context.lineWidth = 1 ;
    board_context.beginPath();
    for(var i=0;i<15;i++){
        board_context.moveTo(40+0.5,40*(i+1)+0.5);
        board_context.lineTo(600+0.5,40*(i+1)+0.5);
    }

    for(var i=0;i<15;i++){
        board_context.moveTo(40*(i+1)+0.5,40+0.5);
        board_context.lineTo(40*(i+1)+0.5,600+0.5);
    }
    board_context.closePath();
    board_context.stroke();
    board_context.fillStyle = "#000";
    fill_circle(160,160,6);
    fill_circle(160,480,6);
    fill_circle(480,160,6);
    fill_circle(480,480,6);
    fill_circle(320,320,6);
}


function draw_move(row,col,user,mode)
{
    if(user == 1) board_context.fillStyle = "#000";
    else board_context.fillStyle = "#fff";
    if(mode == 0) board_context.strokeStyle = "#000";
    else board_context.strokeStyle = "red";
    stroke_circle((col+1)*40,(row+1)*40,18);
    fill_circle((col+1)*40,(row+1)*40,17);
}


function fill_circle(x,y,r)
{
    board_context.beginPath();
    board_context.arc(x,y,r,0,2*Math.PI);
    board_context.closePath();
    board_context.fill();
}

function stroke_circle(x,y,r)
{
    board_context.beginPath();
    board_context.arc(x,y,r,0,2*Math.PI);
    board_context.closePath();
    board_context.stroke();
}

function game_result( response)
{
    if(response)
        alert(response);
    game_ended = true;
}


function set_board()
{
    ajax_get("set_gobang_board.php",game_result);
}


function make_move(row,col)
{
    var path = "data_process/receive_data.php";
    var parts = new Array();
    parts.push(encodeURIComponent("gobang_move")+"="+"");
    parts.push(encodeURIComponent("row")+"="+encodeURIComponent(row));
    parts.push(encodeURIComponent("col")+"="+encodeURIComponent(col));
    ajax_post(parts.join('&'),path,do_nothing);           
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


function ajax_post(sendData,path,fun)
{
   var xhr = new XMLHttpRequest();
   xhr.onreadystatechange = function(){
       if(xhr.readyState ==4)
       {
           if(xhr.status >= 200 && xhr.status <300)
           {
               
               fun(xhr.responseText);
           }
           else
           {
               alert("ajax 失败！");
           }
       }
    }
    xhr.open("POST",path,true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); 
    xhr.send(sendData);
}




