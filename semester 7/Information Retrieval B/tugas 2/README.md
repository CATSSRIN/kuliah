# 📚 Tugas 2: Information Retrieval - Text Clustering & Classification Menggunakan TF-IDF

**Mata Kuliah:** Information Retrieval (Week 4)  
**Program Studi:** Sains Data - UPN "Veteran" Jawa Timur  
**Prinsip Utama:** *"Dokumen menjadi vektor, lalu model menemukan kelompok atau memprediksi kategori."*

---

## 📌 Ringkasan Isi Tugas

Proyek ini mengimplementasikan konsep **Information Retrieval** untuk pemrosesan teks tingkat lanjut, mencakup:
1. **Representasi Matriks TF-IDF Sparse ($X \in \mathbb{R}^{n \times m}$):**
   - Mengubah kumpulan dokumen menjadi representasi ruang vektor numerik.
   - Pemanfaatan `sublinear_tf=True` ($1 + \log(\text{tf})$) dan normalisasi $L_2$ (`norm='l2'`).
   - Karakteristik sparsitas data (sebagian besar sel bernilai $0$).
2. **Document Clustering (Unsupervised Learning):**
   - Pengelompokan teks tanpa label kelas menggunakan algoritma **K-Means** ($k=2$).
   - Optimasi meminimalkan jarak dokumen terhadap *centroid* cluster (*Inertia/WCSS*).
   - **Evaluasi 4 Pilar:**
     - *Internal:* **Silhouette Score** (separasi & kohesi cluster) & **Inertia** (Elbow method).
     - *Eksternal:* **Adjusted Rand Index (ARI)** terhadap ground truth.
     - *Kualitatif:* Ekstraksi **Top Terms** berbobot tertinggi pada tiap centroid cluster.
   - Visualisasi sebaran dokumen dan centroid dalam proyeksi 2D menggunakan **PCA**.
3. **Document Classification (Supervised Learning):**
   - Klasifikasi dokumen teks menggunakan **Scikit-Learn Pipeline** (`TfidfVectorizer` + `LogisticRegression`).
   - Pemisahan data latih & uji secara terstratifikasi (`train_test_split` dengan `stratify`).
   - Evaluasi performa melalui **Classification Report** (Precision, Recall, F1-Score, Support) dan visualisasi **Confusion Matrix**.
   - Inferensi prediksi untuk kalimat atau dokumen baru.
4. **Studi Kasus Dataset Riil:**
   - Penerapan pipeline pada dataset komentar YouTube (`processed_youtube_comments.csv`) dari Tugas 1.

---

## 📁 Struktur Berkas

```
tugas 2/
├── Tugas_2_Clustering_dan_Classification.ipynb   # File Jupyter Notebook lengkap (termasuk output & grafik)
├── main.py                                      # Script CLI Python standalone
├── generate_notebook.py                         # Script pembuat notebook otomatis
├── requirements.txt                             # Daftar dependensi pustaka
└── README.md                                    # Dokumentasi tugas
```

---

## 🚀 Cara Menjalankan

### Opsi 1: Menjalankan via Jupyter Notebook / VS Code / Google Colab
Buka berkas [Tugas_2_Clustering_dan_Classification.ipynb](file:///d:/Github/kuliah/semester%207/Information%20Retrieval%20B/tugas%202/Tugas_2_Clustering_dan_Classification.ipynb) di VS Code, Jupyter Notebook, JupyterLab, atau Google Colab, lalu jalankan semua sel secara berurutan (*Run All*).

### Opsi 2: Menjalankan via Terminal CLI
Pastikan dependensi telah terinstal:
```bash
pip install -r requirements.txt
python main.py
```

---

## 📊 Matriks Perbandingan Teori

| Aspek | Clustering (Unsupervised) | Classification (Supervised) |
| :--- | :--- | :--- |
| **Tujuan** | Mengelompokkan dokumen berdasarkan kemiripan fitur tanpa label target. | Memetakan dokumen baru ke kategori tertentu berdasarkan data latih berlabel. |
| **Input** | Matriks TF-IDF $X \in \mathbb{R}^{n \times m}$. | Matriks Dokumen $X$ dan Vektor Label Target $y$. |
| **Algoritma Utama** | **K-Means Clustering** ($k=2$, `n_init=10`). | **Logistic Regression**, Naive Bayes via `Pipeline`. |
| **Metrik Evaluasi** | • **Internal:** *Silhouette Score*, *Inertia/WCSS*<br>• **Eksternal:** *Adjusted Rand Index (ARI)*<br>• **Kualitatif:** *Top terms centroid & interpretasi semantik*. | • **Precision, Recall, F1-score, Akurasi**<br>• *Confusion Matrix*<br>• *Classification Report*. |
