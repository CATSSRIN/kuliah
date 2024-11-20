#vector

# Impor pustaka NumPy dan beri alias sebagai np
import numpy as np

# Buat dua list
list1 = [15, 25, 35, 45, 55]
list2 = [2, 3, 4, 5, 6]

# Buat sebuah list bersarang (list dalam list)
list3 = [
    [6,
     7,
     8]
]

# Konversi list ke dalam array NumPy
vector1 = np.array(list1)
vector2 = np.array(list2)
vector3 = np.array(list3)

# Lakukan perkalian elemen dari vector1 dan vector2
mult = vector1 * vector2
print("-perkalian vector 1 dan 2 : \n ", mult)

# Lakukan perkalian titik (dot product) dari vector1 dan vector2
dot = vector1.dot(vector2)
print("-perkalian titik pada vector 1 dan 2 : \n ", dot)

# Tentukan sebuah konstanta
konstanta = 2
print(f"-bilangan konstanta : \n {konstanta}")

# Kalikan vector1 dengan konstanta
dot_kons = vector1 * konstanta
print(f"-perkalian vector 1 dengan konstanta : \n {dot_kons} ")

# Lakukan pembagian elemen dari vector1 dengan vector2
div = vector1 / vector2
print("-pembagian vector 1 dengan 2 : \n ", div)

# Tampilkan vector pertama dan kedua
print("-vector pertama : \n ", vector1)
print("-vector kedua : \n ", vector2)

# Tampilkan vector ketiga (vector3)
print("-vector vertikal : \n ", vector3)

# Lakukan penjumlahan elemen dari vector1 dan vector2
add = vector1 + vector2
print("-penjumblahan vector 1 dan 2 : \n ", add)

# Lakukan pengurangan elemen dari vector1 dan vector2
subs = vector1 - vector2
print("-pengurangan vector 1 dan 2 : \n ", subs)