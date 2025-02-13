#include "iostream"
using namespace std;

class Segitiga{
    float alas, tinggi;

    void input(float _alas, float _tinggi){
        alas = _alas;
        tinggi = _tinggi;
    }

    float luas(){
        return 0.5 * alas * tinggi;
    }

    void output(){
        cout<<"ini luas segitiga dengan alas="<<alas<<" , tinggi= "<<tinggi<<"dan luas= "<<luas()<<

    }
}

int main(){
Segitiga sg;
sg.input(5,10);
sg.output();
return 0;
}