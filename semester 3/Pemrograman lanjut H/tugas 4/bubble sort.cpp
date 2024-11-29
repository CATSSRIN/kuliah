#include <stdio.h>

void bubbleSort(int arr[], int n, int ascending) {
    for (int i = 0; i < n - 1; ++i) {
        for (int j = 0; j < n - i - 1; ++j) {
            if (ascending ? (arr[j] > arr[j + 1]) : (arr[j] < arr[j + 1])) {
                int temp = arr[j];
                arr[j] = arr[j + 1];
                arr[j + 1] = temp;
            }
        }
    }
}

void printArray(int arr[], int n) {
    for (int i = 0; i < n; ++i) {
        printf("%d ", arr[i]);
    }
    printf("\n");
}

int main() {
    int numbers[] = {64, 34, 25, 12, 22, 11, 90};
    int n = sizeof(numbers) / sizeof(numbers[0]);
    char choice;
    int ascending;

    printf("Choose sorting order (a for ascending, d for descending): ");
    scanf(" %c", &choice);

    if (choice == 'a' || choice == 'A') {
        ascending = 1;
    } else if (choice == 'd' || choice == 'D') {
        ascending = 0;
    } else {
        fprintf(stderr, "Invalid choice!\n");
        return 1;
    }

    bubbleSort(numbers, n, ascending);

    printf("Sorted array: ");
    printArray(numbers, n);

    return 0;
}