# pertambahan matriks
m1 = [
    [2, 5, 2],
    [-1, 0, 1],
    [6, 2, 4]
]

m2 = [
    [8, 1, 3],
    [-1, 1, 2],
    [8, 1, 3]
]


hasil_matrix = []


for baris1, baris2 in zip(m1, m2):
    hasil_baris = [x + y for x, y in zip(baris1, baris2)]
    hasil_matrix.append(hasil_baris)


print("\nHasil pertambahan matrix:")
for baris in hasil_matrix:
    print(baris)
