import sympy as sp

# mendefinisikan simbol variabel
x = sp.Symbol('x')

# mendefinisikan fungsi
f = sp.sec(x)**2 / sp.cot(x)**3


#melakukan integral definitif dari fungsi f terhadap x pada rentang [0, pi/2]

print("hasil integral dari sec(x)**2 / cot(x)**3 = ", integral_definite)

