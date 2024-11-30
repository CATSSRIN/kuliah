
#include <stdio.h>
#include <string.h>

struct Mahasiswa {
    char npm[20];
    char nama[50];
    char prodi[50];
    float ipk;
};

void tukar(Mahasiswa* a, Mahasiswa* b) {
    Mahasiswa temp = *a;
    *a = *b;
    *b = temp;
}

void tampilkanData(Mahasiswa mhs[], int n) {
    printf("%-10s %-20s %-15s %-5s\n", "NPM", "Nama", "Prodi", "IPK");
    printf("----------------------------------------------\n");
    for (int i = 0; i < n; i++) {
        printf("%-10s %-20s %-15s %-5.2f\n", mhs[i].npm, mhs[i].nama, mhs[i].prodi, mhs[i].ipk);
    }
    printf("\n");
}

void bubbleSort(Mahasiswa mhs[], int n) {
    for (int i = 0; i < n - 1; i++) {
        for (int j = 0; j < n - i - 1; j++) {
            if (strcmp(mhs[j].npm, mhs[j + 1].npm) > 0) {  
                tukar(&mhs[j], &mhs[j + 1]);
            }
        }
    }
}

int main() {
    Mahasiswa mhs[] = {
        {"23081010015", "Andi", "Informatika", 3.5},
        {"23081010180", "Budi", "Informatika", 3.8},
        {"23081010001", "Chandra", "Informatika", 3.2}
    };
    
    int n = sizeof(mhs) / sizeof(mhs[0]);  

    printf("Data Mahasiswa Sebelum Pengurutan:\n");
    tampilkanData(mhs, n);

    bubbleSort(mhs, n);

    printf("Data Mahasiswa Setelah Pengurutan Berdasarkan NPM (Ascending):\n");
    tampilkanData(mhs, n);

    return 0;
}
