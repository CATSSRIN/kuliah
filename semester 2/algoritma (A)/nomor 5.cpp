#include <iostream>
using namespace std;

int main() {
    int N;
    char repeat;

    do {
        cout << "Masukkan nilai N: ";
        cin >> N;

        cout << "Bilangan genap dari 0 hingga " << N << " adalah: ";
        for (int i = 0; i <= N; i += 2) {
            cout << i << " ";
        }
        cout << endl;

        cout << "Apakah Anda ingin mengulangi? (y/n): ";
        cin >> repeat;
    } while (repeat == 'y' || repeat == 'Y');

    return 0;
}
