m1 = [
    [1, 2, 4],
    [2, 6, 0]
]

skalar = int(input("\nMasukkan skalar: "))
perkalian_skalar = [[skalar * element for element in row] for row in m1]

print("Hasil perkalian skalar adalah:")
for row in perkalian_skalar:
    print(row)