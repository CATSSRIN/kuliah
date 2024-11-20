import sympy as sp
x = sp.Symbol('x')

f = 4*x**3 + 2*sp.sin(x)

second_derivative = sp.diff(f, x)

print("hasil turunan = ", second_derivative)