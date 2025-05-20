#include <iostream>
#<include <string>
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