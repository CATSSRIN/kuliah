# pertambahan matriks

m1 = [
    [5, 9, 4, 2, 4, 8],
    [7, 5, 3, 1, 1, 4],
    [7, 8, 2, 0, 1, 7],
    [5, 6, 9, 3, 4, 4],
    [0, 9, 7, 2, 3, 7],
    [7, 1, 9, 3, 6, 7]
]


m2 = [
    [1, 2, 9, 0, 4, 7],
    [7, 7, 1, 2, 7, 3],
    [9, 2, 2, 1, 1, 7],
    [3, 3, 7, 9, 0, 9],
    [1, 0, 8, 7, 8, 9],
    [2, 5, 0, 0, 6, 9]
]


result_matrix = []


for row1, row2 in zip(m1, m2):
    result_row = [x + y for x, y in zip(row1, row2)]
    result_matrix.append(result_row)




print("\nHasil pertambahan matrix:")
for row in result_matrix:
    print(row)
