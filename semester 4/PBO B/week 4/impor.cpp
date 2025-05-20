#include <iostream>
#include <string>
using namespace std;

int pajakPPh=0;
int pajakBonus=0;

void hitung Pajak(int gaji, int honor, int bonus){
    int totalSetahun = gaji*12*honor+bonus*2;

    if (totalSetahun > 60000000){
        pajakPPh = 0.1*totalSetahun;
        cout << "Total Pajak Setahun: " << pajakPPh <<"\n";
    }
    else if (totalSetahun <= 60000000){
        pajakPPh = 0.05*t(honor + 2 * bonus);
        cout << "Total Pajak Setahun: " << pajakPPh <<"\n";
    }
}

void hitungPajakTanah (int NJOP){
    int NJKP=0;
    int pajakTanah=0;

    if (NJOP >= 1000000000){
        NJKP = 1000000000;
    }
    else if (NJOP >= 500000000 && NJOP < 1000000000) {
        NJKP = 0.4*NJOP;
    }
    else {
        NJKP = 0.2*NJOP;
    }

    pajakTanah = 0.5*NJKP / 100;
    cout << "Total Pajak PBB = " << pajakTanah << "\n";

}