#include <stdio.h>
#include <conio.h>

void main() {
    clrscr();
    int data [8] = {8,10,6,-2,11,7,1,100};
    int cari;
    int flag=0;
    printf("masukkan data yang ingin dicari = ");
        scanf("%d", &cari);
    for (int i=0; i<8; i++) {
        if (data[i] == cari)
            flag=1;
    }
    if (flag==1)
        printf("Data ditemukan");
    else
        printf("Data tidak ditemukan");
}