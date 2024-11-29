#include <conio.h>
#include <stdlib.h>

void main() {
    clrscr();
    int data[100];
    int cari=20;
    int counter=0;
    int flag=0;
    randomize();
    printf (int i=0; i<100; i++) {
        data[i]=random(100);
        printf ("%d ", data[i]);
    }
    printf("\ndone. \n");

    for (int i=0; i<100; i++) {
        if (data[i]==cari) {
            flag=1;
            counter++;
        }
    }
    if (flag==1)
        printf("Data ditemukan sebanyak: %d\n", counter);
    else
        printf("Data tidak ditemukan");
}
// original code from teacher