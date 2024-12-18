// Caezarlov
/*=====================================================================================================================================*/
/*=====================================================================================================================================*/

#include <conio.h>
#include <math.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#define max_lines 100

typedef struct
{
    char id[15];
    char nama[50];
    char kategori[30];
    int stok;
} Barang;

void printDataBarang(Barang brg[], int n) {
    printf("+-------------------+-----------------------------------+-----------------------+--------+\n");
    printf("| ID                | NAMA BARANG                       |KATEGORI               |STOK    |\n");
    printf("+-------------------+-----------------------------------+-----------------------+--------+\n");
    int i;
    for (i = 0; i < n; i++) {
        printf("| %-17s | %-33s | %-21s | %-6d |\n", brg[i].id, brg[i].nama, brg[i].kategori, brg[i].stok);
    }
    printf("+-------------------+-----------------------------------+-----------------------+--------+\n");
    printf("\n");
}

// Anas
/*=====================================================================================================================================*/
/*=====================================================================================================================================*/

void BubbleSort(Barang brg[], int n, int kolom, int ascending) {
    int i;
    int j;
    for (i = 0; i < n - 1; i++) {
        for (j = 0; j < n - i - 1; j++) {
            int compare = 0;
            switch (kolom) {
                case 1:
                    compare = strcmp(brg[j].id, brg[j + 1].id);
                    break;
                case 2:
                    compare = strcasecmp(brg[j].nama, brg[j + 1].nama);
                    break;
                case 3:
                    compare = strcmp(brg[j].kategori, brg[j + 1].kategori);
                    break;
                case 4:
                    compare = (brg[j].stok > brg[j + 1].stok) - (brg[j].stok < brg[j + 1].stok);
                    break;
            }

            if ((ascending && compare > 0) || (!ascending && compare < 0)) {
                Barang temp = brg[j];
                brg[j] = brg[j + 1];
                brg[j + 1] = temp;
            }
        }
    }
}

void SelectionSort(Barang brg[], int n, int kolom, int ascending) {
    int i;
    int j;
    for (i = 0; i < n - 1; i++) {
        int minMaxIdx = i;
        for (j = i + 1; j < n; j++) {
            int compare = 0;
            switch (kolom) {
                case 1:
                    compare = strcmp(brg[j].id, brg[minMaxIdx].id);
                    break;
                case 2:
                    compare = strcasecmp(brg[j].nama, brg[minMaxIdx].nama);
                    break;
                case 3:
                    compare = strcmp(brg[j].kategori, brg[minMaxIdx].kategori);
                    break;
                case 4:
                    compare = (brg[j].stok > brg[minMaxIdx].stok) - (brg[j].stok < brg[minMaxIdx].stok);
                    break;
            }

            if ((ascending && compare < 0) || (!ascending && compare > 0)) {
                minMaxIdx = j;
            }
        }
        if (minMaxIdx != i) {
            Barang temp = brg[i];
            brg[i] = brg[minMaxIdx];
            brg[minMaxIdx] = temp;
        }
    }
}

// Amsal
/*=====================================================================================================================================*/
/*=====================================================================================================================================*/

void InsertionSort(Barang brg[], int n, int kolom, int ascending) {
    int i;
    int j;
    for (i = 1; i < n; i++) {
        Barang key = brg[i];
        j = i - 1;
        int compare;

        while (j >= 0) {
            switch (kolom) {
                case 1:
                    compare = strcmp(key.id, brg[j].id);
                    break;
                case 2:
                    compare = strcasecmp(key.nama, brg[j].nama);
                    break;
                case 3:
                    compare = strcmp(key.kategori, brg[j].kategori);
                    break;
                case 4:
                    compare = (key.stok > brg[j].stok) - (key.stok < brg[j].stok);
                    break;
            }

            if ((ascending && compare < 0) || (!ascending && compare > 0)) {
                brg[j + 1] = brg[j];
                j--;
            } else {
                break;
            }
        }
        brg[j + 1] = key;
    }
}

