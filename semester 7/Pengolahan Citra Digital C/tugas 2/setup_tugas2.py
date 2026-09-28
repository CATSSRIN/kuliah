import json
import os
import shutil
import math
import cv2
import numpy as np

# Ensure target directories exist
tugas1_dir = r"D:\Github\kuliah\semester 7\Pengolahan Citra Digital C\tugas 1"
tugas2_dir = r"D:\Github\kuliah\semester 7\Pengolahan Citra Digital C\tugas 2"

os.makedirs(os.path.join(tugas2_dir, "input"), exist_ok=True)
os.makedirs(os.path.join(tugas2_dir, "output"), exist_ok=True)

# Copy input images from tugas 1 to tugas 2
for fname in os.listdir(os.path.join(tugas1_dir, "input")):
    src = os.path.join(tugas1_dir, "input", fname)
    dst = os.path.join(tugas2_dir, "input", fname)
    if os.path.isfile(src):
        shutil.copy2(src, dst)

print("Input files copied successfully.")
