import docx
import pandas as pd
import re
import os

def konversi_dokumen_ke_excel(nama_file_docx, nama_file_excel):
    """
    Membaca file .docx berisi koleksi artikel jurnal dan mengekstrak
    informasi terstruktur ke dalam file Excel.
    Versi ini disesuaikan untuk menangani berbagai format entri yang kompleks.
    """
    try:
        doc = docx.Document(nama_file_docx)
        # Membersihkan baris kosong saat membaca
        full_text = [para.text.strip() for para in doc.paragraphs if para.text.strip()]
    except Exception as e:
        print(f"Error saat membaca file docx: {e}")
        return

    # Pemetaan singkatan ke nama panjang jurnal (diperbarui untuk file baru)
    journal_mapping = {
        'mib': 'JURNAL MEDIA INFORMATIKA BUDIDARMA',
        'jsii': 'Jurnal Sistem Informasi dan Informatika',
        'ijmurhica': 'International Journal of Multidisciplinary Research of Higher Education (IJMURHICA)',
        'ITJRD': 'IT Journal Research and Development',
        'MORFAI': 'Multidiciplinary Output Research For Actual and International Issue (MORFAI)',
        'jtmi': 'Jurnal Teknologi dan Manajemen Informatika (JTMI)',
        'jmasif': 'Jurnal Masyarakat Informatika',
        'teknika': 'Teknika: Jurnal Sains dan Teknologi',
        'rabit': 'RABIT jurnal Program Studi Teknik Informatika Universitas Abdurrab Pekanbaru.',
        'teknosi': 'Jurnal Teknosi',
        'jetas': 'Journal of Engineering, Technology and Applied Science',
        'JITEKI': 'Jurnal Ilmiah Teknik Elektro Komputer dan Informatika',
        'aiti': 'AITI: Jurnal Teknologi Informasi',
        'bits': 'Building of Informatics, Technology and Science (BITS)',
        'sintechjournal': 'SINTECH (Science and Information Technology) Journal'
    }

    data = []
    current_journal_abbrev = None
    url_regex = r'https?://[^\s\)]+'

    for i, line in enumerate(full_text):
        # --- 1. DETEKSI JURNAL ---
        if 'mib' in line and 'stmik-budidarma' in line: current_journal_abbrev = 'mib'
        elif 'jsii' in line: current_journal_abbrev = 'jsii'
        elif 'ijmurhica' in line: current_journal_abbrev = 'ijmurhica'
        elif 'ITJRD' in line: current_journal_abbrev = 'ITJRD'
        elif 'MORFAI' in line: current_journal_abbrev = 'MORFAI'
        elif 'jtmi' in line: current_journal_abbrev = 'jtmi'
        elif 'jmasif' in line: current_journal_abbrev = 'jmasif'
        elif 'teknika' in line and 'ikado' in line: current_journal_abbrev = 'teknika'
        elif 'rabit' in line: current_journal_abbrev = 'rabit'
        elif 'teknosi' in line: current_journal_abbrev = 'teknosi'
        elif 'jetas' in line: current_journal_abbrev = 'jetas'
        elif 'JITEKI' in line: current_journal_abbrev = 'JITEKI'
        elif 'aiti' in line: current_journal_abbrev = 'aiti'
        elif 'bits' in line: current_journal_abbrev = 'bits'
        elif 'sintechjournal' in line: current_journal_abbrev = 'sintechjournal'

        # --- 2. DETEKSI DAN EKSTRAKSI ARTIKEL ---
        # Logika utama: mencari baris yang mengandung "Title:"
        if 'Title:' in line: # <-- PERUBAHAN DI SINI 1: Menggunakan 'in' agar lebih fleksibel
            title, link, year = "", "", ""

            # Ekstrak judul dan link dari baris "Title:"
            # Membersihkan nomor dan kata "Title:" di awal baris
            title_line = re.sub(r'^.*?Title:', '', line).strip() # <-- PERUBAHAN DI SINI 2: Menggunakan regex untuk membersihkan
            link_match = re.search(url_regex, title_line)
            if link_match:
                link = link_match.group(0)
                title = title_line.split('http')[0].strip().replace('(', '').strip()
            else:
                title = title_line

            # Cari informasi tambahan (Tahun, Link) di 4 baris berikutnya
            for j in range(i + 1, min(i + 5, len(full_text))):
                next_line = full_text[j]

                # Jika link belum ditemukan, cari di baris Authors (kasus khusus)
                if not link and next_line.strip().startswith('Authors:'):
                    link_match = re.search(url_regex, next_line)
                    if link_match:
                        link = link_match.group(0)

                # Cari tahun dari baris "Date:" (format YYYY-MM-DD atau DD-MM-YYYY)
                if next_line.strip().startswith('Date:'):
                    date_str = next_line.replace('Date:', '').strip()
                    date_match = re.search(r'(\d{4})', date_str) # Ambil 4 digit angka sebagai tahun
                    if date_match:
                        year = date_match.group(1)
                    break
                
                # Cari tahun dari baris "Volume:" (format Vol... (YYYY))
                elif next_line.strip().startswith('Volume:'):
                    vol_match = re.search(r'\((\d{4})\)', next_line)
                    if vol_match:
                        year = vol_match.group(1)
                    break
            
            # --- 3. SIMPAN DATA ---
            # Tambahkan data jika judul valid dan belum ada di list
            if title:
                is_duplicate = any(item['judul'] == title for item in data)
                if not is_duplicate:
                    data.append({
                        'judul': title.strip(),
                        'tahun': year.strip(),
                        'singkatan jurnal': current_journal_abbrev,
                        'nama panjang jurnal': journal_mapping.get(current_journal_abbrev, ''),
                        'link': link.strip()
                    })

    if not data:
        print("Tidak ada data artikel yang berhasil diekstrak. Mohon periksa format file .docx.")
        return
        
    df = pd.DataFrame(data)

    try:
        df.to_excel(nama_file_excel, index=False)
        print(f"File '{nama_file_excel}' berhasil dibuat dengan {len(df)} entri!")
    except Exception as e:
        print(f"Gagal menyimpan file excel: {e}")


# --- PENGGUNAAN SKRIP ---
# Pastikan skrip ini dan file docx berada di folder yang sama
script_dir = os.path.dirname(os.path.abspath(__file__))

nama_file_input = "Pengoleksian sinta 3.docx"
nama_file_output = "hasil_konversi_final.xlsx"

path_input_lengkap = os.path.join(script_dir, nama_file_input)
path_output_lengkap = os.path.join(script_dir, nama_file_output)

konversi_dokumen_ke_excel(path_input_lengkap, path_output_lengkap)