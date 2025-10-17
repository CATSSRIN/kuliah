import cv2
import numpy as np
import matplotlib.pyplot as plt
from skimage.segmentation import active_contour
from skimage.filters import gaussian
import sys

# --- FUNGSI UNTUK MENAMPILKAN HASIL ---
def display_results(original, segmented, title):
    """Fungsi untuk menampilkan citra asli dan hasil segmentasi berdampingan."""
    plt.figure(figsize=(12, 6))
    plt.subplot(1, 2, 1)
    plt.title('Citra Asli')
    plt.imshow(original, cmap='gray')
    plt.axis('off')

    plt.subplot(1, 2, 2)
    plt.title(title)
    plt.imshow(segmented, cmap='gray')
    plt.axis('off')
    plt.show()

# --- FUNGSI UNTUK SETIAP METODE SEGMENTASI ---

# 1. THRESHOLDING
def apply_thresholding(img):
    print("Menerapkan Thresholding (Otsu)...")
    blurred_img = cv2.GaussianBlur(img, (5, 5), 0)
    ret, thresh_img = cv2.threshold(blurred_img, 0, 255, cv2.THRESH_BINARY + cv2.THRESH_OTSU)
    print(f"Nilai ambang batas Otsu yang terdeteksi: {ret}")
    display_results(img, thresh_img, 'Hasil Segmentasi Thresholding')

# 2. REGION GROWING
def apply_region_growing(img):
    print("Menerapkan Region Growing...")
    
    # Fungsi internal untuk region growing
    def region_growing_func(image, seed, threshold=15):
        h, w = image.shape
        segmented = np.zeros_like(image)
        visited = np.zeros_like(image, dtype=bool)
        queue = [seed]
        seed_value = image[seed]
        visited[seed] = True

        while queue:
            x, y = queue.pop(0)
            segmented[x, y] = 255
            for i in range(-1, 2):
                for j in range(-1, 2):
                    if i == 0 and j == 0: continue
                    nx, ny = x + i, y + j
                    if 0 <= nx < h and 0 <= ny < w and not visited[nx, ny]:
                        if abs(int(image[nx, ny]) - int(seed_value)) < threshold:
                            visited[nx, ny] = True
                            queue.append((nx, ny))
        return segmented

    # 👉 PENTING: Sesuaikan koordinat seed_point (y, x) ini agar berada di dalam objek target Anda!
    seed_point = (150, 250)
    print(f"Titik awal (seed) diatur ke: {seed_point}")
    region_growing_img = region_growing_func(img, seed=seed_point, threshold=20)
    display_results(img, region_growing_img, 'Hasil Segmentasi Region Growing')

# 3. K-MEANS CLUSTERING
def apply_kmeans(img):
    print("Menerapkan K-Means Clustering...")
    pixel_values = img.flatten().astype(np.float32)
    pixel_values = np.reshape(pixel_values, (-1, 1))
    
    K = 4 # Mengelompokkan menjadi 3 kelas (misal: background, jaringan lunak, tulang)
    print(f"Jumlah cluster (K) diatur ke: {K}")
    criteria = (cv2.TERM_CRITERIA_EPS + cv2.TERM_CRITERIA_MAX_ITER, 100, 0.2)
    _, labels, centers = cv2.kmeans(pixel_values, K, None, criteria, 10, cv2.KMEANS_RANDOM_CENTERS)
    
    centers = np.uint8(centers)
    segmented_data = centers[labels.flatten()]
    kmeans_img = segmented_data.reshape((img.shape))
    display_results(img, kmeans_img, f'Hasil Segmentasi K-Means (K={K})')

# 4. WATERSHED
def apply_watershed(img):
    print("Menerapkan Watershed...")
    # Watershed membutuhkan citra 3-channel
    img_color = cv2.cvtColor(img, cv2.COLOR_GRAY2BGR)
    
    ret, thresh = cv2.threshold(img, 0, 255, cv2.THRESH_BINARY_INV + cv2.THRESH_OTSU)
    kernel = np.ones((3, 3), np.uint8)
    opening = cv2.morphologyEx(thresh, cv2.MORPH_OPEN, kernel, iterations=2)
    
    sure_bg = cv2.dilate(opening, kernel, iterations=3)
    dist_transform = cv2.distanceTransform(opening, cv2.DIST_L2, 5)
    ret, sure_fg = cv2.threshold(dist_transform, 0.7 * dist_transform.max(), 255, 0)
    sure_fg = np.uint8(sure_fg)
    
    unknown = cv2.subtract(sure_bg, sure_fg)
    
    ret, markers = cv2.connectedComponents(sure_fg)
    markers = markers + 1
    markers[unknown == 255] = 0
    
    markers = cv2.watershed(img_color, markers)
    img_color[markers == -1] = [255, 0, 0] # Batas ditandai warna merah
    display_results(img, img_color, 'Hasil Segmentasi Watershed')

# 5. ACTIVE CONTOUR (SNAKES)
def apply_active_contour(img):
    print("Menerapkan Active Contour (Snakes)...")
    smooth_img = gaussian(img, 3, preserve_range=False)

    # 👉 PENTING: Sesuaikan inisialisasi kontur (lingkaran) agar mengelilingi objek target Anda!
    # c = pusat_y, r = pusat_x, 100 = radius
    s = np.linspace(0, 2 * np.pi, 400)
    c = 250 + 100 * np.cos(s) 
    r = 150 + 100 * np.sin(s)
    init = np.array([r, c]).T
    
    print("Kontur awal dibuat, menjalankan iterasi snakes...")
    snake = active_contour(smooth_img, init, alpha=0.015, beta=10, gamma=0.001)

    fig, ax = plt.subplots(figsize=(7, 7))
    ax.imshow(img, cmap=plt.cm.gray)
    ax.plot(init[:, 1], init[:, 0], '--r', lw=3, label='Kontur Awal')
    ax.plot(snake[:, 1], snake[:, 0], '-b', lw=3, label='Kontur Akhir')
    ax.set_title('Hasil Segmentasi Active Contour (Snakes)')
    ax.axis('off')
    plt.legend()
    plt.show()

# --- FUNGSI UTAMA (MAIN) ---
def main():
    # --- Ganti 'xray_tangan.jpg' dengan path file citra Anda ---
    image_path = 'gambar1.jpeg'
    img = cv2.imread(image_path, 0)

    if img is None:
        print(f"Error: Tidak dapat memuat citra dari path: {image_path}")
        sys.exit() # Keluar dari skrip jika citra tidak ditemukan

    while True:
        print("\n" + "="*40)
        print("    PILIH METODE SEGMENTASI CITRA MEDIS")
        print("="*40)
        print("1. Thresholding (Otsu)")
        print("2. Region Growing")
        print("3. K-Means Clustering")
        print("4. Watershed")
        print("5. Active Contour (Snakes)")
        print("6. Keluar")
        print("="*40)
        
        choice = input("Masukkan pilihan Anda (1-6): ")
        
        if choice == '1':
            apply_thresholding(img)
        elif choice == '2':
            apply_region_growing(img)
        elif choice == '3':
            apply_kmeans(img)
        elif choice == '4':
            apply_watershed(img)
        elif choice == '5':
            apply_active_contour(img)
        elif choice == '6':
            print("Terima kasih! Program selesai.")
            break
        else:
            print("Pilihan tidak valid. Silakan masukkan angka antara 1 dan 6.")

if __name__ == '__main__':
    main()