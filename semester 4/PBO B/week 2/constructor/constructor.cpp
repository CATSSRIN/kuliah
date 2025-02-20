#include <iostream>

class student{
    public:
        std::string name;
        int age;
        double gpa;

student (std::string name, int age, double gpa){
    this->name = name;
    this->age = age;
    this->gpa = gpa;

    }

}; 

int main(){

    student student1("spongebob", 25, 3.2);
    student student2("patrick", 40, 1.5);
    student student3("sandy", 21, 4.0);

    std::cout << student1.name << '\n';
    std::cout << student1.age << '\n';
    std::cout << student1.gpa << '\n';
    printf("\n");

    std::cout << student2.name << '\n';
    std::cout << student2.age << '\n';
    std::cout << student2.gpa << '\n';
    printf("\n");

    std::cout << student3.name << '\n';
    std::cout << student3.age << '\n';
    std::cout << student3.gpa << '\n';
    printf("\n");

    return 0;
};