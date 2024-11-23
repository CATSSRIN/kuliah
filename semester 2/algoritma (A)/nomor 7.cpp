#include <iostream>
using namespace std;

int main() {
    double radius, tinggi;
    const double pi = 3.14;

    cout << "Enter the radius of the tube: ";
    cin >> radius;

    cout << "Enter the tinggi of the tube: ";
    cin >> tinggi;

    double volume = pi * radius * radius * tinggi;

    cout << "The volume of the tube is: " << volume << endl;

    return 0;
}


