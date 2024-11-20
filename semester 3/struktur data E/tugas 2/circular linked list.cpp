#include <stdio.h>
#include <stdlib.h> 

//========================================================

struct node {
    int data;
    struct node *next;
};
typedef struct node node;

void tambahAwal(node **head);
void tambahData(node **head);
void tambahAkhir(node **head);
void hapusAwal(node **head);
void hapusTengah(node **head);
void hapusAkhir(node **head);
void cariData(node *head);
void jumlahData(node *head);
void sumData(node *head);
void tranverse(node *head);

//========================================================

int main()
{
    node *head;
    char pilih;

    head = NULL;
    do {
        #ifdef _WIN32
            system("cls");
        #else
            system("clear");
        #endif

        printf("masukkan pilihan\n");
        printf("1. tambah data di awal\n");
        printf("2. tambah data di tengah list\n");
        printf("3. tambah data di akhir list\n");
        printf("4. cetak isi list\n");
        printf("5. hapus data di awal\n");
        printf("6. hapus data di tengah\n");
        printf("7. hapus data di akhir\n");
        printf("8. cari data dalam list\n");
        printf("9. jumlah data dalam list\n");
        printf("0. jumlahkan semua data dalam list\n");
        printf("MASUKKAN PILIHAN (tekan q untuk keluar) : ");
        fflush(stdin);
        scanf(" %c", &pilih);

        if (pilih == '1')
            tambahAwal(&head);
        else if (pilih == '2')
            tambahData(&head);
        else if (pilih == '3')
            tambahAkhir(&head);
        else if (pilih == '4') {
            tranverse(head);
            getchar();
        }
        else if (pilih == '5')
            hapusAwal(&head);
        else if (pilih == '6')
            hapusTengah(&head);
        else if (pilih == '7')
            hapusAkhir(&head);
        else if (pilih == '8') {
            cariData(head);
            getchar();
        }
        else if (pilih == '9') {
            jumlahData(head);
            getchar();
        }
        else if (pilih == '0') {
            sumData(head);
            getchar();
        }
    } while (pilih != 'q');

    return 0;
}

//========================================================

void tambahAwal(node **head) {
    int bil;
    node *pNew, *pCur;

    system("clear");
    fflush(stdin);
    printf("masukkan bilangan : ");
    fflush(stdin);
    scanf("%d", &bil);

    pNew = (node *)malloc(sizeof(node));

    if (pNew != NULL) {
        pNew->data = bil;
        if (*head == NULL) {
            pNew->next = pNew; 
        } else {
            pCur = *head;
            while (pCur->next != *head) { 
                pCur = pCur->next;
            }
            pCur->next = pNew; 
            pNew->next = *head; 
        }
        *head = pNew; 
    } else {
        printf("Alokasi memori gagal");
        getchar();
    }
}

//========================================================

void tambahData(node **head) {
    int pos, bil;
    node *pNew, *pCur;

    system("clear");
    tranverse(*head);
    printf("\nposisi penyisipan setelah data bernilai : ");
    fflush(stdin);
    scanf("%d", &pos);
    printf("\nbilangan : ");
    fflush(stdin);
    scanf("%d", &bil);

    pCur = *head;
    do {
        if (pCur->data == pos) {
            break;
        }
        pCur = pCur->next;
    } while (pCur != *head); 

    pNew = (node *)malloc(sizeof(node));

    if (pCur == NULL || pCur == *head && (*head)->data != pos) {
        printf("\nnode tidak ditemukan");
        getchar();
    } else if (pNew == NULL) {
        printf("\nalokasi memori gagal");
        getchar();
    } else {
        pNew->data = bil;
        pNew->next = pCur->next;
        pCur->next = pNew;
    }
}

//========================================================

void tambahAkhir(node **head) {
    int bil;
    node *pNew, *pCur;

    system("clear");
    fflush(stdin);
    printf("masukkan bilangan : ");
    fflush(stdin);
    scanf("%d", &bil);

    pNew = (node *)malloc(sizeof(node));
    if (pNew != NULL) {
        pNew->data = bil;

        if (*head == NULL) {
            pNew->next = pNew; 
            *head = pNew;
        } else {
            pCur = *head;
            while (pCur->next != *head) {
                pCur = pCur->next;
            }
            pCur->next = pNew;
            pNew->next = *head;
        }
    } else {
        printf("Alokasi memori gagal");
        getchar();
    }
}

//========================================================

void hapusAwal(node **head) {
    node *pDel, *pCur;

    system("clear");
    if (*head != NULL) {
        if ((*head)->next == *head) {
            pDel = *head;
            *head = NULL;
        } else {
            pDel = *head;
            pCur = *head;
            while (pCur->next != *head) {
                pCur = pCur->next;
            }
            pCur->next = (*head)->next;
            *head = (*head)->next;
        }
        free(pDel);
        printf("Data pertama berhasil dihapus\n");
    } else {
        printf("List kosong\n");
    }
    getchar();
}

//========================================================

void hapusTengah(node **head) {
    int pos;
    node *pCur, *pPrev;

    system("clear");
    tranverse(*head);
    printf("\nMasukkan data yang ingin dihapus : ");
    fflush(stdin);
    scanf("%d", &pos);

    pCur = *head;
    pPrev = NULL;

    do {
        if (pCur->data == pos) {
            break;
        }
        pPrev = pCur;
        pCur = pCur->next;
    } while (pCur != *head);

    if (pCur == *head && pCur->data != pos) {
        printf("Data tidak ditemukan\n");
    } else {
        if (pPrev != NULL)
            pPrev->next = pCur->next;
        free(pCur);
        printf("Data berhasil dihapus\n");
    }
    getchar();
}

//========================================================

void hapusAkhir(node **head) {
    node *pCur, *pPrev;

    system("clear");
    if (*head != NULL) {
        pCur = *head;
        pPrev = NULL;

        while (pCur->next != *head) {
            pPrev = pCur;
            pCur = pCur->next;
        }

        if (pPrev != NULL)
            pPrev->next = *head;
        else
            *head = NULL;

        free(pCur);
        printf("Data terakhir berhasil dihapus\n");
    } else {
        printf("List kosong\n");
    }
    getchar();
}

//========================================================

void cariData(node *head) {
    int bil;
    node *pCur;

    system("clear");
    printf("Masukkan bilangan yang ingin dicari: ");
    fflush(stdin);
    scanf("%d", &bil);

    pCur = head;
    do {
        if (pCur->data == bil) {
            printf("Data ditemukan: %d\n", bil);
            return;
        }
        pCur = pCur->next;
    } while (pCur != head);

    printf("Data tidak ditemukan\n");
}

//========================================================

void jumlahData(node *head) {
    int count = 0;
    node *pCur = head;

    if (head != NULL) {
        do {
            count++;
            pCur = pCur->next;
        } while (pCur != head);
    }

    printf("Jumlah data dalam list: %d\n", count);
}

//========================================================

void sumData(node *head) {
    int sum = 0;
    node *pCur = head;

    if (head != NULL) {
        do {
            sum += pCur->data;
            pCur = pCur->next;
        } while (pCur != head);
    }

    printf("Jumlah total dari semua data: %d\n", sum);
}

//========================================================

void tranverse(node *head) {
    node *pWalker;

    system("clear");
    if (head != NULL) {
        pWalker = head;
        do {
            printf("%d\t", pWalker->data);
            pWalker = pWalker->next;
            printf(" -> ");
        } while (pWalker != head);
    }
    printf("NULL");
}
