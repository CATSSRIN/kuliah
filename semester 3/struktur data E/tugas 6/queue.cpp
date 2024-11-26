#include <stdio.h>
#include <stdlib.h>

//========================================================

struct node {
    int data;
    struct node *next;
};
typedef struct node node;

void nambah(node **head, node **tail);
void mengurangi(node **head, node **tail);
void print_all(node *head);

//========================================================

int main()
{
    node *head, *tail;
    char pilih;

    head = tail = NULL;
    do {
        printf("\nmasukkan pilihan\n");
        printf("1. Tambahkan nomor\n");
        printf("2. Habus nomor depan\n");
        printf("3. cetak isi queue\n");
        printf("MASUKKAN PILIHAN (tekan q untuk keluar) : ");
        fflush(stdin);
        scanf(" %c", &pilih);
        if (pilih == '1')
             nambah(&head, &tail);
        else if (pilih == '2')
            mengurangi(&head, &tail);
        else if (pilih == '3') {
            print_all(head);
            getchar();  
        }
    } while (pilih != 'q');

    return 0;
}

//========================================================

void nambah(node **head, node **tail) {
    int bil;
    node *pNew;

    printf("masukkan bilangan : ");
    fflush(stdin);
    scanf("%d", &bil);
    pNew = (node *)malloc(sizeof(node));

    if (pNew != NULL) {
        pNew->data = bil;
        pNew->next = NULL;
        if (*tail != NULL) {
            (*tail)->next = pNew;
        }
        *tail = pNew;
        if (*head == NULL) {
            *head = pNew;
        }
    } else {
        printf("Alokasi memori gagal");
        getchar();  
    }
}

//========================================================

void mengurangi(node **head, node **tail) {
    node *pDel;

    if (*head != NULL) {
        pDel = *head;
        *head = (*head)->next;
        if (*head == NULL) {
            *tail = NULL;
        }
        free(pDel);
    } else {
        printf("Queue kosong");
        getchar();  
    }
}

//========================================================

void print_all(node *head) {
    node *pWalker;

    pWalker = head;
    while (pWalker != NULL) {
        printf("%d -> ", pWalker->data);
        pWalker = pWalker->next;
    }
    printf("NULL\n");
}