// Rama
/*=====================================================================================================================================*/
/*=====================================================================================================================================*/

int binarySearch(Barang brg[], int n, int kolom, char *key) {
    int low = 0, high = n - 1;
    while (low <= high) {
        int mid = low + (high - low) / 2;
        int compare = 0;

        switch (kolom) {
            case 1:
                compare = strcmp(brg[mid].id, key);
                break;
            case 2:
                compare = strcasecmp(brg[mid].nama, key);
                break;
            case 3:
                compare = strcmp(brg[mid].kategori, key);
                break;
            case 4:
                compare = atoi(key) - brg[mid].stok;
                break;
        }

        if (compare == 0)
            return mid;

        if (compare < 0)
            low = mid + 1;
        else
            high = mid - 1;
    }
    return -1;
}

int sequentialSearch(Barang brg[], int n, int kolom, char *key) {
    int i;
    for (i = 0; i < n; i++) {
        int compare = 0;

        switch (kolom) {
            case 1:
                compare = strcmp(brg[i].id, key);
                break;
            case 2:
                compare = strcasecmp(brg[i].nama, key);
                break;
            case 3:
                compare = strcmp(brg[i].kategori, key);
                break;
            case 4:
                compare = atoi(key) - brg[i].stok;
                break;
        }

        if (compare == 0)
            return i;
    }
    return -1;
}

// Amsal
/*=====================================================================================================================================*/
/*=====================================================================================================================================*/

int jumpSearch(Barang brg[], int n, int kolom, char *key) {
    int step = 3;
    int prev = 0;
    int i;

    while (prev < n &&
           ((kolom == 1 && strcmp(brg[(step < n ? step : n) - 1].id, key) < 0) ||
            (kolom == 2 && strcasecmp(brg[(step < n ? step : n) - 1].nama, key) < 0) ||
            (kolom == 3 && strcmp(brg[(step < n ? step : n) - 1].kategori, key) < 0))) {
        prev = step;
        step += 3;
        if (prev >= n)
            return -1;
    }

    // Linear search dalam blok
    for (i = prev; i < (step < n ? step : n); i++) {
        if ((kolom == 1 && strcmp(brg[i].id, key) == 0) ||
            (kolom == 2 && strcasecmp(brg[i].nama, key) == 0) ||
            (kolom == 3 && strcmp(brg[i].kategori, key) == 0)) {
            return i;
        }
    }
    return -1;
}

// Wildan
/*=====================================================================================================================================*/
/*=====================================================================================================================================*/

