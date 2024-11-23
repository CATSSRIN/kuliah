#include <iostream>
using namespace std;

int main() {
    int bilangan, pembagi;
    cout << "Masukkan bilangan: ";
    cin >> bilangan;
    cout << "Masukkan pembagi: ";
    cin >> pembagi;

    int sisaBagi = bilangan - (bilangan / pembagi) * pembagi;

    if (sisaBagi == 0) {
        cout << "Tidak ada sisa bagi" << endl;
    } else {
        cout << "Sisa bagi: " << sisaBagi << endl;
    }

    return 0;
}