import fitz  # PyMuPDF
import os
import re
from datetime import datetime

def extract_info_from_pdf(pdf_path):
    """
    Mengekstrak teks dari halaman pertama PDF untuk menemukan judul dan tahun.
    - Judul: diasumsikan sebagai teks dengan ukuran font terbesar.
    - Tahun: dicari menggunakan regular expression (regex) atau dari metadata.
    """
    try:
        doc = fitz.open(pdf_path)
        first_page = doc[0]
        
        # 1. EKSTRAK JUDUL (berdasarkan ukuran font)
        blocks = first_page.get_text("dict", flags=11)["blocks"]
        max_font_size = 0
        potential_title = ""
        
        if not blocks:
            print(f"Tidak ada blok teks yang ditemukan di halaman pertama {os.path.basename(pdf_path)}.")
            doc.close()
            return None, None

        for b in blocks:
            if "lines" in b:
                for l in b["lines"]:
                    if "spans" in l:
                        for s in l["spans"]:
                            if s["size"] > max_font_size:
                                max_font_size = s["size"]
                                potential_title = s["text"].strip()
                            elif s["size"] == max_font_size and potential_title:
                                potential_title += " " + s["text"].strip()
        
        # 2. EKSTRAK TAHUN
        year = None
        full_text = first_page.get_text("text")
        
        # Metode A: Cari tahun (misal: 1980-2029) di dalam teks menggunakan regex
        # Mencari angka 4 digit yang diawali 19 atau 20
        current_year = datetime.now().year
        # Regex mencari tahun antara 1950 dan tahun saat ini + 1
        match = re.search(r'\b(19[5-9]\d|20\d{2})\b', full_text)
        if match and int(match.group(1)) <= current_year + 1:
            year = match.group(1)

        # Metode B: Jika tidak ditemukan, coba cari dari metadata PDF
        if not year:
            metadata = doc.metadata
            # Metadata tanggal sering dalam format 'D:YYYYMMDD...'
            date_keys = ['creationDate', 'modDate']
            for key in date_keys:
                if metadata.get(key) and metadata[key].startswith('D:'):
                    year_from_meta = metadata[key][2:6]
                    if year_from_meta.isdigit():
                        year = year_from_meta
                        break # Hentikan pencarian jika sudah ditemukan
                        
        doc.close()
        return potential_title if potential_title else None, year

    except Exception as e:
        print(f"Gagal memproses file {os.path.basename(pdf_path)}: {e}")
        return None, None

def sanitize_filename(filename):
    """
    Menghapus karakter yang tidak valid untuk nama file.
    """
    return re.sub(r'[\\/*?:"<>|]', "", filename)

def rename_pdf_based_on_info(pdf_path):
    """
    Mengganti nama file PDF berdasarkan judul dan tahun yang diekstrak.
    """
    print(f"Memproses file: {os.path.basename(pdf_path)}...")
    
    directory = os.path.dirname(pdf_path)
    original_filename = os.path.basename(pdf_path)
    
    title, year = extract_info_from_pdf(pdf_path)
    
    if title:
        sanitized_title = sanitize_filename(title)
        
        # Batasi panjang judul (opsional, untuk menghindari nama file terlalu panjang)
        if len(sanitized_title) > 150:
            sanitized_title = sanitized_title[:150].strip()
            
        # Bentuk nama file baru
        if year:
            new_filename_base = f"{year} - {sanitized_title}"
        else:
            # Jika tahun tidak ditemukan, gunakan judul saja
            new_filename_base = sanitized_title
            print(f"  -> Peringatan: Tahun tidak ditemukan untuk '{original_filename}'.")

        new_filename = new_filename_base + ".pdf"
        new_path = os.path.join(directory, new_filename)
        
        if os.path.exists(new_path):
            print(f"  -> Gagal: Nama file baru '{new_filename}' sudah ada.")
            return

        try:
            os.rename(pdf_path, new_path)
            print(f"  -> Berhasil: Diubah menjadi '{new_filename}'")
        except OSError as e:
            print(f"  -> Gagal mengganti nama file: {e}")
            
    else:
        print(f"  -> Gagal: Judul tidak dapat ditemukan untuk '{original_filename}'. File tidak diubah.")


# --- CARA PENGGUNAAN ---
if __name__ == "__main__":
    # GANTI DENGAN PATH FOLDER PDF ANDA
    folder_path = r"D:\Github\kuliah\semester 5\Pemrograman API\tugas 1\koleksi jurnal\21"

    if not os.path.isdir(folder_path):
        print(f"Error: Folder tidak ditemukan di '{folder_path}'")
    else:
        print(f"Memulai proses di folder: {folder_path}\n" + "="*40)
        # Loop semua file di dalam folder
        for filename in os.listdir(folder_path):
            if filename.lower().endswith(".pdf"):
                file_path = os.path.join(folder_path, filename)
                rename_pdf_based_on_info(file_path)
        print("="*40 + "\nProses selesai.")