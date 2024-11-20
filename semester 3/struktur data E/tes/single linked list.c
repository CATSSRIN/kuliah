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
    newNode->next = NULL;
    return newNode;
}

// Tambah node di awal
void pushFront(struct Node** head, int data) {
    struct Node* newNode = createNode(data);
    newNode->next = *head;
    *head = newNode;
}

// Cetak semua elemen dalam SLL
void printList(struct Node* head) {
    while (head != NULL) {
        printf("%d -> ", head->data);
        head = head->next;
    }
    printf("NULL\n");
}

// Hapus node pertama
void popFront(struct Node** head) {
    if (*head == NULL) return;
    struct Node* temp = *head;
    *head = (*head)->next;
    free(temp);
}

// Cari elemen dalam SLL
struct Node* search(struct Node* head, int key) {
    while (head != NULL) {
        if (head->data == key) return head;
        head = head->next;
    }
    return NULL;
}

int main() {
    struct Node* head = NULL;
    pushFront(&head, 10);
    pushFront(&head, 20);
    pushFront(&head, 30);

    printf("Singly Linked List:\n");
    printList(head);

    printf("Hapus elemen pertama:\n");
    popFront(&head);
    printList(head);

    struct Node* found = search(head, 20);
    if (found)
        printf("Elemen %d ditemukan.\n", found->data);
    else
        printf("Elemen tidak ditemukan.\n");

    return 0;
}
