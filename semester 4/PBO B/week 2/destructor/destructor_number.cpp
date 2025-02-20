#include <iostream>

using namespace std;

class number{
    private:
        double num;

    public:
        number(double num)
        {
            this->num = num;
            cout << "constuctor is being called" << endl;
            cout << "number is " << num << endl;
        }

            ~number(){
            cout<<"Object is being deleted by destructor"<<endl;
            cout << "number is " << num << endl;
        }


        };
        
        int main(){
            number seven(7);
            number five(5);
            number six(6);
        
            return 0;
        };