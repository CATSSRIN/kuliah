#include <stdio.h>

int main() {
    int n, i;
    unsigned long long faktorial = 1;

    printf("Masukkan bilangan bulat positif: ");
    scanf("%d", &n);

    for (i = 1; i <= n; ++i) {
        faktorial *= i;
    }

    printf("Faktorial dari %d = %llu", n, faktorial);
    return 0;
}