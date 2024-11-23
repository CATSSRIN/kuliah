m3 = [
    [1, 2, 9],
    [7, 7, 1],
    [9, 2, 2]
]

m4 = [
    [1, 4, 6],
    [7, 6, 6],
    [5, 2, 9]
]

rows_m3 = len(m3)
cols_m3 = len(m3[0])
rows_m4 = len(m4)
cols_m4 = len(m4[0])

result_matrix = [[0 for _ in range(cols_m4)] for _ in range(rows_m3)]

for i in range(rows_m3):
    for j in range(cols_m4):
        for k in range(cols_m3):
            result_matrix[i][j] += m3[i][k] * m4[k][j]

print("\nHasil perkalian:")
for row in result_matrix:
    print(row)
