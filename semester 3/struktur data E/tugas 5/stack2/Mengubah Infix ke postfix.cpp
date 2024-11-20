#include <stdio.h>
#include <stdlib.h>
#include <ctype.h>

int precedence(char c) {
    if (c == '+' || c == '-') return 1;
    if (c == '*' || c == '/') return 2;
    return 0;
}

void infixToPostfix(const char *expr, char *result) {
    char stack[100], top = -1;
    int j = 0;

    for (int i = 0; expr[i]; i++) {
        if (isdigit(expr[i])) {
            result[j++] = expr[i];
        } else if (expr[i] == '(') {
            stack[++top] = expr[i];
        } else if (expr[i] == ')') {
            while (top >= 0 && stack[top] != '(') {
                result[j++] = stack[top--];
            }
            top--;  
        } else if (expr[i] == '+' || expr[i] == '-' || expr[i] == '*' || expr[i] == '/') {
            while (top >= 0 && precedence(stack[top]) >= precedence(expr[i])) {
                result[j++] = stack[top--];
            }
            stack[++top] = expr[i];
        }
    }

    while (top >= 0) result[j++] = stack[top--];
    result[j] = '\0';
}

int main() {
    char result[100];
    infixToPostfix("3+(2*5)", result);
    printf("%s\n", result);  
    return 0;
}
