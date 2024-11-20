#include <stdio.h>

int main() {
    int angka, i, flag = 0;

    printf("Masukkan angka: ");
    scanf("%d", &angka);

    for (i = 2; i <= angka / 2; ++i) {
        if (angka % i == 0) {
            flag = 1;
            break;
        }
    }

    if (angka == 1) {
        printf("Angka 1 bukan bilangan prima.\n");
    } else {
        if (flag == 0)
            printf("%d adalah bilangan prima.\n", angka);
        else
            printf("%d bukan bilangan prima.\n", angka);
    }

    return 0;
}