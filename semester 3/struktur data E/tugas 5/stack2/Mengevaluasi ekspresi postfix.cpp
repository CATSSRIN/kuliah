#include <stdio.h>
#include <stdlib.h>
#include <ctype.h>

int evaluatePostfix(const char *expr) {
    int stack[100], top = -1;

    for (int i = 0; expr[i]; i++) {
        if (isdigit(expr[i])) {
            stack[++top] = expr[i] - '0';
        } else {
            int b = stack[top--];
            int a = stack[top--];
            if (expr[i] == '+') stack[++top] = a + b;
            if (expr[i] == '-') stack[++top] = a - b;
            if (expr[i] == '*') stack[++top] = a * b;
            if (expr[i] == '/') stack[++top] = a / b;
        }
    }

    return stack[top];
}

int main() {
    printf("%d\n", evaluatePostfix("23*5+"));  
    return 0;
}
