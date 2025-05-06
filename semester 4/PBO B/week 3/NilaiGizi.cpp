#include <iostream>
using namespace std;

// Base class
class Makanan {
public:
    int jumlahSajian;
    int energi;
    int lemakTotal;
    int gulaTotal;
    int natriumTotal;
    
    void setVariable() {
        jumlahSajian = 1;
        energi = 20; //kkal
        lemakTotal = 0;
        gulaTotal = 4; //gram
        natriumTotal = 0;
    }
};

// Derived class Biskuit
class Biskuit : public Makanan {
public:
    int seratTotal;

    void setVariable() {
        // Setting base class variables
        Makanan::setVariable(); 
        jumlahSajian = 4;
        energi = 35; //kkal
        lemakTotal = 8;
        gulaTotal = 12; //gram
        natriumTotal = 50;
        seratTotal = 3;
    }

    void hitungGizi() {
        cout << "Jumlah Sajian: " << jumlahSajian << endl;  
        cout << "Jumlah Energi: " << energi << endl;  
        cout << "Lemak Total: " << lemakTotal << endl;  
        cout << "Gula Total: " << gulaTotal << endl;  
        cout << "Natrium Total: " << natriumTotal << endl; 
        cout << "Serat Total: " << seratTotal << endl; 
    }
};

// Main function
int main() {
    Makanan makanan;
    Biskuit biskuit;
    
    // Goto
    goto jump;

    // Ini adalah tempat biasa di mana kita mendeklarasikan objek Biskuit
    biskuit.setVariable(); // Initialize values
    biskuit.hitungGizi();  // Display values

    return 0;

jump:
    // Melompat ke bagian ini setelah 'goto jump'
    makanan.setVariable();  // Initialize the base class variables
    cout << "Jumlah Sajian: " << makanan.jumlahSajian << endl;  
    cout << "Jumlah Energi: " << makanan.energi << endl;  
    cout << "Lemak Total: " << makanan.lemakTotal << endl;  
    cout << "Gula Total: " << makanan.gulaTotal << endl;  
    cout << "Natrium Total: " << makanan.natriumTotal << endl; 
    
    // Tidak ada `seratTotal` karena ini adalah variabel dari objek Biskuit
    return 0;
}
