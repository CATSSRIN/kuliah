# perkalian matriks

# Define the first matrix
m3 = [
    [1, 2, 9, 0, 4, 7],
    [7, 7, 1, 2, 7, 3],
    [9, 2, 2, 1, 1, 7],
    [3, 3, 7, 9, 0, 9],
    [1, 0, 8, 7, 8, 9],
    [2, 5, 0, 0, 6, 9]
]

# Define the second matrix
m4 = [
    [17, 14, 46, 82, 33, 75, 64, 48],
    [77, 26, 76, 56, 64, 81, 57, 43],
    [56, 42, 69, 3, 55, 52, 66, 44],
    [47, 79, 51, 66, 7, 24, 33, 11],
    [5, 48, 32, 54, 13, 48, 88, 27],
    [69, 18, 4, 12, 56, 19, 89, 6]
]

# Get the dimensions of the matrices
rows_m3 = len(m3)
cols_m3 = len(m3[0])
rows_m4 = len(m4)
cols_m4 = len(m4[0])

# Initialize a matrix for the result
result_matrix = [[0 for _ in range(cols_m4)] for _ in range(rows_m3)]

# Perform matrix multiplication
for i in range(rows_m3):
    for j in range(cols_m4):
        for k in range(cols_m3):
            result_matrix[i][j] += m3[i][k] * m4[k][j]


print("\nHasil perkalian:")
for row in result_matrix:
    print(row)