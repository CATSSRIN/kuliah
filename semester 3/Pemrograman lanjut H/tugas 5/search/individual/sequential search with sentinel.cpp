#include <stdio.h>
#include <conio.h>
#include <stdlib.h>

void main() {
    system("cls");
    int data[7] = {3, 12, 9, -4, 21, 6};
    int cari, i;
    printf("Masukkan data yang dicari: ");
        scanf("%d", &cari);
    data[6] = cari;
    i=0;
    while (data[i] != cari)
        i++;
    if (i < 6)
        printf("Data ditemukan pada indeks ke-%d\n", i);
    else
        printf("Data tidak ditemukan\n");
}