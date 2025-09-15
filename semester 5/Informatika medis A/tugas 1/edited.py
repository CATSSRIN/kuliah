import cv2
import numpy as np
import matplotlib.pyplot as plt
import os

def process_image(image_path):
    """
    Fungsi untuk memproses satu citra medis
    dengan berbagai metode yang diminta.
    """
    img = cv2.imread(image_path, cv2.IMREAD_GRAYSCALE)
    if img is None:
        print(f"Error: Tidak bisa membaca file {image_path}")
        return

    # 1. Histogram Equalization (HE)
    img_he = cv2.equalizeHist(img)

    # 2. CLAHE (Contrast Limited Adaptive Histogram Equalization)
    clahe_params = [(2.0, (8, 8)), (4.0, (10, 10))]
    img_clahe_list = []
    for clip_limit, grid_size in clahe_params:
        clahe = cv2.createCLAHE(clipLimit=clip_limit, tileGridSize=grid_size)
        img_clahe_list.append(clahe.apply(img))

    # 3. Gamma Correction
    gamma_values = [0.5, 1.5]
    img_gamma_list = []
    for gamma in gamma_values:
        inv_gamma = 1.0 / gamma
        table = np.array([((i / 255.0) ** inv_gamma) * 255
                          for i in np.arange(0, 256)]).astype("uint8")
        img_gamma_list.append(cv2.LUT(img, table))

    # 4. Filtering (Median dan Gaussian)
    img_median = cv2.medianBlur(img, 5)
    img_gaussian = cv2.GaussianBlur(img, (5, 5), 0)

    # Menampilkan hasil
    fig, axes = plt.subplots(3, 3, figsize=(15, 15))

    # Kode yang diperbaiki: Menggunakan indeks yang benar (0, 0)
    axes[0, 0].imshow(img, cmap='gray')
    axes[0, 0].set_title(f'Original: {os.path.basename(image_path)}')

    axes[0, 1].imshow(img_he, cmap='gray')
    axes[0, 1].set_title('Histogram Equalization (HE)')

    axes[0, 2].imshow(img_clahe_list[0], cmap='gray')
    axes[0, 2].set_title(f'CLAHE (clip={clahe_params[0][0]}, grid={clahe_params[0][1]})')

    axes[1, 0].imshow(img_clahe_list[1], cmap='gray')
    axes[1, 0].set_title(f'CLAHE (clip={clahe_params[1][0]}, grid={clahe_params[1][1]})')

    axes[1, 1].imshow(img_gamma_list[0], cmap='gray')
    axes[1, 1].set_title(f'Gamma Correction (γ < 1)')

    axes[1, 2].imshow(img_gamma_list[1], cmap='gray')
    axes[1, 2].set_title(f'Gamma Correction (γ > 1)')

    axes[2, 0].imshow(img_median, cmap='gray')
    axes[2, 0].set_title('Median Filter')

    axes[2, 1].imshow(img_gaussian, cmap='gray')
    axes[2, 1].set_title('Gaussian Filter')
    
    axes[2, 2].axis('off')

    for ax in axes.flat:
        ax.set_xticks([])
        ax.set_yticks([])

    # Menambahkan spasi vertikal antar baris
    plt.subplots_adjust(hspace=0.5)

    plt.show()

# --- PENTING: Daftar jalur file lengkap untuk setiap gambar ---
image_files = [
    r'D:\Github\kuliah\semester 5\Informatika medis A\tugas 1\foto\medis1.png',
]

# Panggil fungsi untuk setiap citra
for file in image_files:
    process_image(file)