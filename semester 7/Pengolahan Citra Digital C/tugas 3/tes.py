import numpy as np
import cv2
import urllib.request

def load_image_from_url(url, grayscale=True):
    """
    Fungsi untuk memuat turun imej daripada URL secara automatik
    dan menukarnya terus ke format tatasusunan NumPy/OpenCV.
    """
    try:
        # Menghantar permintaan HTTP (dengan User-Agent bagi mengelakkan sekatan 403 Forbidden)
        req = urllib.request.Request(
            url, 
            headers={'User-Agent': 'Mozilla/5.0'}
        )
        with urllib.request.urlopen(req) as resp:
            # Membaca bait imej daripada respons rangkaian
            image_array = np.asarray(bytearray(resp.read()), dtype=np.uint8)
            
            # Menyahkod bait ke imej OpenCV
            read_flag = cv2.IMREAD_GRAYSCALE if grayscale else cv2.IMREAD_COLOR
            image = cv2.imdecode(image_array, read_flag)
            
            if image is None:
                raise ValueError("Format imej tidak sah atau gagal dinyahkod.")
            return image
    except Exception as e:
        print(f"Ralat semasa memuat turun imej: {e}")
        return None

def fuzzy_histogram_enhancement(img_gray):
    """
    Pelaksanaan algoritma peningkatan kontras imej berasaskan kertas kajian:
    Fuzzy Smoothing + Entropy-Controlled Dynamic Histogram Equalization.
    """
    L = 256
    img = img_gray.copy()
    
    # 1. Histogram Pengiraan & Fuzzy Smoothing (Persamaan 10 & 11)
    hist, _ = np.histogram(img.flatten(), bins=L, range=[0, L])
    mean_val = np.mean(img)
    std_val = np.std(img)
    if std_val == 0:
        return img

    k_vals = np.arange(L)
    mu = np.exp(-0.5 * ((mean_val - k_vals) / std_val) ** 2)
    fuzzy_hist = np.convolve(hist.astype(np.float64), mu, mode='same')
    
    # 2. Menentukan Titik Maksima & Minima Tempatan (Persamaan 8, 9, 12, 13)
    grad1 = np.zeros(L)
    grad2 = np.zeros(L)
    grad1[1:-1] = (fuzzy_hist[2:] - fuzzy_hist[:-2]) / 2.0
    grad2[1:-1] = fuzzy_hist[2:] - 2.0 * fuzzy_hist[1:-1] + fuzzy_hist[:-2]
    
    critical_points = []
    for k in range(1, L - 1):
        if (grad1[k - 1] > 0 and grad1[k] <= 0 and grad2[k] < 0) or \
           (grad1[k] >= 0 and grad1[k + 1] < 0 and grad2[k] < 0):
            critical_points.append(k)
        elif (grad1[k - 1] < 0 and grad1[k] >= 0 and grad2[k] > 0) or \
             (grad1[k] <= 0 and grad1[k + 1] > 0 and grad2[k] > 0):
            critical_points.append(k)
            
    critical_points = sorted(list(set(critical_points)))
    p_min = int(np.min(img))
    p_max = int(np.max(img))
    
    split_points = [p for p in critical_points if p_min < p < p_max]
    
    # Pembentukan sempadan sub-segmen
    segments = []
    curr_low = p_min
    for sp in split_points:
        if sp >= curr_low:
            segments.append((curr_low, sp))
            curr_low = sp + 1
    if curr_low <= p_max:
        segments.append((curr_low, p_max))
        
    num_segments = len(segments)
    if num_segments == 0:
        segments = [(p_min, p_max)]
        num_segments = 1

    # 3. Peruntukan Julat Terkawal Entropi (Persamaan 14 - 20)
    factors = []
    for (low_r, high_r) in segments:
        sub_hist = hist[low_r:high_r + 1].astype(np.float64)
        M_r = np.sum(sub_hist)
        span_r = high_r - low_r
        
        if M_r > 0:
            pr = sub_hist / M_r
            pr_nonzero = pr[pr > 0]
            E_Hr = -np.sum(pr_nonzero * np.log10(pr_nonzero))
        else:
            E_Hr = 0.0
            
        phi_r = 1.0 / (E_Hr + 1e-6)
        log_M = np.log10(M_r) if M_r > 1 else 1e-6
        factor_r = span_r * phi_r * log_M
        factors.append(factor_r)
        
    sum_factors = np.sum(factors) if np.sum(factors) != 0 else 1.0
    ranges = [(f * (L - 1)) / sum_factors for f in factors]
    
    dyn_ranges = []
    cum_range = 0
    for r in range(num_segments):
        if r == 0:
            init_r = 0
            fin_r = int(np.round(ranges[0]))
        elif r == num_segments - 1:
            init_r = int(np.round(cum_range)) + 1
            fin_r = L - 1
        else:
            init_r = int(np.round(cum_range)) + 1
            fin_r = int(np.round(cum_range + ranges[r]))
            
        cum_range += ranges[r]
        init_r = max(0, min(L - 1, init_r))
        fin_r = max(init_r, min(L - 1, fin_r))
        dyn_ranges.append((init_r, fin_r))

    # 4. Penyamaan Segmen (Persamaan 21 - 25)
    X = np.zeros_like(img, dtype=np.float64)
    for idx, (low_r, high_r) in enumerate(segments):
        init_r, fin_r = dyn_ranges[idx]
        range_span = fin_r - init_r
        
        mask = (img >= low_r) & (img <= high_r)
        if not np.any(mask):
            continue
            
        sub_hist = hist[low_r:high_r + 1].astype(np.float64)
        M_r = np.sum(sub_hist)
        cdf = np.cumsum(sub_hist / M_r) if M_r > 0 else np.zeros_like(sub_hist)
        
        map_table = init_r + range_span * cdf
        vals = img[mask] - low_r
        X[mask] = map_table[vals]

    # 5. Normalisasi Kecerahan Purata (Persamaan 26)
    Y = np.zeros_like(X)
    for idx, (low_r, high_r) in enumerate(segments):
        mask = (img >= low_r) & (img <= high_r)
        if not np.any(mask):
            continue
            
        O_Ir = np.mean(img[mask])
        O_Xr = np.mean(X[mask])
        eta = O_Ir / (O_Xr + 1e-6)
        Y[mask] = np.clip(eta * X[mask], 0, 255)
        
    return np.uint8(np.round(Y))

# =========================================================
# Contoh Penggunaan: Mengambil Imej Dalam Talian Secara Automatik
# =========================================================
if __name__ == "__main__":
    # Masukkan URL imej yang ingin diproses (contoh imej ujian rawak atau daripada internet)
    # URL di bawah menggunakan imej sampel rawak resolusi 512x512 daripada Unsplash
    image_url = "https://images.unsplash.com/photo-1509114397022-ed747cca3f65?w=512&q=80"
    
    print("Sedang memuat turun imej daripada URL...")
    input_image = load_image_from_url(image_url, grayscale=True)
    
    if input_image is not None:
        print("Imej berjaya dimuat turun. Memulakan pemprosesan kontras...")
        enhanced_image = fuzzy_histogram_enhancement(input_image)
        
        # Simpan kedua-dua imej asal dan hasil untuk perbandingan
        cv2.imwrite("input_online.jpg", input_image)
        cv2.imwrite("enhanced_online_output.jpg", enhanced_image)
        print("Selesai! Imej asal disimpan sebagai 'input_online.jpg' dan imej hasil disimpan sebagai 'enhanced_online_output.jpg'.")