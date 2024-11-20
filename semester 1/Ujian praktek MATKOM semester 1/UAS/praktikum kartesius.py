import itertools
import matplotlib.pyplot as plt

# Define the sets
set1 = [1, 2, 3]
set2 = ['a', 'b', 'c']

# Calculate the Cartesian product
cartesian_product = list(itertools.product(set1, set2))

# Create empty lists for x and y coordinates
x_coords = []
y_coords = []

# Append coordinates to the lists
for pair in cartesian_product:
    x, y = pair
    x_coords.append(x)
    y_coords.append(y)

# Plot the points and connect them with lines
plt.plot(x_coords, y_coords, '-o')
plt.xlabel('X')
plt.ylabel('Y')
plt.title('Cartesian Product')
plt.grid(True)
plt.show()


plt.plot(x_coords, y_coords, '-o', color='cyan')
