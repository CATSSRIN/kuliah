#include <stdio.h>

// Function to calculate the area of a triangle
float calculateTriangleArea(float base, float height) {
   return 0.5 * base * height;
}

// Function to calculate the circumference of a triangle
float calculateTriangleCircumference(float side1, float side2, float side3) {
   return side1 + side2 + side3;
}

// Function to calculate the area of a rectangle
float calculateRectangleArea(float length, float width) {
   return length * width;
}

// Function to calculate the circumference of a rectangle
float calculateRectangleCircumference(float length, float width) {
   return 2 * (length + width);
}

// Function to calculate the area of a square
float calculateSquareArea(float side) {
   return side * side;
}

// Function to calculate the circumference of a square
float calculateSquareCircumference(float side) {
   return 4 * side;
}

int main() {
   float base, height, side1, side2, side3, length, width;

   // Input for triangle
   printf("Enter the base and height of the triangle: ");
   scanf("%f %f", &base, &height);

   // Calculate and display the area and circumference of the triangle
   printf("Triangle:\n");
   printf("Area: %.2f\n", calculateTriangleArea(base, height));
   printf("Circumference: %.2f\n", calculateTriangleCircumference(base, height, height));

   // Input for rectangle
   printf("\nEnter the length and width of the rectangle: ");
   scanf("%f %f", &length, &width);

   // Calculate and display the area and circumference of the rectangle
   printf("Rectangle:\n");
   printf("Area: %.2f\n", calculateRectangleArea(length, width));
   printf("Circumference: %.2f\n", calculateRectangleCircumference(length, width));

   // Input for square
   printf("\nEnter the side length of the square: ");
   scanf("%f", &side1);

   // Calculate and display the area and circumference of the square
   printf("Square:\n");
   printf("Area: %.2f\n", calculateSquareArea(side1));
   printf("Circumference: %.2f\n", calculateSquareCircumference(side1));

   return 0;
}