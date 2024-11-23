
m1 = [
    [1, 2, 4],
    [2, 6, 0]
]

m2 = [
    [4, 1, 4, 3],
    [0, -1, 3, 1],
    [2, 7, 5, 2]
]

baris_m1 = len(m1)
kolom_m1 = len(m1[0])
baris_m2 = len(m2)
kolom_m2 = len(m2[0])

hasil_matriks = [[0 for _ in range(kolom_m2)] for _ in range(baris_m1)]

for i in range(baris_m1):
    for j in range(kolom_m2):
        for k in range(kolom_m1):
            hasil_matriks[i][j] += m1[i][k] * m2[k][j]


print("\nHasil perkalian:")
for row in hasil_matriks:
    print(row)
