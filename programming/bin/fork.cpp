#include <stdlib.h>
#include <unistd.h>
#include <iostream>

using namespace std;

int main()
{
    int pid = fork();
    if(pid == -1)
        cout << "Error detected !" << endl;
    else if(pid == 0){
        char *argv[] = {"five_in_a_row.o",NULL};
        cout << execvp("/var/www/html/programming/bin/five_in_a_row.o",argv) << endl;
    }else{
        cout <<"This is parent process, child pid = " << pid << endl;
    }
    return 0;
}
