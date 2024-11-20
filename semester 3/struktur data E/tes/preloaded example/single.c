#include <stdio.h>
#include <stdlib.h>

struct Node {
    int data;
    struct Node* next;
};

// Function to create a new node
struct Node* createNode(int data) {
    struct Node* newNode = (struct Node*)malloc(sizeof(struct Node));
    newNode->data = data;
    newNode->next = NULL;
    return newNode;
}

// Add a node at the front
void pushFront(struct Node** head, int data) {
    struct Node* newNode = createNode(data);
    newNode->next = *head;
    *head = newNode;
}

// Print the linked list
void printList(struct Node* head) {
    while (head != NULL) {
        printf("%d -> ", head->data);
        head = head->next;
    }
    printf("NULL\n");
}

// Example with preloaded data
int main() {
    struct Node* head = NULL;

    // Preloaded data
    pushFront(&head, 30);
    pushFront(&head, 20);
    pushFront(&head, 10);

    printf("Singly Linked List:\n");
    printList(head);

    return 0;
}
