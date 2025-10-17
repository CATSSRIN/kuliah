import cv2
import numpy as np
import matplotlib.pyplot as plt
import os

# --- Fungsi-fungsi Pemrosesan ---

def apply_he(image):
    """Menerapkan Histogram Equalization (HE)."""
    print("Menerapkan Histogram Equalization...")
    return cv2.equalizeHist(image)

def apply_clahe(image, clip_limit, grid_size):
    """Menerapkan CLAHE dengan parameter yang diberikan."""
    print(f"Menerapkan CLAHE dengan clipLimit={clip_limit} dan gridSize={grid_size}...")
    clahe = cv2.createCLAHE(clipLimit=clip_limit, tileGridSize=grid_size)
    return clahe.apply(image)

def apply_gamma(image, gamma):
    """Menerapkan Gamma Correction dengan nilai gamma yang diberikan."""
    if gamma == 0:
        return image
        
    print(f"Menerapkan Gamma Correction dengan gamma={gamma}...")
    inv_gamma = 1.0 / gamma
    table = np.array([((i / 255.0) ** inv_gamma) * 255 for i in np.arange(0, 256)]).astype("uint8")
    return cv2.LUT(image, table)

def apply_median_filter(image, kernel_size=5):
    """Menerapkan Median Filter."""
    print(f"Menerapkan Median Filter dengan kernel size={kernel_size}...")
    return cv2.medianBlur(image, kernel_size)

def apply_gaussian_filter(image, kernel_size=(5, 5)):
    """Menerapkan Gaussian Filter."""
    print(f"Menerapkan Gaussian Filter dengan kernel size={kernel_size}...")
    return cv2.GaussianBlur(image, kernel_size, 0)

def apply_denoising(image, strength):
    """Menerapkan Non-Local Means Denoising."""
    print(f"Menerapkan Denoising dengan strength={strength}...")
    # h = parameter kekuatan, mengatur seberapa banyak denoising dilakukan.
    return cv2.fastNlMeansDenoising(image, None, h=strength, templateWindowSize=7, searchWindowSize=21)

def apply_normalization(image):
    """Menerapkan Normalisasi Min-Max untuk merentangkan piksel ke 0-255."""
    print("Menerapkan Normalisasi (Min-Max)...")
    normalized_img = np.zeros_like(image, dtype=np.uint8)
    cv2.normalize(image, normalized_img, alpha=0, beta=255, norm_type=cv2.NORM_MINMAX)
    return normalized_img

# --- Fungsi untuk Menampilkan Hasil ---

def show_images(original, processed, title):
    """Menampilkan gambar asli dan hasil proses berdampingan."""
    fig, axes = plt.subplots(1, 2, figsize=(12, 6))
    
    axes[0].imshow(original, cmap='gray')
    axes[0].set_title('Original Image')
    axes[0].axis('off')

    axes[1].imshow(processed, cmap='gray')
    axes[1].set_title(f'Processed: {title}')
    axes[1].axis('off')

    plt.tight_layout()
    plt.show()

# --- Program Utama ---

if __name__ == "__main__":
    # --- PENTING: Ganti dengan jalur file lengkap gambar Anda ---
    image_path = r'D:\Github\kuliah\semester 5\Informatika medis A\tugas 1\foto\medis1.png'
    
    img_original = cv2.imread(image_path, cv2.IMREAD_GRAYSCALE)
    
    if img_original is None:
        print(f"Error: Tidak bisa membaca file {image_path}")
        exit()

    while True:
        print("\n" + "="*40)
        print("Pilih Metode Pra-Pemrosesan Citra:")
        print("="*40)
        print("1. Histogram Equalization (HE)")
        print("2. CLAHE")
        print("3. Gamma Correction")
        print("4. Median Filter")
        print("5. Gaussian Filter")
        print("6. Denoising (Non-Local Means)")
        print("7. Normalisasi (Min-Max)")
        print("8. Keluar")
        print("="*40)
        
        choice = input("Masukkan pilihan Anda (1-8): ")

        if choice == '1':
            img_processed = apply_he(img_original)
            show_images(img_original, img_processed, "Histogram Equalization")

        elif choice == '2':
            try:
                clip_limit = float(input("Masukkan Clip Limit (contoh: 2.0): "))
                grid_str = input("Masukkan Ukuran Grid (contoh: 8,8): ")
                grid_x, grid_y = map(int, grid_str.split(','))
                grid_size = (grid_x, grid_y)
                
                img_processed = apply_clahe(img_original, clip_limit, grid_size)
                show_images(img_original, img_processed, f"CLAHE (clip={clip_limit}, grid={grid_size})")
            
            except (ValueError, IndexError):
                print("Error: Input tidak valid. Pastikan formatnya benar.")

        elif choice == '3':
            try:
                gamma = float(input("Masukkan nilai Gamma (contoh: 0.5 atau 1.5): "))
                img_processed = apply_gamma(img_original, gamma)
                show_images(img_original, img_processed, f"Gamma Correction (γ={gamma})")

            except ValueError:
                print("Error: Input tidak valid. Masukkan angka desimal.")

        elif choice == '4':
            img_processed = apply_median_filter(img_original)
            show_images(img_original, img_processed, "Median Filter")

        elif choice == '5':
            img_processed = apply_gaussian_filter(img_original)
            show_images(img_original, img_processed, "Gaussian Filter")

        elif choice == '6':
            try:
                strength = float(input("Masukkan kekuatan Denoising (contoh: 10, lebih tinggi lebih kuat): "))
                img_processed = apply_denoising(img_original, strength)
                show_images(img_original, img_processed, f"Denoising (strength={strength})")
            except ValueError:
                print("Error: Input tidak valid. Masukkan angka.")

        elif choice == '7':
            img_processed = apply_normalization(img_original)
            show_images(img_original, img_processed, "Normalisasi (Min-Max)")

        elif choice == '8':
            print("Program selesai. Terima kasih!")
            break
            
        else:
            print("Pilihan tidak valid. Silakan masukkan angka dari 1 hingga 8.")