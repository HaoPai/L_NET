#include <iostream>
#include <sstream>
#include <sys/stat.h>
#include <unistd.h>
#include <fcntl.h>
#include "five_in_a_row.h"
#define BUFFER_SIZE 1000

using namespace std;

int look_ahead(const Board &game,int depth, Move &recommended);
void get_user_move(int &row, int &col, char buffer[]);


int main()
{
    Board game;
    Move m(1,5,0);
    game.play(m);
    stack<Move> moves;
    game.json();
    const char *myfifo = "/var/www/html/programming/bin/gobang";
    mkfifo(myfifo,0777);
    char input[BUFFER_SIZE];
    while(!game.done()){
        int fd = open(myfifo,O_RDONLY);
        int n = read(fd,input,BUFFER_SIZE);
        input[n] = '\0';
        int row = -1,col = -1;
        get_user_move(row,col,input);
        Move user_move(row,col,0);

        // if(!game.play(user_move)){
        //     close(fd);
        //     continue;
        // }
        Move reply;
        look_ahead(game,2,reply);
        game.play(reply);
        game.json();
        close(fd);
    }
    if(game.winner() == 1)
        cout << "黑棋获胜！" << endl;
    else if(game.winner() == 2)
        cout << "白旗获胜！" << endl;
    else 
        cout << " " << endl;

    return 0;
}

int look_ahead(const Board &game,int depth, Move &recommended)
{
    if(game.done()||depth == 0){
        //cout << game.evaluate() << endl;
        return game.get_score_one() - game.get_score_two();
    }
    else {
        stack<Move> moves;
        game.legal_moves(moves);
        int value, best_value = game.worst_case();
        while(moves.size()>0){
            Move try_it,reply;
            try_it = moves.top();
            moves.pop();
            if(try_it.adj_number == 0)
                continue;
            Board new_game = game;
            new_game.play(try_it);
            value = look_ahead(new_game,depth-1,reply);
            if(game.better(value,best_value)){
                best_value = value;
                recommended = try_it;
            }
        }
        return best_value;
    }
}

void get_user_move(int &row, int &col, char buffer[])
{
    stringstream ss;
    ss << buffer;
    ss >> row >>col;
}