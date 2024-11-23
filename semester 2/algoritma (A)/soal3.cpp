#include <iostream>
using namespace std;

// Prototipe fungsi
void sum_n_avg(const double arr[], int n, double& sum, double& avg);

// Function (sum_n_avg)
void sum_n_avg(const double arr[], int n, double& sum, double& avg) {
    sum = 0.0;
    for (int i = 0; i < n; ++i) {
        sum += arr[i];
    }
    avg = sum / n;
}

int main() {
    const double data[] = { 10.5, 20.5, 30.5, 40.5, 50.5 };
    int n = sizeof(data) / sizeof(data[0]);
    double sum, avg;

    sum_n_avg(data, n, sum, avg);

    cout << "Jumlah: " << sum << endl;
    cout << "Rata-rata: " << avg << endl;

    return 0;
}
