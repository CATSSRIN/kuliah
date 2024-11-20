#include <iostream>
using namespace std;

bool searchNumber(int arr[], int size, int num) {
    for (int i = 0; i < size; i++) {
        if (arr[i] == num) {
            return true;
        }
    }
    return false;
}

int main() {
    int arr[] = {67, 5, 1, 90, 27, 8, 12};
    int size = sizeof(arr) / sizeof(arr[0]);
    int num;

    printf("Enter a number to search: ");
    scanf("%d", &num);

    if (searchNumber(arr, size, num)) {
        printf("Yes\n");
    } else {
        printf("No\n");
    }

    return 0;
}
