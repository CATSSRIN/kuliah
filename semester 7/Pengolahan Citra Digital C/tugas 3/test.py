import cv2
import numpy as np
import matplotlib.pyplot as plt
import os

# 1. Load a built-in sample image from Colab's default sample data folder
# If the sample image isn't available, it will generate a synthetic one so it never crashes.
img_path = '/content/sample_data/mnist_test.csv' 
if os.path.exists('/content/sample_data/README.md'):
    # Let's generate a nice gradient image with some noise to simulate a real photo
    np.random.seed(42)
    img = np.linspace(50, 200, 256 * 256).reshape(256, 256).astype(np.uint8)
    noise = np.random.normal(0, 7, img.shape).astype(np.int16)
    img = np.clip(img + noise, 0, 255).astype(np.uint8)
else:
    # Fallback synthetic image if run outside of standard Colab environment
    img = np.indices((256, 256))[0].astype(np.uint8)

# 2. Get image dimensions and initialize histograms
rows, cols = img.shape
crisp_hist = np.zeros(256)
fuzzy_hist = np.zeros(256)

# Define the fuzzy support width (spread weight to 2 neighbors on each side)
width = 2 

# 3. Calculate both Crisp and Fuzzy Histograms
for r in range(rows):
    for c in range(cols):
        val = int(img[r, c])
        
        # Standard Crisp Histogram (Direct count)
        crisp_hist[val] += 1
        
        # Fuzzy Histogram calculation (Triangular Membership)
        for k in range(-width, width + 1):
            neighbor_val = val + k
            
            # Stay within valid grayscale bounds (0 to 255)
            if 0 <= neighbor_val <= 255:
                # Triangular weight formula: peaks at 1 when k=0, drops off at edges
                weight = 1.0 - (abs(k) / (width + 1))
                
                # Add fractional weight to the neighboring bin
                fuzzy_hist[neighbor_val] += weight

# 4. Plot the results side-by-side using Matplotlib
plt.figure(figsize=(12, 8))

# Top Graph: Crisp Histogram
plt.subplot(2, 1, 1)
plt.bar(range(256), crisp_hist, color='#3498db', width=1.0, edgecolor='none')
plt.title('Standard Crisp Histogram (With Jagged Noise Spikes)', fontsize=14)
plt.xlabel('Gray Level Intensity')
plt.ylabel('Pixel Count')
plt.xlim([0, 255])

# Bottom Graph: Fuzzy Histogram
plt.subplot(2, 1, 2)
plt.bar(range(256), fuzzy_hist, color='#e67e22', width=1.0, edgecolor='none')
plt.title('Fuzzified Histogram (Naturally Smoothed via Membership)', fontsize=14)
plt.xlabel('Gray Level Intensity')
plt.ylabel('Fuzzy Membership Count')
plt.xlim([0, 255])

plt.tight_layout()
plt.show()