import cv2
import numpy as np

# --- (Fungsi apply_clahe, apply_denoising, apply_normalization, resize_image tetap sama) ---
# Saya akan menyertakan hanya fungsi yang dimodifikasi dan bagian utama untuk kejelasan.
# Anda bisa menyalin fungsi-fungsi ini dari kode sebelumnya atau menyimpannya dalam satu file.

def apply_clahe(image):
    """Menerapkan Contrast Limited Adaptive Histogram Equalization (CLAHE) pada gambar."""
    if image.ndim == 3: # Hanya proses jika gambar berwarna
        lab_img = cv2.cvtColor(image, cv2.COLOR_BGR2Lab)
        l_channel, a_channel, b_channel = cv2.split(lab_img)
        
        clahe = cv2.createCLAHE(clipLimit=2.0, tileGridSize=(8, 8))
        cl_channel = clahe.apply(l_channel)
        
        merged_channels = cv2.merge((cl_channel, a_channel, b_channel))
        final_img = cv2.cvtColor(merged_channels, cv2.COLOR_Lab2BGR)
        return final_img
    else: # Jika gambar grayscale, terapkan langsung CLAHE
        clahe = cv2.createCLAHE(clipLimit=2.0, tileGridSize=(8, 8))
        return clahe.apply(image)

def apply_denoising(image):
    """Menerapkan Non-Local Means Denoising."""
    if image.ndim == 3: # Jika gambar berwarna
        denoised_img = cv2.fastNlMeansDenoisingColored(image, None, 10, 10, 7, 21)
    else: # Jika gambar grayscale
        denoised_img = cv2.fastNlMeansDenoising(image, None, 10, 7, 21)
    return denoised_img

def apply_normalization(image):
    """Menerapkan Normalisasi Min-Max untuk mengubah rentang piksel ke 0-255."""
    normalized_img = np.zeros_like(image, dtype=np.uint8)
    cv2.normalize(image, normalized_img, alpha=0, beta=255, norm_type=cv2.NORM_MINMAX)
    return normalized_img

def resize_image(image, max_dim=800):
    """Mengubah ukuran gambar agar dimensi terbesarnya tidak melebihi max_dim."""
    h, w = image.shape[:2]
    
    if max(h, w) <= max_dim:
        print("Gambar sudah cukup kecil, tidak perlu resize.")
        return image
    
    if h > w:
        new_h = max_dim
        new_w = int(w * (max_dim / h))
    else:
        new_w = max_dim
        new_h = int(h * (max_dim / w))
            
    resized_img = cv2.resize(image, (new_w, new_h), interpolation=cv2.INTER_AREA)
    print(f"Gambar diubah ukurannya dari ({w}, {h}) menjadi ({new_w}, {new_h}).")
    return resized_img

# --- FUNGSI show_images YANG DIMODIFIKASI ---
def show_images(original, processed, title, display_max_dim=700): # Tambahkan display_max_dim
    """
    Menampilkan gambar asli dan yang sudah diproses secara berdampingan.
    Mengubah ukuran gambar untuk tampilan agar sesuai dengan layar.
    """
    # Pastikan kedua gambar memiliki jumlah channel yang sama untuk np.hstack
    if original.ndim == 2 and processed.ndim == 3:
        original_display = cv2.cvtColor(original, cv2.COLOR_GRAY2BGR)
        processed_display = processed.copy()
    elif original.ndim == 3 and processed.ndim == 2:
        original_display = original.copy()
        processed_display = cv2.cvtColor(processed, cv2.COLOR_GRAY2BGR)
    else:
        original_display = original.copy()
        processed_display = processed.copy()

    # --- Bagian baru untuk resize sebelum tampilan ---
    h_orig, w_orig = original_display.shape[:2]
    h_proc, w_proc = processed_display.shape[:2]

    # Ambil dimensi terbesar dari kedua gambar
    max_overall_dim = max(h_orig, w_orig, h_proc, w_proc)

    if max_overall_dim > display_max_dim:
        # Hitung faktor skala untuk tampilan
        scale_factor = display_max_dim / max_overall_dim
        
        new_w_orig = int(w_orig * scale_factor)
        new_h_orig = int(h_orig * scale_factor)
        original_display = cv2.resize(original_display, (new_w_orig, new_h_orig), interpolation=cv2.INTER_AREA)
        
        new_w_proc = int(w_proc * scale_factor)
        new_h_proc = int(h_proc * scale_factor)
        processed_display = cv2.resize(processed_display, (new_w_proc, new_h_proc), interpolation=cv2.INTER_AREA)
        
        print(f"Gambar tampilan diubah ukurannya agar sesuai dengan {display_max_dim}px.")

    # Gabungkan gambar secara horizontal
    combined_image = np.hstack((original_display, processed_display))
    
    cv2.imshow(f"Original vs {title}", combined_image)
    print("Tekan tombol apa saja pada jendela gambar untuk menutupnya.")
    cv2.waitKey(0)
    cv2.destroyAllWindows()

