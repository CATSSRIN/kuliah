#include <iostream>
using namespace std;

const int MAX_SIZE = 100;

typedef int tabGol[MAX_SIZE];

void inputData(tabGol &t, int &n) {
    // TODO: Implement the inputData function
}

float rataan(tabGol t, int n) {
    // TODO: Implement the rataan function
}

int main() {
    tabGol t;
    int n;

    // Input data
    for (int i = 0; i < 3; i++) {
        inputData(t, n);
    }

    // Calculate and display the average
    for (int i = 0; i < 3; i++) {
        float average = rataan(t, n);
        cout << "Average for team " << i+1 << ": " << average << endl;
    }

    return 0;
}