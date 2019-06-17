#include <iostream>
#include <string>
#include <cstdio>
#include <sys/stat.h>
#include <unistd.h>
#include <fcntl.h>
#include <cstring>

using namespace std;

int main(int argc, char* argv[])
{
    int fd;
    const char *myfifo = "/var/www/html/programming/bin/gobang";
    const char *space = " ";
    mkfifo(myfifo,777);
    fd = open(myfifo,O_WRONLY|O_NONBLOCK);
    for(int i=1; i<argc; i++){
        write(fd,space,strlen(space));
        write(fd,argv[i],strlen(argv[i]));
    }
    close(fd);
    return 0;
}