#include <stdio.h>

int angka[] = {67, 5, 1, 90, 27, 8, 12};
int find = 27;
int flag = 0;

int main() {
    for (int i = 0; i < 7; i++) 
    {
        if (angka[i] == find) {
            flag = 1;
            break;
        }
    }

    if(flag) printf("data ditemukan");
    else printf("data tidak ditemukan");

    return 0;
}
