#include <stdio.h>
#include <string.h>

struct Mahasiswa {
    char npm[20];
    char nama[50];
    char prodi[50];
    float ipk;
};

// Fungsi untuk mencetak data mahasiswa
void tampilkanData(Mahasiswa mhs[], int n) {
    printf("%-10s %-20s %-15s %-5s\n", "NPM", "Nama", "Prodi", "IPK");
    printf("----------------------------------------------\n");
    for (int i = 0; i < n; i++) {
        printf("%-10s %-20s %-15s %-5.2f\n", mhs[i].npm, mhs[i].nama, mhs[i].prodi, mhs[i].ipk);
    }
    printf("\n");
}

// Fungsi untuk melakukan pengurutan data mahasiswa berdasarkan NPM dengan Bubble Sort
void bubbleSort(Mahasiswa mhs[], int n) {
    for (int i = 0; i < n - 1; i++) {
        for (int j = 0; j < n - i - 1; j++) {
            if (strcmp(mhs[j].npm, mhs[j + 1].npm) > 0) {
                // Tukar data mahasiswa
                Mahasiswa temp = mhs[j];
                mhs[j] = mhs[j + 1];
                mhs[j + 1] = temp;
            }
        }
    }
}

int main() {
    int n;

    // Input jumlah mahasiswa
    printf("Masukkan jumlah mahasiswa: ");
    scanf("%d", &n);

    // Array untuk menyimpan data mahasiswa
    Mahasiswa mhs[n];

    // Input data mahasiswa
    for (int i = 0; i < n; i++) {
        printf("\nMasukkan data mahasiswa ke-%d\n", i + 1);
        printf("NPM: ");
        scanf("%s", mhs[i].npm);  // Membaca NPM
        printf("Nama: ");
        getchar();  // Untuk membersihkan newline character
        fgets(mhs[i].nama, sizeof(mhs[i].nama), stdin);  // Membaca Nama
        mhs[i].nama[strcspn(mhs[i].nama, "\n")] = 0;  // Menghapus newline yang ditambahkan oleh fgets

        printf("Prodi: ");
        fgets(mhs[i].prodi, sizeof(mhs[i].prodi), stdin);  // Membaca Prodi
        mhs[i].prodi[strcspn(mhs[i].prodi, "\n")] = 0;  // Menghapus newline

        printf("IPK: ");
        scanf("%f", &mhs[i].ipk);  // Membaca IPK
    }

    // Menampilkan data mahasiswa sebelum pengurutan
    printf("\nData Mahasiswa Sebelum Pengurutan:\n");
    tampilkanData(mhs, n);

    // Melakukan pengurutan berdasarkan NPM
    bubbleSort(mhs, n);

    // Menampilkan data mahasiswa setelah pengurutan
    printf("\nData Mahasiswa Setelah Pengurutan Berdasarkan NPM:\n");
    tampilkanData(mhs, n);

    return 0;
}
