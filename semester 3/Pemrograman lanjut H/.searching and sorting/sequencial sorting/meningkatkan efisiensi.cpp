
#include <iostream>
#include <cstdlib>
#include <ctime>

int main() {
    system("cls");
    int data[100];
    int cari = 20;
    int counter = 0;
    srand(time(0));
    for (int i = 0; i < 100; i++) {
        data[i] = rand() % 100;
        std::cout << data[i] << " ";
    }
    std::cout << "\ndone. \n";
    printf("\ndone. \n");

    for (int i = 0; i < 100; i++) {
        if (data[i] == cari) {
            counter++;
        }
    }
    std::cout << "Data ditemukan sebanyak: " << counter << "\n";
    printf("Data ditemukan sebanyak: %d\n", counter);
    if (counter == 0) {
        std::cout << "Data tidak ditemukan";
        printf("Data tidak ditemukan");
    }
}