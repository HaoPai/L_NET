#include <iostream>
#include "five_in_a_row.h"

using namespace std;
int look_ahead(const Board &game,int depth, Move &recommended);


int main()
{
    Board game;
    stack<Move> moves;
    int n;
    cin >> n;
    while(n-- >0){
        int row,col;
        cout << "input row and col :";
        cin >> row >> col;
        Move user_move(row,col,0);
        if(!game.play(user_move)) continue;
        Move reply;
        look_ahead(game,3,reply);
        cout <<"reply row = " << reply.row <<" col = " << reply.col << endl;
        game.play(reply);
        game.print();
        cout << endl;
        game.print_adj();

    }
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
        if(best_value == game.worst_case()){
            recommended.row = 7;
            recommended.col = 7;
        }
        return best_value;
    }
}