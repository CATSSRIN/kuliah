#include <iostream>

class car{
    public:
        std::string make;
        std::string model;
        int year;
        std::string color;

    car(std::string make, std::string model, int year, std::string color){
        this->make = make;
        this->model = model;
        this->year = year;
        this->color = color;
    }

};

int main() {

    car car1("Toyota", "Avanza", 2019, "Black");

    std::cout << car1.make << '\n';
    std::cout << car1.model << '\n';
    std::cout << car1.year << '\n';
    std::cout << car1.color << '\n';

};