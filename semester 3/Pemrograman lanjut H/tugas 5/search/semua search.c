#include <stdio.h>
#include <stdlib.h>
#include <math.h>

// Function prototypes
int binarySearch(int arr[], int size, int key);
int interpolationSearch(int arr[], int size, int key);
int jumpSearch(int arr[], int size, int key);
int sequentialSearch(int arr[], int size, int key);

void printMenu();

int main() {
    int choice, key, result;
    int arr[] = {2, 3, 4, 10, 40, 50, 60, 70, 80, 90};
    int size = sizeof(arr) / sizeof(arr[0]);

    printMenu();
    printf("Enter your choice: ");
    scanf("%d", &choice);

    printf("Enter the element to search: ");
    scanf("%d", &key);

    switch (choice) {
        case 1:
            result = binarySearch(arr, size, key);
            break;
        case 2:
            result = interpolationSearch(arr, size, key);
            break;
        case 3:
            result = jumpSearch(arr, size, key);
            break;
        case 4:
            result = sequentialSearch(arr, size, key);
            break;
        default:
            printf("Invalid choice!\n");
            return 1;
    }

    if (result != -1)
        printf("Element found at index %d\n", result);
    else
        printf("Element not found\n");

    return 0;
}

void printMenu() {
    printf("Choose a search method:\n");
    printf("1. Binary Search\n");
    printf("2. Interpolation Search\n");
    printf("3. Jump Search\n");
    printf("4. Sequential Search\n");
}

int binarySearch(int arr[], int size, int key) {
    int left = 0, right = size - 1;
    while (left <= right) {
        int mid = left + (right - left) / 2;
        if (arr[mid] == key)
            return mid;
        if (arr[mid] < key)
            left = mid + 1;
        else
            right = mid - 1;
    }
    return -1;
}

int interpolationSearch(int arr[], int size, int key) {
    int low = 0, high = size - 1;
    while (low <= high && key >= arr[low] && key <= arr[high]) {
        if (low == high) {
            if (arr[low] == key) return low;
            return -1;
        }
        int pos = low + (((double)(high - low) / (arr[high] - arr[low])) * (key - arr[low]));
        if (arr[pos] == key)
            return pos;
        if (arr[pos] < key)
            low = pos + 1;
        else
            high = pos - 1;
    }
    return -1;
}

int jumpSearch(int arr[], int size, int key) {
    int step = sqrt(size);
    int prev = 0;
    while (arr[(int)fmin(step, size) - 1] < key) {
        prev = step;
        step += sqrt(size);
        if (prev >= size)
            return -1;
    }
    while (arr[prev] < key) {
        prev++;
        if (prev == fmin(step, size))
            return -1;
    }
    if (arr[prev] == key)
        return prev;
    return -1;
}

int sequentialSearch(int arr[], int size, int key) {
    for (int i = 0; i < size; i++) {
        if (arr[i] == key)
            return i;
    }
    return -1;
}