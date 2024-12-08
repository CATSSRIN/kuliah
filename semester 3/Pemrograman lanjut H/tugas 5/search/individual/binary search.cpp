#include <stdio.h>
#include <conio.h>
#include <stdlib.h>

void main() {
    system("cls");
    int data[9] = {3, 9, 11, 12, 15, 17, 23, 31, 35};
    int i, r, m;
    int n=9;
    int cari=17;
    i = 0;
    r = n - 1;
    int ktm = 0;;
    while (i<=r && ktm == 0) {
        m = (i + r) / 2;
        printf("data tengah: %d\n", m);
        if (data[m] == cari) {
            ktm = 1;
        } else if (cari < data[m]) {
            printf("cari dikiri\n");
            r = m - 1;
        }
        else {
            i=m+1;
            printf("cari dikanan\n");
        }
    }   
    if (ktm == 1)
        printf("data ada\n");
    else printf("data tidak ada\n");
}