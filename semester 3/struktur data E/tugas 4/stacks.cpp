#include <iostream>

struct Node {
    int data;
    Node* next;
};

struct Stack {
    int count;
    Node* top;

    Stack() : count(0), top(nullptr) {}

    // Function to check if the stack is empty
    bool isEmpty() {
        return top == nullptr;
    }

    // Function to check if memory is available
    bool isMemoryAvailable() {
        Node* test = new(std::nothrow) Node;
        if (test) {
            delete test;
            return true;
        }
        return false;
    }

    // Function to push a value onto the stack
    bool push(int data) {
        if (!isMemoryAvailable()) {
            printf("Push failed: No memory available.\n");
            return false;
        }
        Node* newptr = new Node;
        newptr->data = data;
        newptr->next = top;
        top = newptr;
        count++;
        printf("Pushed %d onto the stack.\n", data);
        return true;
    }

    // Function to pop a value from the stack
    bool pop(int &dataOut) {
        if (isEmpty()) {
            printf("Pop failed: Stack is empty.\n");
            return false;
        }
        Node* dltptr = top;
        dataOut = top->data;
        top = top->next;
        delete dltptr;
        count--;
        printf("Popped %d from the stack.\n", dataOut);
        return true;
    }

    // Function to peek the top value of the stack
    bool peek(int &dataOut) {
        if (isEmpty()) {
            printf("Peek failed: Stack is empty.\n");
            return false;
        }
        dataOut = top->data;
        printf("Peeked top value: %d.\n", dataOut);
        return true;
    }

    // Function to get the current count of elements in the stack
    int getCount() {
        return count;
    }

    // Function to clear the stack
    void clear() {
        while (top != nullptr) {
            Node* temp = top;
            top = top->next;
            delete temp;
        }
        count = 0;
        printf("Stack cleared.\n");
    }
};

int main() {
    Stack stack;
    int dataOut;

    stack.push(10);
    stack.push(20);
    stack.push(30);

    stack.peek(dataOut);
    stack.pop(dataOut);

    printf("Current stack count: %d\n", stack.getCount());

    stack.clear();
    printf("Stack empty status: %s\n", stack.isEmpty() ? "true" : "false");

    return 0;
}
