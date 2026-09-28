"""
=============================================================================
Tugas 2: Information Retrieval - Text Clustering & Classification
Program Studi Sains Data - UPN "Veteran" Jawa Timur
=============================================================================
Topik  : Clustering dan Classification Menggunakan Representasi TF-IDF
Prinsip: "Dokumen menjadi vektor, lalu model menemukan kelompok atau memprediksi kategori."
=============================================================================
"""

import os
import sys
import numpy as np
import pandas as pd
import matplotlib.pyplot as plt

# Scikit-Learn
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.cluster import KMeans
from sklearn.metrics import (
    silhouette_score,
    adjusted_rand_score,
    classification_report,
    confusion_matrix
)
from sklearn.model_selection import train_test_split
from sklearn.pipeline import make_pipeline
from sklearn.linear_model import LogisticRegression


def run_demo_pipeline():
    print("=" * 70)
    print(" [1] REPRESENTASI VEKTOR TEKS: MATRIKS TF-IDF SPARSE")
    print("=" * 70)

    # Dataset Korpus Dual Domain
    docs = [
        "data science dan mesin cerdas berkembang pesat di era teknologi modern",       # D1: Tech
        "analisis data dan pembelajaran mesin untuk prediksi masa depan",                # D2: Tech
        "arsitektur jaringan komputer dan kecerdasan buatan untuk sistem cloud",          # D3: Tech
        "pengolahan data besar dan infrastruktur jaringan komputasi terdistribusi",      # D4: Tech
        "pertandingan bola sepak malam ini sangat sengit dan penuh aksi",                # D5: Sport
        "atlet mencetak gol indah dalam kejuaraan piala sepak bola",                     # D6: Sport
        "pemain bola dan atlet lari berlatih keras di lapangan stadion",                 # D7: Sport
        "strategi tendangan gol penalti menentukan kemenangan tim bola",                 # D8: Sport
    ]

    labels = [
        "Teknologi", "Teknologi", "Teknologi", "Teknologi",
        "Olahraga", "Olahraga", "Olahraga", "Olahraga"
    ]

    doc_ids = [f"D{i+1}" for i in range(len(docs))]

    # TfidfVectorizer sesuai modul slide perkuliahan
    tfidf = TfidfVectorizer(
        lowercase=True,
        sublinear_tf=True,
        norm="l2"
    )
    X = tfidf.fit_transform(docs)
    feature_names = tfidf.get_feature_names_out()

    print(f"• Dimensi Matriks TF-IDF: {X.shape} (Dokumen={X.shape[0]}, Vocabulary={X.shape[1]})")
    density = (X.nnz / (X.shape[0] * X.shape[1])) * 100
    print(f"• Kepadatan Matriks (Density): {density:.2f}% | Sparsity: {100 - density:.2f}%\n")

    # Tampilkan subset term seperti slide 06
    selected_terms = ['data', 'mesin', 'jaringan', 'bola', 'gol', 'atlet']
    available_terms = [t for t in selected_terms if t in feature_names]
    df_sparse = pd.DataFrame(X.toarray(), index=doc_ids, columns=feature_names)[available_terms]
    print("Matriks TF-IDF yang Sparse (Subset Terms):")
    print(df_sparse.map(lambda v: f"{v:.2f}" if v > 0 else "0"))

    print("\n" + "=" * 70)
    print(" [2] DOCUMENT CLUSTERING (UNSUPERVISED LEARNING - K-MEANS)")
    print("=" * 70)

    # K-Means Clustering (k=2)
    model = KMeans(n_clusters=2, n_init=10, random_state=42)
    cluster_id = model.fit_predict(X)

    # Evaluasi
    sil_score = silhouette_score(X, cluster_id)
    ari_score = adjusted_rand_score(labels, cluster_id)

    print(f"• Cluster ID Hasil Prediksi : {cluster_id}")
    print(f"• Metrik Internal (Silhouette Score) : {sil_score:.4f} (Mendekati 1.0 -> Terpisah baik)")
    print(f"• Metrik Internal (Inertia/WCSS)     : {model.inertia_:.4f}")
    print(f"• Metrik Eksternal (Adjusted Rand Index): {ari_score:.4f} (1.0 -> 100% cocok dengan label acuan)")

    # Ekstraksi Top Terms per Centroid
    print("\n--- Top Terms & Interpretasi Makna Tiap Cluster ---")
    centroids = model.cluster_centers_
    for c_i in range(model.n_clusters):
        top_idx = centroids[c_i].argsort()[::-1][:5]
        top_words = [f"{feature_names[i]} ({centroids[c_i][i]:.3f})" for i in top_idx]
        print(f"Cluster {c_i}: {', '.join(top_words)}")

    print("\n" + "=" * 70)
    print(" [3] DOCUMENT CLASSIFICATION (SUPERVISED LEARNING - PIPELINE)")
    print("=" * 70)

    # Split Data
    X_train, X_test, y_train, y_test = train_test_split(
        docs, labels, test_size=0.25, stratify=labels, random_state=42
    )

    # Pipeline: TF-IDF + Logistic Regression
    pipe = make_pipeline(
        TfidfVectorizer(sublinear_tf=True),
        LogisticRegression(max_iter=1000, random_state=42)
    )
    pipe.fit(X_train, y_train)
    y_pred = pipe.predict(X_test)

    print("Classification Report:")
    print(classification_report(y_test, y_pred))

    # Inference Dokumen Baru
    new_samples = [
        "pertandingan final sepak bola kejuaraan dunia",
        "penerapan arsitektur deep learning dan machine learning pada big data"
    ]
    new_preds = pipe.predict(new_samples)
    print("Prediksi Kalimat Baru:")
    for doc, pred in zip(new_samples, new_preds):
        print(f"  • \"{doc}\" -> [{pred}]")

    print("\n" + "=" * 70)
    print(" [OK] Eksekusi Seluruh Pipeline Selesai dengan Sukses!")
    print("=" * 70)


if __name__ == '__main__':
    run_demo_pipeline()
