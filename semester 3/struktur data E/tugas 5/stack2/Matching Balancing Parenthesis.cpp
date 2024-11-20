#include <stdio.h>
#include <stack>
#include <string.h>

int isValidParentheses(const char *expr) {
    std::stack<char> stack;
    for (int i = 0; expr[i]; i++) {
        if (expr[i] == '(' || expr[i] == '{' || expr[i] == '[' || expr[i] == '<') {
            stack.push(expr[i]);
        } else if ((expr[i] == ')' && stack.top() == '(') ||
                   (expr[i] == '}' && stack.top() == '{') ||
                   (expr[i] == ']' && stack.top() == '[') ||
                   (expr[i] == '>' && stack.top() == '<')) {
            stack.pop();
        } else {
            return 0; 
        }
    }
    return stack.empty() ? 1 : 0; 
}

int main() {
    printf("%d\n", isValidParentheses("({[<>]})"));  
    return 0;
}
