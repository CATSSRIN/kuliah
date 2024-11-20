#include <iostream>
#include <stack>
#include <unordered_map>
#include <string>
#include <cstdio> 

using namespace std;

bool isValidCode(const string& code) {
    unordered_map<char, char> bracket_pairs = {
        {')', '('}, 
        {'}', '{'}, 
        {']', '['}, 
        {'>', '<'}
    };

    stack<char> stack; 

    for (char ch : code) {
        if (ch == '(' || ch == '{' || ch == '[' || ch == '<') {
            stack.push(ch);
        }
        else if (ch == ')' || ch == '}' || ch == ']' || ch == '>') {
            if (stack.empty() || stack.top() != bracket_pairs[ch]) {
                return false; 
            }
            stack.pop(); 
        }
    }

    return stack.empty();
}

int main() {
    
    string contoh_code = R"(
Object obj (in inputParams, out outParams)
{
    <Blob> myInf = new Blob(function(){
               data : [
                {
                    type: image,
                    size: 100,
                    bg: transparent
                }
              ]
            })
            
    run process_img(){
        this.myInf.blobToImage({
            type: jpg
        })
    }
}
)";

    
    if (isValidCode(contoh_code)) {
        printf("Kode valid!\n");  
    } else {
        printf("Kode tidak valid!\n");  
    }

    return 0;
}
