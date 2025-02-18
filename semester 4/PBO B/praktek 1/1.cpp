// segitiga, persegi panjang, prisma segitiga

#include "iostream"

using namespace std;
class Segitiga{
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

class Rectangle{
    public:
        float panjang, lebar;

    void input(float _panjang, float _lebar){
        panjang = _panjang;
        lebar = _lebar;
    }

    float luas(){
        return panjang * lebar;
    }

    void output(){
        cout<<"ini adalah"<<
            "bangun segi empat yang memiliki"<<
            "panjang"<<panjang<<
            "lebar"<<lebar<<
            "dan luas"<<luas()<<endl;
    }
};

class prisma{
    Segitiga s;
    Rectangle r;

    void input(float _as, float _ts, float _tp){
        float miring = sqrt(pow(_as) + pow(_ts));
        s.input(_as, _ts);
        r.input(_tp, miring);

    }

    float volume(){
        return s.luas * r.panjang;

    }

    float luas_permukaan(){
        return (2 * s.luas()) + (3 * r.luas);

    }
};

int main(){
    Segitiga sg;
    sg.input(5, 10);
    sg.output();
    return 0;

};