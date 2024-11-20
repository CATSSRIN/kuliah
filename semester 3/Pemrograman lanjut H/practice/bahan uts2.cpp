#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include <time.h>

// Struktur untuk Buku
struct Buku {
    int id;
    char judul[100];
    char penulis[100];
    int stok;
    int dipinjam;
    int kategori;
};

// Struktur untuk Anggota
struct Anggota {
    int id;
    char nama[100];
    char kategori[50];
    int maxPeminjaman;
    int jumlahDipinjam;
    float denda;
};

// Struktur untuk Transaksi Peminjaman
struct Transaksi {
    int idAnggota;
    int idBuku;
    time_t tanggalPinjam;
    time_t tanggalKembali;
    int terlambat;
    float denda;
};

// Data buku dan anggota
struct Buku buku[100];
struct Anggota anggota[100];
struct Transaksi transaksi[100];
int totalBuku = 0;
int totalAnggota = 0;
int totalTransaksi = 0;

// Fungsi untuk menampilkan menu
void tampilkanMenu() {
    printf("===== Menu Perpustakaan =====\n");
    printf("1. Validasi Keanggotaan\n");
    printf("2. Batas Maksimum Peminjaman\n");
    printf("3. Durasi Peminjaman\n");
    printf("4. Denda Keterlambatan\n");
    printf("5. Ganti Rugi\n");
    printf("6. Status Ketersediaan Buku\n");
    printf("7. Data Pengunjung\n");
    printf("8. Kategori Pengunjung\n");
    printf("9. Buku Terpopuler\n");
    printf("10. Jumlah Transaksi Peminjaman dan Pengembalian Buku\n");
    printf("11. Data Anggota\n");
    printf("12. Perubahan Status Buku\n");
    printf("13. Pembayaran Denda\n");
    printf("14. Stok Buku\n");
    printf("15. Audit Stok Buku\n");
    printf("16. Menambahkan anggota\n");
    printf("0. Keluar\n");
    printf("=============================\n");
}

void add_member() {
    char nama[50];
    printf("Masukkan nama anggota: ");
    getchar(); // Mengatasi masalah buffer
    fgets(nama, sizeof(nama), stdin);
    nama[strcspn(nama, "\n")] = '\0'; // Menghapus karakter newline
    strcpy(anggota[totalAnggota].nama, nama);
    anggota[totalAnggota].id = 1;
    anggota[totalAnggota].jumlahDipinjam = 0;
    totalAnggota++;
    printf("Anggota '%s' telah ditambahkan.\n", nama);
}

// Fungsi untuk validasi keanggotaan
void validasiKeanggotaan() {
    int id;
    printf("Masukkan ID Anggota: ");
    scanf("%d", &id);
    for (int i = 0; i < totalAnggota; i++) {
        if (anggota[i].id == id) {
            printf("Anggota ditemukan: %s\n", anggota[i].nama);
            return;
        }
    }
    printf("Anggota tidak ditemukan.\n");
}

// Fungsi untuk melihat batas maksimum peminjaman
void batasMaksimumPeminjaman() {
    int id;
    printf("Masukkan ID Anggota: ");
    scanf("%d", &id);
    for (int i = 0; i < totalAnggota; i++) {
        if (anggota[i].id == id) {
            printf("Anggota: %s, Batas Maksimum Peminjaman: %d\n", anggota[i].nama, anggota[i].maxPeminjaman);
            return;
        }
    }
    printf("Anggota tidak ditemukan.\n");
}

// Fungsi untuk menampilkan durasi peminjaman
void durasiPeminjaman() {
    printf("Durasi peminjaman standar adalah 14 hari.\n");
}

// Fungsi untuk menghitung denda keterlambatan
void dendaKeterlambatan() {
    int idAnggota;
    printf("Masukkan ID Anggota: ");
    scanf("%d", &idAnggota);
    for (int i = 0; i < totalAnggota; i++) {
        if (anggota[i].id == idAnggota) {
            printf("Denda keterlambatan untuk anggota %s: Rp%.2f\n", anggota[i].nama, anggota[i].denda);
            return;
        }
    }
    printf("Anggota tidak ditemukan.\n");
}

// Fungsi untuk ganti rugi buku yang hilang
void gantiRugi() {
    int idBuku;
    printf("Masukkan ID Buku yang hilang: ");
    scanf("%d", &idBuku);
    for (int i = 0; i < totalBuku; i++) {
        if (buku[i].id == idBuku) {
            printf("Biaya ganti rugi untuk buku \"%s\": Rp200,000\n", buku[i].judul);
            return;
        }
    }
    printf("Buku tidak ditemukan.\n");
}

// Fungsi untuk mengecek status ketersediaan buku
void statusKetersediaan() {
    int idBuku;
    printf("Masukkan ID Buku: ");
    scanf("%d", &idBuku);
    for (int i = 0; i < totalBuku; i++) {
        if (buku[i].id == idBuku) {
            printf("Buku \"%s\" tersedia sebanyak %d eksemplar.\n", buku[i].judul, buku[i].stok - buku[i].dipinjam);
            return;
        }
    }
    printf("Buku tidak ditemukan.\n");
}

