#include <iostream>
using namespace std;

// Prototipe function
string cekTendangan(int tendangan);
void tampilkanStatistik(int marcus, int deGea);

// Function (Cek Tendangan)
string cekTendangan(int tendangan) {
    if (tendangan % 2 == 0)
        return "Tendangan terlalu ke kiri atau terlalu ke kanan";
    else if (tendangan % 5 == 0)
        return "Tendangan terlalu ke atas";
    else
        return "Tendangan tepat sasaran";
}

// Function (Statistik)
void tampilkanStatistik(int marcus, int deGea) {
    cout << "Skor akhir: " << marcus << " untuk Marcus, " << deGea << " untuk De Gea" << endl;
    if (marcus > deGea)
        cout << "Superb, Marcus!" << endl;
    else
        cout << "Well done, De Gea!" << endl;
}

int main() {
    int tendangan[5];
    int marcus = 0, deGea = 0;

    // Input tendangan
    for (int i = 0; i < 5; ++i) {
        cout << "Masukkan tendangan ke-" << (i + 1) << ": ";
        cin >> tendangan[i];
        string hasil = cekTendangan(tendangan[i]);
        cout << "Hasil: " << hasil << endl;
        if (hasil == "Tendangan tepat sasaran")
            marcus++;
        else
            deGea++;
    }

    tampilkanStatistik(marcus, deGea);

    return 0;
}