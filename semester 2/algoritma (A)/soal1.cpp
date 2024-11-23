#include <iostream>
using namespace std;

// Prototipe fungsi
double phi(int n);

// Definisi fungsi
double phi(int n) {
    double pi = 0.0;
    bool tambah = true;

    for (int i = 1; i <= n; i += 2) {
        if (tambah) {
            pi += 1.0 / i;
        }
        else {
            pi -= 1.0 / i;
        }
        tambah = !tambah;
    }

    return pi * 4;
}

int main() {
    int n;

    cout << "Masukkan jumlah suku n untuk pendekatan π: ";
    cin >> n;

    double pi = phi(n);
    cout << "Pendekatan nilai π menggunakan " << n << " suku adalah: " << pi << endl;

    return 0;
}