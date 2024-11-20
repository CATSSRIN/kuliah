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
    newNode->next = newNode;  // Point to itself initially
    return newNode;
}

// Add a node at the end
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

// Print the circular linked list
void printList(struct Node* tail) {
    if (tail == NULL) return;
    struct Node* temp = tail->next;
    do {
        printf("%d -> ", temp->data);
        temp = temp->next;
    } while (temp != tail->next);
    printf("(Back to Start)\n");
}

// Example with preloaded data
int main() {
    struct Node* tail = NULL;

    // Preloaded data
    append(&tail, 10);
    append(&tail, 20);
    append(&tail, 30);

    printf("Circular Linked List:\n");
    printList(tail);

    return 0;
}
