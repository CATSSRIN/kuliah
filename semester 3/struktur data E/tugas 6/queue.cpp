#include <stdio.h>
#include <stdlib.h>

struct node {
    int data;
    struct node *next;
};

typedef struct node node;

void enqueue(node **front, node **rear);
void dequeue(node **front, node **rear);
void printQueue(node *front);

int main() {
    node *front = NULL, *rear = NULL;
    char pilih;
    do {
        system("cls");
        printf("Masukkan pilihan:\n");
        printf("1. Enqueue (Tambah data ke belakang)\n");
        printf("2. Dequeue (Hapus data dari depan)\n");
        printf("3. Cetak isi queue\n");
        printf("Masukkan pilihan (ketik q untuk keluar): ");
        fflush(stdin);

        scanf("%c", &pilih);
        if (pilih == '1')
            enqueue(&front, &rear);
        else if (pilih == '2')
            dequeue(&front, &rear);
        else if (pilih == '3')
            printQueue(front);
        getch();
    } while (pilih != 'q');

    return 0;
}

void enqueue(node **front, node **rear) {
    int bil;
    node *pNew;

    system("cls");
    printf("Masukkan bilangan: ");
    fflush(stdin);
    scanf("%d", &bil);

    pNew = (node *)malloc(sizeof(node));

    if (pNew != NULL) {
        pNew->data = bil;
        pNew->next = NULL;

        if (*rear == NULL) {
            // If queue is empty, both front and rear point to the new node
            *front = *rear = pNew;
        } else {
            // Add the new node at the end (rear)
            (*rear)->next = pNew;
            *rear = pNew;
        }
    } else {
        printf("Alokasi memori gagal!\n");
        getch();
    }
}

void dequeue(node **front, node **rear) {
    node *temp;

    system("cls");
    if (*front == NULL) {
        printf("Queue kosong! Tidak dapat dequeue.\n");
    } else {
        // Remove the front node
        temp = *front;
        *front = (*front)->next;
        if (*front == NULL) {
            // If queue becomes empty, set rear to NULL as well
            *rear = NULL;
        }
        printf("Data yang dikeluarkan: %d\n", temp->data);
        free(temp);
    }
    getch();
}

void printQueue(node *front) {
    node *pWalker;

    system("cls");
    if (front == NULL) {
        printf("Queue kosong!\n");
    } else {
        printf("Isi queue: ");
        pWalker = front;
        while (pWalker != NULL) {
            printf("%d -> ", pWalker->data);
            pWalker = pWalker->next;
        }
        printf("NULL\n");
    }
}
