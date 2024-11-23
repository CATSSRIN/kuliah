#include <iostream>
#include <cmath>
using namespace std;

struct Point {
    double x;
    double y;
};

double distance(Point P1, Point P2) {
    return sqrt(pow(P1.x - P2.x, 2) + pow(P1.y - P2.y, 2));
}

int main() {
    Point P1, P2;

    cout << "Enter coordinates for P1 (x y): ";
    cin >> P1.x >> P1.y;

    cout << "Enter coordinates for P2 (x y): ";
    cin >> P2.x >> P2.y;

    double dist = distance(P1, P2);

    cout << "The distance between the points is: " << dist << endl;

    return 0;
}