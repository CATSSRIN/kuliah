
struct Mahasiswa {
    char nama[20];
    int nilai[4];
};

int jumlah_mahasiswa = 5;
struct Mahasiswa mahasiswa[5] = {
    {"alice", {85, 78, 90, 88}},
    {"bob", {70, 65, 75, 80}},
    {"charlie", {90, 92, 85, 89}},
    {"david", {60, 58, 70, 65}},
    {"eve", {80, 85, 78, 82}}
};

#include <stdio.h>

int main() {
    for (int i = 0; i < jumlah_mahasiswa; i++) {
        int sum = 0;
        for (int j = 0; j < 4; j++) {
            sum += mahasiswa[i].nilai[j];
        }
        float average = (float)sum / 4;
        printf("Average for %s: %.2f\n", mahasiswa[i].nama, average);
    }
    return 0;
}




