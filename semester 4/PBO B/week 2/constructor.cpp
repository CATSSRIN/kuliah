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

    std::cout << student1.name << '\n';
    std::cout << student1.age << '\n';
    std::cout << student1.gpa << '\n';

    return 0;
}



int main(){

    student student1("spongebob", 25, 3.2);

    std::cout << student1.name << '\n';

    return 0;
}