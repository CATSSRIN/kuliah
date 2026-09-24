# Tugas 1: Information Retrieval - Text Preprocessing Pipeline

Proyek ini merupakan implementasi pipeline text preprocessing lengkap untuk mata kuliah **Information Retrieval (IR)** menggunakan dataset komentar YouTube (`YoutubeCommentsDataSet.csv`) serta dataset kustom untuk Normalisasi dan Stopwords.

---

## 📌 Fitur Preprocessing yang Diimplementasikan
1. **Load Data Text:** Membaca dan memfilter dataset CSV dengan penanganan *missing values*.
2. **Normalization:**
   - Case folding (*lowercasing*)
   - Pembersihan URL, tag HTML, emoji/karakter non-ASCII, dan tanda baca.
   - Penanganan singkatan, internet slang, dan kontraksi menggunakan kamus `dataset/normalization_dict.csv` (contoh: `u` -> `you`, `dont` -> `do not`, `ltt` -> `linus tech tips`, `psu` -> `power supply unit`).
3. **Tokenization:** Pemecahan teks menjadi token kata menggunakan NLTK `word_tokenize` dan regex.
4. **Stop Word Removal:** Menghapus kata umum/noise menggunakan gabungan NLTK Stopwords + dataset kustom `dataset/stopwords.txt` (termasuk noise kata YouTube seperti `subscribe`, `video`, `channel`, `like`, dll).
5. **Stemming:** Memotong kata ke bentuk dasar menggunakan *Porter Stemmer* dan *Snowball Stemmer*.
6. **Lemmatization:** Mengembalikan kata ke lema kamus yang tepat dengan mempertimbangkan konteks tata bahasa (*Part-of-Speech / POS Tagging*) menggunakan NLTK *WordNet Lemmatizer*.

---

## 📁 Struktur File & Direktori

```text
Tugas 1/
├── dataset/
│   ├── YoutubeCommentsDataSet.csv       # Dataset utama komentar YouTube
│   ├── normalization_dict.csv           # Dataset kamus normalisasi (slang, singkatan, kontraksi)
│   ├── stopwords.txt                    # Dataset custom stop words (format teks per baris)
│   ├── stopwords.csv                    # Dataset custom stop words (format CSV)
│   └── processed_youtube_comments.csv   # Hasil export setelah diproses
├── Tugas_1_Text_Preprocessing.ipynb     # Jupyter Notebook siap dijalankan di Google Colab
├── preprocessing.py                     # Script Python modular & siap dieksekusi di terminal
├── requirements.txt                     # Daftar dependency Python
└── README.md                            # Dokumentasi penggunaan
```

---

## 🚀 Cara Menjalankan di Google Colab

1. Buka [Google Colab](https://colab.research.google.com/).
2. Pilih tab **Upload** dan unggah file [`Tugas_1_Text_Preprocessing.ipynb`](file:///d:/Github/kuliah/semester%207/Information%20Retrieval%20B/Tugas%201/Tugas_1_Text_Preprocessing.ipynb).
3. Di panel sebelah kiri Colab, klik ikon **Files 📁**:
   - Buat folder bernama `dataset` (atau upload langsung ke root).
   - Upload file `YoutubeCommentsDataSet.csv`, `normalization_dict.csv`, dan `stopwords.txt` ke dalam folder tersebut.
4. Klik menu **Runtime > Run all** (`Ctrl + F9`) untuk menjalankan seluruh notebook.
5. Hasil pemrosesan dan tabel perbandingan Stemming vs Lemmatization serta visualisasi frekuensi kata akan langsung tampil.

---

## 💻 Cara Menjalankan Secara Lokal (Python)

### 1. Install Dependencies
```bash
pip install -r requirements.txt
```

### 2. Jalankan Script Preprocessing
```bash
python preprocessing.py
```

### 3. Jalankan via Jupyter Lab / Notebook Lokal
```bash
jupyter notebook Tugas_1_Text_Preprocessing.ipynb
```

---

## 📊 Contoh Hasil Perbandingan Stemming vs POS-Lemmatization

| Original Word | Porter Stemmer | POS Lemmatizer | POS Tag | Catatan |
| :--- | :--- | :--- | :--- | :--- |
| `running` | `run` | `run` | `v` (Verb) | Keduanya menghasilkan akar kata yang sama |
| `studies` | `studi` | `study` | `n` (Noun) | Lemmatizer menghasilkan kata kamus yang valid |
| `better` | `better` | `good` | `a` (Adj) | Lemmatizer mengenali bentuk komparatif ke bentuk dasar |
| `went` | `went` | `go` | `v` (Verb) | Lemmatizer mengenali irregular verb |
| `universities`| `univers` | `university` | `n` (Noun) | Stemmer memotong imbuhan, lemmatizer mengembalikan ejaan baku |
