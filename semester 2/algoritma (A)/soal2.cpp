#include <iostream>
#include <algorithm> 

using namespace std;

// Prototipe fungsi gabung
void gabung(const double a1[], int n1, const double a2[], int n2, double a3[], int* n3);

// Definisi fungsi gabung
void gabung(const double a1[], int n1, const double a2[], int n2, double a3[], int* n3) {
   
    int i;
    for (i = 0; i < n1; ++i) {
        a3[i] = a1[i];
    }
    for (int j = 0; j < n2; ++j) {
        a3[i++] = a2[j];
    }
    *n3 = n1 + n2;

    // Sort array a3
    sort(a3, a3 + *n3);

    // Menghilangkan duplikasi
    auto end = unique(a3, a3 + *n3);
    *n3 = distance(a3, end);
}

int main() {
    // Data array pertama dan kedua
    const double array1[] = { -10.5, -1.8, 3.5, 6.3, 7.2 };
    const double array2[] = { -1.8, 3.1, 6.3 };
    int n1 = sizeof(array1) / sizeof(array1[0]);
    int n2 = sizeof(array2) / sizeof(array2[0]);

    double array3[n1 + n2];
    int n3;

    gabung(array1, n1, array2, n2, array3, &n3);

    cout << "Array pertama: ";
    for (int i = 0; i < n1; ++i) {
        cout << array1[i] << " ";
    }
    cout << endl;

    cout << "Array kedua: ";
    for (int i = 0; i < n2; ++i) {
        cout << array2[i] << " ";
    }
    cout << endl;

    cout << "Hasil penggabungan: ";
    for (int i = 0; i < n3; ++i) {
        cout << array3[i] << " ";
    }
    cout << endl;

    return 0;
}
