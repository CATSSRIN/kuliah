import numpy as np
import matplotlib.pyplot as plt

# Define the function
def f(x):
    return (8 / np.tan(x)**2) - np.sinh(x)

# Generate x values
x = np.linspace(np.pi, 2*np.pi)

# Calculate y values
y = f(x)

# Plot the graph with red color
plt.plot(x, y, color='red')
plt.xlabel('x')
plt.ylabel('f(x)')
plt.title('Graph of 8/cot^2(x) - sinh(x)')
plt.grid(True)

# Show the graph
plt.show()
