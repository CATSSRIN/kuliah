import docx
import pandas as pd
import re

def konversi_docx_ke_excel(nama_file_docx, nama_file_excel):
    """
    Membaca file .docx yang berisi daftar artikel jurnal dan mengekstrak
    informasi judul, tahun, singkatan jurnal, dan nama panjang jurnal,
    lalu menyimpannya ke dalam file Excel.
    """
    try:
        # Membuka dokumen Word
        doc = docx.Document(nama_file_docx)
        full_text = [para.text for para in doc.paragraphs]
    except Exception as e:
        print(f"Error saat membaca file docx: {e}")
        return

    # Pemetaan singkatan ke nama panjang jurnal
    journal_mapping = {
        'JITEKI': 'Jurnal Ilmiah Teknik Elektro Komputer dan Informatika',
        'aiti': 'Aiti: Jurnal Teknologi Informasi',
        'bits': 'Building of Informatics, Technology and Science (BITS)', # Nama panjang spesifik tidak ditemukan
        'sintechjournal': 'SINTECH (Science and Information Technology) Journal',
        'decode': 'DECODE: Jurnal Pendidikan Teknologi Informasi'
    }

    data = []
    current_journal_abbrev = None
    
    # Iterasi melalui setiap baris teks dari dokumen
    for i, line in enumerate(full_text):
        # Mengidentifikasi jurnal saat ini berdasarkan URL
        if 'journal.uad.ac.id/index.php/JITEKI' in line:
            current_journal_abbrev = 'JITEKI'
        elif 'ejournal.uksw.edu/aiti' in line:
            current_journal_abbrev = 'aiti'
        elif 'ejurnal.seminar-id.com/index.php/bits' in line:
            current_journal_abbrev = 'bits'
        elif 'ejournal.instiki.ac.id/index.php/sintechjournal' in line:
            current_journal_abbrev = 'sintechjournal'
        elif 'journal.umkendari.ac.id/index.php/decode' in line:
            current_journal_abbrev = 'decode'
        
        # Mencari tautan artikel untuk mengekstrak judul
        # Pola ini lebih andal untuk menemukan entri artikel
        if '/article/view/' in line:
            title = ""
            # Judul bisa berada di baris yang sama dengan URL atau di baris sebelumnya
            if 'http' in line and line.strip().startswith('('):
                # Kasus jika URL berada di baris terpisah
                title = full_text[i-1].strip()
            else:
                # Kasus jika URL ada di akhir baris judul
                title_candidate = line.split('http')[0].strip()
                # Menghapus nomor di awal judul jika ada (misal: "1. Judul Artikel")
                title = re.sub(r'^\d+\.\s*', '', title_candidate)
            
            # Setelah menemukan judul, cari tanggal publikasi di beberapa baris berikutnya
            year = None
            for j in range(i, i + 5): # Mencari hingga 5 baris ke depan
                if j < len(full_text) and "Date:" in full_text[j]:
                    date_match = re.search(r'(\d{4})-\d{2}-\d{2}', full_text[j])
                    if date_match:
                        year = date_match.group(1)
                        break
            
            # Jika semua informasi ditemukan, tambahkan ke list data
            if title and year and current_journal_abbrev:
                # Menghindari duplikasi judul
                is_duplicate = False
                for item in data:
                    if item['judul'] == title and item['tahun'] == year:
                        is_duplicate = True
                        break
                
                if not is_duplicate:
                    data.append({
                        'judul': title,
                        'tahun': year,
                        'singkatan jurnal': current_journal_abbrev,
                        'nama panjang jurnal': journal_mapping.get(current_journal_abbrev, '')
                    })

    # Membuat DataFrame dari list data
    df = pd.DataFrame(data)

    # Menyimpan DataFrame ke file Excel
    try:
        df.to_excel(nama_file_excel, index=False)
        print(f"File '{nama_file_excel}' berhasil dibuat!")
    except Exception as e:
        print(f"Error saat menyimpan file excel: {e}")


# --- PENGGUNAAN SKRIP ---
# Ganti nama file docx sesuai dengan nama file Anda
nama_file_input = "23081010182_Caezarlov nugraha.docx"
nama_file_output = "hasil_konversi.xlsx"

# Memanggil fungsi untuk melakukan konversi
konversi_docx_ke_excel(nama_file_input, nama_file_output)