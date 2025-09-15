import cv2
import numpy as np

img = cv2.imread('xray.png', 0)

if img is None:
    print("Error: Could not read the image.")
    exit()

# Histogram equalization
he = cv2.equalizeHist(img)

# CLAHE
clahe = cv2.createCLAHE(clipLimit=2.0, tileGridSize=(8,8))
cl = clahe.apply(img)

# Gamma correction
gamma = 1.5
gc = np.array(255*(img/255)**gamma, dtype='uint8')

# Filtering
med = cv2.medianBlur(img, 3)
gauss = cv2.GaussianBlur(img, (5,5), 0)

