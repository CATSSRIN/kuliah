#include <iostream>
#include <cmath>
using namespace std;

// Prototipe function
double P(double x, int n);

// Function (rekursif)
double P(double x, int n) {
    if (n == 0)
        return 1;
    else if (n % 2 != 0)
        return x * pow(P(x, n / 2), 2);
    else
        return pow(P(x, n / 2), 2);
}

int main() {
    double x = 2.0;
    int n = 11;

    double result = P(x, n);

    cout << "P(" << x << ", " << n << ") = " << result << endl;

    return 0;
}