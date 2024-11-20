#include <stdio.h>
#include <stdlib.h>  // Untuk malloc(), calloc(), exit()
#include <string.h>  // Untuk strtok(), strcmp(), dll.

int main() {
  FILE *instream;
  char fname[81];          // Menyimpan nama file
  char buffer[101];        // Menyimpan baris saat ini dari file
  char *token;             // Menyimpan token saat ini
  char *tokens[100];       // Array untuk menyimpan token unik
  int tokenline[100];      // Array untuk menyimpan nomor baris di mana token ditemukan
  int ntokens = 0;         // Jumlah token yang ditemukan sejauh ini
  int linenum = 0;         // Nomor baris saat ini
  int tnum;

  printf("File: ");
  scanf("%80s", fname);

  // Membuka file untuk dibaca
  if ((instream = fopen(fname, "r")) == NULL) {
    printf("Tidak bisa membuka file %s\n", fname);
    exit(-1);
  }

  // Membaca file baris per baris
  while (fgets(buffer, sizeof(buffer), instream) != NULL) {
    linenum++;
    // Mendapatkan token pertama dari buffer
    token = strtok(buffer, " \t\n");
    while (token != NULL) {
      tnum = 0;
      // Mencari token dalam array tokens
      while ((tnum < ntokens) && (strcmp(token, tokens[tnum]) != 0))
        tnum++;

      // Jika token tidak ditemukan, tambahkan ke array tokens
      if (tnum == ntokens) {
        tokens[tnum] = (char *)calloc(strlen(token) + 1, sizeof(char));
        strcpy(tokens[tnum], token);   // Salin token ke array
        tokenline[tnum] = linenum;     // Simpan nomor baris di mana token pertama kali ditemukan
        ntokens++;
      }

      // Mendapatkan token berikutnya
      token = strtok(NULL, " \t\n");
    }
  }

  // Mencetak token yang ditemukan dan nomor baris pertama kali muncul
  for (tnum = 0; tnum < ntokens; tnum++) {
    printf("%s first appears on line %d\n", tokens[tnum], tokenline[tnum]);
  }

  // Menutup file
  fclose(instream);

  // Bebaskan memori yang dialokasikan untuk setiap token
  for (tnum = 0; tnum < ntokens; tnum++) {
    free(tokens[tnum]);
  }

  return 0;
}
