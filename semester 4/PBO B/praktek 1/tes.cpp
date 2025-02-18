// segitiga, persegi panjang, prisma segitiga

#include "iostream"
#include "cmath"

using namespace std;

class Segitiga {
public:
    float alas, tinggi;

    void input(float _alas, float _tinggi) {
        alas = _alas;
        tinggi = _tinggi;
    }

    float luas() {
        return 0.5f * alas * tinggi;
    }

    void output() {
        cout << "ini adalah "
             << "bangun segitiga yang memiliki "
             << "alas " << alas
             << " tinggi " << tinggi
             << " dan luas " << luas() << endl;
    }
};

class Rectangle {
public:
    float panjang, lebar;

    void input(float _panjang, float _lebar) {
        panjang = _panjang;
        lebar = _lebar;
    }

    float luas() {
        return panjang * lebar;
    }

    void output() {
        cout << "ini adalah "
             << "bangun segi empat yang memiliki "
             << "panjang " << panjang
             << " lebar " << lebar
             << " dan luas " << luas() << endl;
    }
};

class Prisma {
    Segitiga s;
    Rectangle r;

public:
    void input(float _alas, float _tinggiSegitiga, float _tinggiPrisma) {
        s.input(_alas, _tinggiSegitiga);
        r.input(_alas, _tinggiPrisma);
    }

    float volume() {
        return s.luas() * r.lebar;
    }

    float luas_permukaan() {
        return (2 * s.luas()) + (3 * r.luas());
    }

    void output() {
        cout << "ini adalah "
             << "bangun prisma segitiga yang memiliki "
             << "volume " << volume()
             << " dan luas permukaan " << luas_permukaan() << endl;
    }
};

int main() {
    Segitiga sg;
    sg.input(5, 10);
    sg.output();

    Rectangle rect;
    rect.input(4, 8);
    rect.output();

    Prisma pr;
    pr.input(5, 10, 15);
    pr.output();

    return 0;
}