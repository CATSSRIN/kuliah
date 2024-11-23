def are_orthogonal(vector1, vector2):
    if len(vector1) != len(vector2):
        print("Vectors harus sama panjang")
        return False

    dot_product = 0
    for i in range(len(vector1)):
        dot_product += vector1[i] * vector2[i]

    if dot_product == 0:
        return True
    else:
        return False

def get_vector_input(vector_number):
    vector = input(f"Masukkan elemen dari vektor {vector_number} pisahkan dengan spasi: ")
    return list(map(int, vector.split()))

vector1 = get_vector_input(1)
vector2 = get_vector_input(2)

if are_orthogonal(vector1, vector2):
    print("Vectors adalah orthogonal.")
else:
    print("Vectors tidak orthogonal.")