int main() {
    FILE *file;
    Barang DataBarang[max_lines];
    int count = 0;

    file = fopen("gudang.txt", "r");
    if (file == NULL) {
        perror("error opening file");
        getch();
        return 1;
    }

    while (count < max_lines && fscanf(file, "%14s %49s %29s %d",
                                       &DataBarang[count].id,
                                       &DataBarang[count].nama,
                                       &DataBarang[count].kategori,
                                       &DataBarang[count].stok) == 4) {
        count++;
    }

    fclose(file);

    // int b = sizeof(DataBarang) / sizeof(DataBarang[0]);
    int pilihan, kolom, ascending, flag;
    char key[50];

    do {
        do {
            system("cls");
            printf("Data Barang Sebelum diurutkan:\n");
            printDataBarang(DataBarang, count);
            printf("Menu Pengurutan:\n");
            printf("1. Bubble Sorting\n");
            printf("2. Selection Sorting\n");
            printf("3. Insertion Sorting\n");
            printf("4. Binary Search\n");
            printf("5. Sequential Search\n");
            printf("6. Jump Search\n");
            printf("7. Keluar\n");
            printf("Pilih opsi (1-7): ");
            scanf("%d", &pilihan);
            flag = 0;
            if (pilihan > 7) {
                printf("Pilihan Sorting Tidak Valid\n");
                flag = 1;
                system("pause");
            }

        } while (flag == 1);

        if (pilihan == 7) {
            printf("Keluar dari program.\n");
            break;
        }

        if (pilihan == 6) {
            printf("\nPilih kolom untuk pencarian:\n");
            printf("1. ID\n");
            printf("2. Nama barang\n");
            printf("3. kategori\n");
            printf("Pilihan (1-3): ");
            scanf("%d", &kolom);

            printf("\nMasukkan nilai yang ingin dicari: ");
            scanf(" %[^\n]", key);

            int index = jumpSearch(DataBarang, count, kolom, key);

            if (index != -1) {
                printf("\nData ditemukan:\n");
                printDataBarang(&DataBarang[index], 1);
            } else {
                printf("\nData tidak ditemukan.\n");
            }
            system("pause");
        }

        do {
            if (pilihan == 6) {
                break;
            }
            printf("\nPilih kolom untuk pengurutan:\n");
            printf("1. ID\n");
            printf("2. Nama Barang\n");
            printf("3. Kategori\n");
            printf("4. Stok\n");
            printf("Pilihan (1-4): ");
            scanf("%d", &kolom);
            flag = 0;
            if (kolom != 1 && kolom != 2 && kolom != 3 && kolom != 4) {
                printf("Pilihan Kolom Tidak Valid\n");
                flag = 1;
            }
        } while (flag == 1);

        do {
            if (pilihan == 6) {
                break;
            }
            printf("\nPilih tipe pengurutan:\n");
            printf("1. Ascending  \n");
            printf("2. Descending \n");
            printf("Pilihan tipe (1-2): ");
            scanf("%d", &ascending);
            flag = 0;
            if (ascending != 1 && ascending != 2) {
                printf("Pilihan Tipe Pengurutan Tidak Valid\n");
                flag = 1;
            }
            ascending = (ascending == 1);
        } while (flag == 1);

        switch (pilihan) {
            case 1:
                BubbleSort(DataBarang, count, kolom, ascending);
                break;
            case 2:
                SelectionSort(DataBarang, count, kolom, ascending);
                break;
            case 3:
                InsertionSort(DataBarang, count, kolom, ascending);
                break;
            case 4:  // Binary Search
                printf("\nPilih kolom untuk pencarian:\n");
                printf("1. ID\n2. Nama Barang\n3. Kategori\n4. Stok\n");
                printf("Pilihan (1-4): ");
                scanf("%d", &kolom);

                printf("\nMasukkan nilai yang ingin dicari: ");
                scanf(" %[^\n]", key);

                // Lakukan Sorting dulu sebelum Binary Search
                BubbleSort(DataBarang, count, kolom, 1);

                int binaryIndex = binarySearch(DataBarang, count, kolom, key);

                if (binaryIndex != -1) {
                    printf("\nData ditemukan:\n");
                    printDataBarang(&DataBarang[binaryIndex], 1);
                } else {
                    printf("\nData tidak ditemukan.\n");
                }
                system("pause");
                break;
            case 5:  // Sequential Search
                printf("\nPilih kolom untuk pencarian:\n");
                printf("1. ID\n2. Nama Barang\n3. Kategori\n4. Stok\n");
                printf("Pilihan (1-4): ");
                scanf("%d", &kolom);

                printf("\nMasukkan nilai yang ingin dicari: ");
                scanf(" %[^\n]", key);

                int seqIndex = sequentialSearch(DataBarang, count, kolom, key);

                if (seqIndex != -1) {
                    printf("\nData ditemukan:\n");
                    printDataBarang(&DataBarang[seqIndex], 1);
                } else {
                    printf("\nData tidak ditemukan.\n");
                }
                system("pause");
                break;
        }

        if (pilihan != 4) {
            printf("\nData Barang setelah diurutkan:\n");
            printDataBarang(DataBarang, count);
            system("pause");
        }

    } while (pilihan != 5);

    return 0;
}