import numpy as np
import matplotlib.pyplot as plt

# Generate x values from pi to 2*pi
x = np.linspace(np.pi, 2*np.pi)

# Calculate y values for sin^4(x) + cos^3(x)
y = np.sin(x)**4 + np.cos(x)**3

# Plot the graph
plt.plot(x, y)
plt.xlabel('x')
plt.ylabel('y')
plt.title('Graph of sin^4(x) + cos^3(x)')
plt.grid(True)
plt.show()
