#include <stdio.h>
#include <conio.h>

void main() {
    clrscr();
    int data[7] = {3, 12, 9, -4, 21, 6};
    int cari, i;
    printf("Masukkan data yang dicari: ");
        scanf("%d", &cari);
    data[6] = cari;
    i=o;
    while (data[i] != cari)
        i++;
    if (i < 6)
        printf("Data ditemukan pada indeks ke-%d\n", i);
    else
        printf("Data tidak ditemukan\n");
}