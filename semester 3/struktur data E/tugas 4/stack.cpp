#include <stdio.h>
#include <stdlib.h>
#include <conio.h>

#define MAX 100  

typedef struct {
    int items[MAX];
    int top;
} Stack;

// Function prototypes
void initStack(Stack *s);
int isEmpty(Stack *s);
int isFull(Stack *s);
void push(Stack *s, int value);
int pop(Stack *s);
int stackTop(Stack *s);
int stackCount(Stack *s);
void destroyStack(Stack *s);
void printStack(Stack *s);

int main() {
    Stack stack;
    char choice;
    int value;

    initStack(&stack);

    do {
        system("cls");
        printf("Operasi Stack:\n");
        printf("1. Push to Stack\n");
        printf("2. Pop from Stack\n");
        printf("3. Stack Top\n");
        printf("4. Check if Stack is Empty\n");
        printf("5. Check if Stack is Full\n");
        printf("6. Stack Count\n");
        printf("7. Destroy Stack\n");
        printf("8. Print Stack\n");
        printf("Enter choice (press 'q' to quit): ");
        fflush(stdin);
        scanf(" %c", &choice);

        switch (choice) {
            case '1':
                printf("Enter value to push: ");
                scanf("%d", &value);
                push(&stack, value);
                break;
            case '2':
                value = pop(&stack);
                if (value != -1)
                    printf("Popped value: %d\n", value);
                getch();
                break;
            case '3':
                value = stackTop(&stack);
                if (value != -1)
                    printf("Stack top: %d\n", value);
                getch();
                break;
            case '4':
                printf("Stack is %sempty\n", isEmpty(&stack) ? "" : "not ");
                getch();
                break;
            case '5':
                printf("Stack is %sfull\n", isFull(&stack) ? "" : "not ");
                getch();
                break;
            case '6':
                printf("Stack count: %d\n", stackCount(&stack));
                getch();
                break;
            case '7':
                destroyStack(&stack);
                printf("Stack destroyed\n");
                getch();
                break;
            case '8':
                printStack(&stack);
                getch();
                break;
            case 'q':
                break;
            default:
                printf("Invalid choice!\n");
                getch();
        }
    } while (choice != 'q');

    return 0;
}

// Initialize the stack
void initStack(Stack *s) {
    s->top = -1;
}

// Check if the stack is empty
int isEmpty(Stack *s) {
    return s->top == -1;
}

// Check if the stack is full
int isFull(Stack *s) {
    return s->top == MAX - 1;
}

// Push a value onto the stack
void push(Stack *s, int value) {
    if (isFull(s)) {
        printf("Stack is full. Cannot push.\n");
        getch();
    } else {
        s->items[++(s->top)] = value;
        printf("Pushed %d to stack\n", value);
        getch();
    }
}

// Pop a value from the stack
int pop(Stack *s) {
    if (isEmpty(s)) {
        printf("Stack is empty. Cannot pop.\n");
        return -1;
    } else {
        return s->items[(s->top)--];
    }
}

// Get the top value of the stack
int stackTop(Stack *s) {
    if (isEmpty(s)) {
        printf("Stack is empty. No top element.\n");
        return -1;
    } else {
        return s->items[s->top];
    }
}

// Get the count of elements in the stack
int stackCount(Stack *s) {
    return s->top + 1;
}

// Destroy the stack by resetting the top index
void destroyStack(Stack *s) {
    s->top = -1;
}

// Print all elements in the stack
void printStack(Stack *s) {
    if (isEmpty(s)) {
        printf("Stack is empty.\n");
    } else {
        printf("Stack elements:\n");
        for (int i = s->top; i >= 0; i--) {
            printf("%d\n", s->items[i]);
        }
    }
}
