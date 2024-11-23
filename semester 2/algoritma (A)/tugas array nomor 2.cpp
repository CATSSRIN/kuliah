#include <iostream>

const int ARRAY_SIZE = 256;

void reverseArray(int arr[], int size) {
    int start = 0;
    int end = size - 1;
    
    while (start < end) {
        int temp = arr[start];
        arr[start] = arr[end];
        arr[end] = temp;
        
        start++;
        end--;
    }
}

bool isPalindrome(int arr[], int size) {
    int start = 0;
    int end = size - 1;
    
    while (start < end) {
        if (arr[start] != arr[end]) {
            return false;
        }
        
        start++;
        end--;
    }
    
    return true;
}

void fillOddEvenArrays(int arr[], int size, int oddArr[], int& oddSize, int evenArr[], int& evenSize) {
    oddSize = 0;
    evenSize = 0;
    
    for (int i = 0; i < size; i++) {
        if (arr[i] % 2 == 0) {
            evenArr[evenSize] = arr[i];
            evenSize++;
        } else {
            oddArr[oddSize] = arr[i];
            oddSize++;
        }
    }
}

int main() {
    int arr[ARRAY_SIZE];
    int oddArr[ARRAY_SIZE];
    int evenArr[ARRAY_SIZE];
    int size = ARRAY_SIZE;
    int oddSize = 0;
    int evenSize = 0;
    
    // Fill the array with values
    for (int i = 0; i < size; i++) {
        std::cout << "Enter a value for index " << i << ": ";
        std::cin >> arr[i];
    }
    
    // Reverse the array
    reverseArray(arr, size);
    
    // Check if the array is a palindrome
    bool isPal = isPalindrome(arr, size);
    
    // Fill the odd and even arrays
    fillOddEvenArrays(arr, size, oddArr, oddSize, evenArr, evenSize);
    
    return 0;
}