#Derivative atau diferensial atau turunan Di sini diambil contoh f = x*x = x^2

#Importing sympy
 
from sympy import *
 
# create a "symbol" called x
x = Symbol('x')
 
#Define function
f = x**2
 
#Calculating Derivative
derivative_f = f.diff(x)
 
derivative_f

#--------------------------------------------------------------

#Integral ∫sin(x)dx dengan rentang 0 hingga pi

#trapz = trapezoid, menghitung luas area di bawah grafik dengan aturan trapezoid (silahkan dilihat di sini https://pythonnumericalmethods.berkeley.edu/notebooks/chapter21.03-Trapezoid-Rule.html)



import numpy as np
from scipy.integrate import trapz

a = 0
b = np.pi
n = 11
h = (b - a) / (n - 1)
x = np.linspace(a, b, n)
f = np.sin(x)

I_trapz = trapz(f,x) #menggunakan fungsi trapz bawaan scipy
I_trap = (h/2)*(f[0] + 2 * sum(f[1:n-1]) + f[n-1]) #fungsi trap dengan perhitungan manual

print(I_trapz)
print(I_trap)

#menggambar plot integral

from scipy.integrate import cumtrapz
import matplotlib.pyplot as plt
import numpy as np

%matplotlib inline
plt.style.use('seaborn-poster')

x = np.arange(0, np.pi, 0.01)
F_exact = -np.cos(x)
F_approx = cumtrapz(np.sin(x), x)

plt.figure(figsize = (10,6))
plt.plot(x, F_exact)
plt.plot(x[1::], F_approx)
plt.grid()
plt.tight_layout()
plt.title('$F(x) = \int_0^{x} sin(y) dy$')
plt.xlabel('x')
plt.ylabel('f(x)')
plt.legend(['Exact with Offset', 'Approx'])
plt.show()