// Fungsi untuk menampilkan data pengunjung (anggota)
void dataPengunjung() {
    printf("===== Data Pengunjung =====\n");
    for (int i = 0; i < totalAnggota; i++) {
        printf("ID: %d, Nama: %s, Kategori: %s\n", anggota[i].id, anggota[i].nama, anggota[i].kategori);
    }
}

// Fungsi untuk menampilkan kategori pengunjung
void kategoriPengunjung() {
    printf("1. Mahasiswa\n");
    printf("2. Dosen\n");
    printf("3. Umum\n");
}

// Fungsi untuk menampilkan buku terpopuler (yang paling banyak dipinjam)
void bukuTerpopuler() {
    int maxDipinjam = 0;
    int idBukuTerpopuler = -1;
    for (int i = 0; i < totalBuku; i++) {
        if (buku[i].dipinjam > maxDipinjam) {
            maxDipinjam = buku[i].dipinjam;
            idBukuTerpopuler = i;
        }
    }
    if (idBukuTerpopuler != -1) {
        printf("Buku terpopuler: \"%s\" dengan %d kali peminjaman.\n", buku[idBukuTerpopuler].judul, buku[idBukuTerpopuler].dipinjam);
    } else {
        printf("Tidak ada data buku yang dipinjam.\n");
    }
}

// Fungsi untuk menampilkan jumlah transaksi
void jumlahTransaksi() {
    printf("Total transaksi peminjaman dan pengembalian: %d\n", totalTransaksi);
}

// Fungsi untuk menampilkan data anggota
void dataAnggota() {
    printf("===== Data Anggota =====\n");
    for (int i = 0; i < totalAnggota; i++) {
        printf("ID: %d, Nama: %s, Kategori: %s\n", anggota[i].id, anggota[i].nama, anggota[i].kategori);
    }
}

// Fungsi untuk perubahan status buku
void perubahanStatusBuku() {
    int idBuku;
    printf("Masukkan ID Buku: ");
    scanf("%d", &idBuku);
    for (int i = 0; i < totalBuku; i++) {
        if (buku[i].id == idBuku) {
            printf("Masukkan stok terbaru untuk buku \"%s\": ", buku[i].judul);
            scanf("%d", &buku[i].stok);
            printf("Stok buku berhasil diperbarui.\n");
            return;
        }
    }
    printf("Buku tidak ditemukan.\n");
}

// Fungsi untuk pembayaran denda
void pembayaranDenda() {
    int idAnggota;
    printf("Masukkan ID Anggota: ");
    scanf("%d", &idAnggota);
    for (int i = 0; i < totalAnggota; i++) {
        if (anggota[i].id == idAnggota) {
            printf("Total denda yang harus dibayar: Rp%.2f\n", anggota[i].denda);
            printf("Masukkan jumlah yang dibayar: ");
            float pembayaran;
            scanf("%f", &pembayaran);
            if (pembayaran >= anggota[i].denda) {
                anggota[i].denda = 0;
                printf("Denda lunas.\n");
            } else {
                anggota[i].denda -= pembayaran;
                printf("Sisa denda: Rp%.2f\n", anggota[i].denda);
            }
            return;
        }
    }
    printf("Anggota tidak ditemukan.\n");
}

// Fungsi untuk menampilkan stok buku
void stokBuku() {
    printf("===== Data Stok Buku =====\n");
    for (int i = 0; i < totalBuku; i++) {
        printf("ID: %d, Judul: %s, Stok: %d, Dipinjam: %d\n", buku[i].id, buku[i].judul, buku[i].stok, buku[i].dipinjam);
    }
}

// Fungsi audit stok buku
void auditStok() {
    printf("===== Audit Stok Buku =====\n");
    for (int i = 0; i < totalBuku; i++) {
        printf("Buku: \"%s\", Stok: %d, Dipinjam: %d\n", buku[i].judul, buku[i].stok, buku[i].dipinjam);
    }
}

// Fungsi utama
int main() {
    int pilihan;
    do {
        tampilkanMenu();
        printf("Pilih menu: ");
        scanf("%d", &pilihan);
        switch (pilihan) {
            case 1: validasiKeanggotaan(); break;
            case 2: batasMaksimumPeminjaman(); break;
            case 3: durasiPeminjaman(); break;
            case 4: dendaKeterlambatan(); break;
            case 5: gantiRugi(); break;
            case 6: statusKetersediaan(); break;
            case 7: dataPengunjung(); break;
            case 8: kategoriPengunjung(); break;
            case 9: bukuTerpopuler(); break;
            case 10: jumlahTransaksi(); break;
            case 11: dataAnggota(); break;
            case 12: perubahanStatusBuku(); break;
            case 13: pembayaranDenda(); break;
            case 14: stokBuku(); break;
            case 15: auditStok(); break;
            case 16: add_member(); break;
            case 0: printf("Keluar dari program.\n"); break;
            default: printf("Pilihan tidak valid.\n");
        }
    } while (pilihan != 0);

    return 0;
}
