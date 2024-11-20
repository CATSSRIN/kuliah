#transpose matriks

# Define the original matrix
original_matrix = [
    [80, 29, 14, 8, 36, 81, 47, 43],
    [45, 62, 49, 32, 32, 47, 57, 63],
    [81, 84, 77, 44, 25, 90, 3, 86],
    [21, 87, 38, 38, 43, 24, 56, 41],
    [47, 16, 64, 33, 89, 62, 77, 33],
    [60, 53, 22, 45, 55, 71, 55, 68],
    [15, 89, 79, 41, 19, 81, 36, 71],
    [68, 86, 83, 46, 1, 58, 66, 34],
    [66, 65, 53, 50, 67, 88, 54, 3],
    [58, 30, 35, 47, 9, 89, 75, 48],
    [29, 0, 85, 68, 34, 86, 86, 39],
    [10, 43, 10, 82, 24, 52, 44, 6]
]

# Transpose the matrix
transposed_matrix = list(map(list, zip(*original_matrix)))


print("\nHasil transpose:")
for row in transposed_matrix:
    print(row)
