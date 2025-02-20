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
    car car2("ford", "mustang", 2020, "red");


    std::cout << car1.make << '\n';
    std::cout << car1.model << '\n';
    std::cout << car1.year << '\n';
    std::cout << car1.color << '\n';
    std::cout << '\n';

    printf(car2.make.c_str());
    printf("\n");
    printf(car2.model.c_str());
    printf("\n");
    printf("%d\n", car2.year); //or this works too
    printf(car2.color.c_str());
    printf("\n");

};