# pertambahan matriks

m1 = [
    [16, -2],
    [0, 4],

]


m2 = [
    [2, 4, 2],
    [6, 1, 5]
]


result_matrix = []


for row1, row2 in zip(m1, m2):
    result_row = [x + y for x, y in zip(row1, row2)]
    result_matrix.append(result_row)




print("\nHasil pertambahan:")
for row in result_matrix:
    print(row)
