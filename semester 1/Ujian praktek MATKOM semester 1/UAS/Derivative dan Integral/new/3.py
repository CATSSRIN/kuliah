import sympy as sp
import matplotlib.pyplot as plt

x = sp.Symbol('x')

line_1 = sp.oo
line_2 = 12*x**2 + 2*sp.cos(x)

start_range = 0
end_range = sp.pi/2

intersection = sp.solve((line_1 - line_2), x)

filtered_intersection = [point for point in intersection if point >= start_range and point <= end_range]

print("titik perpotongan yang berada dalam rentang [0, pi/2] adalah ", filtered_intersection)

#grafik
plt.title("grafik")
plt.xlabel("x")
plt.ylabel("y")

plt.show()