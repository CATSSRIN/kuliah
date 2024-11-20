#include <stdio.h>
#include <stdlib.h>  // Untuk exit()
#include <string.h>  // Untuk fungsi string seperti sprintf()

int main() {
  FILE *instream;
  FILE *outstream;
  char basefname[81];   // Prefix untuk nama file input
  char readfname[101];  // Nama file input yang lengkap
  char savefname[81];   // Nama file tempat menyimpan output
  char buffer[101];     // Menyimpan setiap baris yang dibaca
  int fnum;

  // Input nama prefix dan file tujuan output
  printf("File Prefix: ");
  scanf("%80s", basefname);
  printf("Save to File: ");
  scanf("%80s", savefname);

  // Membuka file output untuk ditulis
  if ((outstream = fopen(savefname, "w")) == NULL) {
    printf("Unable to open %s\n", savefname);
    exit(-1);
  }

  // Membuka 5 file input dengan nama berformat: <basefname>.<fnum>
  for (fnum = 0; fnum < 5; fnum++) {
    sprintf(readfname, "%s.%d", basefname, fnum);

    // Membuka file input untuk dibaca
    if ((instream = fopen(readfname, "r")) == NULL) {
      printf("Unable to open input file %s\n", readfname);
      fclose(outstream);  // Pastikan file output ditutup jika ada error
      exit(-1);
    }

    // Menyalin isi file input ke file output
    while (fgets(buffer, sizeof(buffer), instream) != NULL) {
      fputs(buffer, outstream);
    }

    fclose(instream);  // Menutup file input setelah selesai membaca
  }

  fclose(outstream);  // Menutup file output setelah selesai menulis
  return 0;
}
