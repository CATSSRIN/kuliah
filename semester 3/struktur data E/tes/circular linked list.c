#include <stdio.h>
#include <stdlib.h>

struct Node {
    int data;
    struct Node* next;
};

// Fungsi untuk membuat node baru
struct Node* createNode(int data) {
    struct Node* newNode = (struct Node*)malloc(sizeof(struct Node));
    newNode->data = data;
    newNode->next = newNode;  // Circular ke dirinya sendiri
    return newNode;
}

// Tambah node di akhir
void append(struct Node** tail, int data) {
    struct Node* newNode = createNode(data);

    if (*tail == NULL) {
        *tail = newNode;
    } else {
        newNode->next = (*tail)->next;
        (*tail)->next = newNode;
        *tail = newNode;
    }
}

// Cetak semua elemen dalam CLL
void printList(struct Node* tail) {
    if (tail == NULL) return;

    struct Node* temp = tail->next;
    do {
        printf("%d -> ", temp->data);
        temp = temp->next;
    } while (temp != tail->next);
    printf("(Circular)\n");
}

// Hapus node pertama
void popFront(struct Node** tail) {
    if (*tail == NULL) return;

    struct Node* head = (*tail)->next;
    if (head == *tail) {  // Hanya satu elemen
        free(head);
        *tail = NULL;
    } else {
        (*tail)->next = head->next;
        free(head);
    }
}

int main() {
    struct Node* tail = NULL;
    append(&tail, 10);
    append(&tail, 20);
    append(&tail, 30);

    printf("Circular Linked List:\n");
    printList(tail);

    printf("Hapus elemen pertama:\n");
    popFront(&tail);
    printList(tail);

    return 0;
}
