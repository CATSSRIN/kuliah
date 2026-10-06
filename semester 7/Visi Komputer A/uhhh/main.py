import os
import cv2
import numpy as np

def extract_logo_sharp_and_smooth(
    input_path: str,
    output_path: str,
    crop_scale: float = 0.22,
    # Nilai ambang kecerahan untuk menghapus biru tua.
    # Nilai ini harus di antara 0 (hitam) dan 255 (putih).
    # Tingkatkan jika biru tua masih ada, turunkan jika logo jadi transparan.
    brightness_cutoff: int = 40,
    logo_color: tuple = (230, 221, 242) # BGR: Warna lilac asli
):
    # Validasi dan muat gambar (aman terhadap spasi)
    if not os.path.exists(input_path):
        raise FileNotFoundError(f"File tidak ditemukan: '{input_path}'")
    with open(input_path, "rb") as f:
        bytes_data = bytearray(f.read())
        numpy_array = np.asarray(bytes_data, dtype=np.uint8)
        img = cv2.imdecode(numpy_array, cv2.IMREAD_COLOR)
    if img is None:
        raise ValueError(f"Gagal mendecode gambar dari '{input_path}'")

    h, w, _ = img.shape

    # 1. Crop area tengah
    crop_w = int(w * crop_scale)
    crop_h = int(h * crop_scale)
    center_x, center_y = w // 2, h // 2
    x1 = max(0, center_x - crop_w // 2)
    y1 = max(0, center_y - crop_h // 2)
    x2 = min(w, center_x + crop_w // 2)
    y2 = min(h, center_y + crop_h // 2)
    cropped = img[y1:y2, x1:x2]

    # 2. Analisis Kecerahan untuk Masker Transparansi yang Mulus
    gray = cv2.cvtColor(cropped, cv2.COLOR_BGR2GRAY)

    # Kita menggunakan peta kecerahan secara langsung sebagai saluran Alpha.
    # Semakin cerah suatu piksel (logo), semakin buram (solid).
    # Semakin gelap suatu piksel (biru tua), semakin transparan.
    
    # Gunakan thresholding sederhana namun *lembut* untuk menghapus biru tua:
    # Segala sesuatu yang sangat gelap (biru tua) menjadi hitam penuh (transparan).
    # Segala sesuatu yang cerah (logo) tetap cerah (semakin solid).
    _, soft_alpha = cv2.threshold(gray, brightness_cutoff, 255, cv2.THRESH_TOZERO)

    # 3. Buat Gambar RGBA Baru
    b_out = np.full_like(soft_alpha, logo_color[0])
    g_out = np.full_like(soft_alpha, logo_color[1])
    r_out = np.full_like(soft_alpha, logo_color[2])

    # Menggabungkan B, G, R, dan saluran Alpha yang mulus
    rgba_result = cv2.merge([b_out, g_out, r_out, soft_alpha])

    # 4. Simpan dengan imencode
    is_success, buffer = cv2.imencode(".png", rgba_result)
    if is_success:
        with open(output_path, "wb") as f:
            f.write(buffer)
        print(f"Berhasil disimpan ke: '{output_path}'")
    else:
        raise RuntimeError("Gagal meng-encode gambar PNG.")

if __name__ == "__main__":
    # Mengambil path absolut direktori folder tempat script ini (main.py) berada
    base_dir = os.path.dirname(os.path.abspath(__file__))

    # Menggabungkan path folder script dengan nama file gambar
    input_file = os.path.join(base_dir, "08 Booklet Back.png")
    output_file = os.path.join(base_dir, "tuyu_transparent_smooth.png")

    extract_logo_sharp_and_smooth(input_file, output_file)