#include "iostream"

using namespace std;

class segitiga{
    public:
        float alas, tinggi;

        void input(float _alas, float _tinggi){
            alas = _alas;
            tinggi = _tinggi;
        }

        float luas(){
            return 0.5f * alas * tinggi;
        }

        void output(){
            cout<<"ini adalah "<<
            "bangun segitiga yang memiliki "<<
            "alas "<<alas<<
            "tinggi "<<tinggi<<
            "dan luas "<<luas()<<endl;
        }

};

int main(){

}