# --- Program Utama (tidak ada perubahan besar di sini) ---
if __name__ == "__main__":
    image_path = 'gambar1.jpeg'
    original_image = None
    current_image = None 
    original_image_for_display = None # Akan diset setelah resize awal

    while True:
        if original_image is None:
            try:
                temp_image = cv2.imread(image_path)
                if temp_image is None:
                    print(f"Error: Tidak dapat menemukan gambar di '{image_path}'")
                    print("Pastikan file 'gambar1.jpeg' ada di direktori yang sama.")
                    exit()
                
                original_image = temp_image.copy() # Simpan salinan asli yang sebenarnya
                current_image = temp_image.copy() # Mulai dengan salinan untuk operasi

                print(f"Gambar '{image_path}' berhasil dimuat.")
                print(f"Dimensi asli: {original_image.shape[1]}x{original_image.shape[0]}")
                
                choice_resize_initial = input("Apakah Anda ingin langsung mengubah ukuran gambar untuk PEMROSESAN (misal, ke maks 800px)? (y/n): ").lower()
                if choice_resize_initial == 'y':
                    current_image = resize_image(current_image, max_dim=800)
                
                # original_image_for_display akan menjadi representasi gambar 'asli'
                # yang akan disandingkan dengan gambar yang diproses.
                # Ini adalah current_image setelah resize awal (jika ada).
                original_image_for_display = current_image.copy() 

            except Exception as e:
                print(f"Terjadi error saat memuat atau meresize: {e}")
                exit()
        
        print("\n--- Menu Pra-Pemrosesan Gambar ---")
        print("1. CLAHE (Peningkatan Kontras)")
        print("2. Denoising (Pengurangan Derau)")
        print("3. Normalisasi (Perbaikan Rentang Piksel)")
        print("4. Ubah Ukuran Gambar (Resize untuk pemrosesan)")
        print("5. Reset Gambar (Kembali ke kondisi awal setelah resize pertama)")
        print("6. Keluar")
        
        choice = input("Masukkan pilihan Anda (1/2/3/4/5/6): ")

        if choice == '1':
            print("Memproses dengan CLAHE...")
            processed_image = apply_clahe(current_image)
            show_images(original_image_for_display, processed_image, "CLAHE", display_max_dim=700) # Sesuaikan 700
            
        elif choice == '2':
            print("Memproses dengan Denoising...")
            processed_image = apply_denoising(current_image)
            show_images(original_image_for_display, processed_image, "Denoising", display_max_dim=700) # Sesuaikan 700
            
        elif choice == '3':
            print("Memproses dengan Normalisasi...")
            processed_image = apply_normalization(current_image)
            show_images(original_image_for_display, processed_image, "Normalization", display_max_dim=700) # Sesuaikan 700
            
        elif choice == '4':
            dim_input = input("Masukkan dimensi maksimum yang diinginkan untuk PEMROSESAN (misal: 800px): ")
            try:
                max_dim = int(dim_input)
                current_image = resize_image(current_image, max_dim=max_dim)
                original_image_for_display = current_image.copy() # Update tampilan "asli"
                print(f"Gambar untuk pemrosesan telah diubah ukurannya. Dimensi baru: {current_image.shape[1]}x{current_image.shape[0]}")
            except ValueError:
                print("Input dimensi tidak valid. Harap masukkan angka.")
            
        elif choice == '5':
            print("Mengatur ulang gambar ke kondisi asli (setelah resize pertama jika ada)...")
            temp_original = cv2.imread(image_path) # Muat ulang gambar asli sejati
            if temp_original is None:
                print("Error: Gagal memuat ulang gambar asli.")
                exit()
            
            # Tanyakan lagi apakah ingin di-resize dari awal untuk pemrosesan
            choice_resize_reset = input("Apakah Anda ingin mengubah ukuran gambar untuk PEMROSESAN lagi (y/n)? ").lower()
            if choice_resize_reset == 'y':
                current_image = resize_image(temp_original.copy(), max_dim=800) # Resize untuk pemrosesan
            else:
                current_image = temp_original.copy() # Gunakan asli untuk pemrosesan
            
            original_image_for_display = current_image.copy() # Update tampilan "asli"
            print("Gambar telah direset.")

        elif choice == '6':
            print("Terima kasih! Program selesai.")
            break
            
        else:
            print("Pilihan tidak valid. Silakan coba lagi.")