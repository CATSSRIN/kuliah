#include <stdio.h>
#include <stdlib.h>
#include <string.h>

// ========================================================
#define MAX_ORANG 20

struct node {
    char nama[30];
    char jenis_kelamin;
    struct node *next;
};
typedef struct node node;

void tambahData(node **head, int *jumlah);
void hapusData(node **head, int *jumlah);
void cetakList(node *head);
void jumlahData(node *head);
int cekNama(node *head, const char *nama);

// ========================================================

int main() {
    node *laki = NULL, *mahasiswi = NULL;
    int jumlahLaki = 0, jumlahmahasiswi = 0;
    char pilih;

    do {
        #ifdef _WIN32
            system("cls");
        #else
            system("clear");
        #endif

        printf("Pilih operasi:\n");
        printf("1. Tambah ke lingkaran 1\n");
        printf("2. Tambah ke lingkaran 2\n");
        printf("3. Hapus mahasiswa\n");
        printf("4. Cetak lingkaran\n");
        printf("5. Jumlah anggota dalam lingkaran\n");
        printf("q. Keluar\n");
        printf("Ketik nomor pilihan: ");
        fflush(stdin);
        scanf(" %c", &pilih);

        if (pilih == '1') {
            if (jumlahLaki + jumlahmahasiswi < MAX_ORANG) {
                tambahData(&laki, &jumlahLaki);
            } else {
                printf("Lingkaran penuh! tidak bisa menambahkan orang\n");
                getchar();
            }
        } else if (pilih == '2') {
            if (cekNama(mahasiswi, "Cindy")) {
                printf("Cindy tidak bisa masuk jika ada mahasiswa\n");
            } else if (jumlahLaki + jumlahmahasiswi < MAX_ORANG) {
                tambahData(&mahasiswi, &jumlahmahasiswi);
            } else {
                printf("Lingkaran penuh! Tidak bisa menambahkan orang\n");
                getchar();
            }
        } else if (pilih == '3') {
            hapusData(&laki, &jumlahLaki);
            hapusData(&mahasiswi, &jumlahmahasiswi);
        } else if (pilih == '4') {
            printf("\nLingkaran mahasiswa:\n");
            cetakList(laki);
            printf("\nLingkaran mahasiswi:\n");
            cetakList(mahasiswi);
            getchar();
        } else if (pilih == '5') {
            printf("\nJumlah mahasiswa: %d\n", jumlahLaki);
            printf("Jumlah mahasiswi: %d\n", jumlahmahasiswi);
            printf("Total: %d\n", jumlahLaki + jumlahmahasiswi);
            getchar();
        }

    } while (pilih != 'q');

    return 0;
}

// ========================================================

void tambahData(node **head, int *jumlah) {
    char nama[50];
    char jenis_kelamin;
    node *pNew, *pCur;

    system("clear");
    fflush(stdin);
    printf("Masukkan nama: ");
    scanf("%s", nama);
    printf("Jenis kelamin (L/P): ");
    fflush(stdin);
    scanf(" %c", &jenis_kelamin);

    pNew = (node *)malloc(sizeof(node));
    if (pNew != NULL) {
        strcpy(pNew->nama, nama);
        pNew->jenis_kelamin = jenis_kelamin;
        pNew->next = NULL;

        if (*head == NULL) {
            *head = pNew;
            pNew->next = *head;
        } else {
            pCur = *head;
            while (pCur->next != *head) {
                pCur = pCur->next;
            }
            pCur->next = pNew;
            pNew->next = *head;
        }
        (*jumlah)++;
    } else {
        printf("gagal input data\n");
        getchar();
    }
}

// ========================================================

void hapusData(node **head, int *jumlah) {
    char nama[50];
    node *pCur, *pPrev;

    if (*head == NULL) {
        printf("Lingkaran kosong.\n");
        return;
    }

    printf("Masukkan nama yang ingin dihapus: ");
    scanf("%s", nama);

    pCur = *head;
    pPrev = NULL;

    do {
        if (strcmp(pCur->nama, nama) == 0) {
            if (pCur == *head) {
                if ((*head)->next == *head) {
                    *head = NULL;
                } else {
                    node *last = *head;
                    while (last->next != *head) {
                        last = last->next;
                    }
                    last->next = (*head)->next;
                    *head = (*head)->next;
                }
            } else {
                pPrev->next = pCur->next;
            }
            free(pCur);
            (*jumlah)--;
            printf("Data berhasil dihapus.\n");
            return;
        }
        pPrev = pCur;
        pCur = pCur->next;
    } while (pCur != *head);

    printf("Nama tidak ditemukan.\n");
}

// ========================================================

void cetakList(node *head) {
    node *pWalker;

    if (head == NULL) {
        printf("Lingkaran kosong.\n");
        return;
    }

    pWalker = head;
    do {
        printf("%s (%c) -> ", pWalker->nama, pWalker->jenis_kelamin);
        pWalker = pWalker->next;
    } while (pWalker != head);
    printf("Kembali ke awal\n");
}

int cekNama(node *head, const char *nama) {
    node *pCur = head;

    if (head == NULL) return 0;

    do {
        if (strcmp(pCur->nama, nama) == 0) {
            return 1;
        }
        pCur = pCur->next;
    } while (pCur != head);

    return 0;
}
