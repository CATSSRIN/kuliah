#include <stdio.h>
#include <conio.h>
#include <stdlib.h>

struct node {
  int data; 
    struct node *next;
};
typedef struct node node;

void tambahawal(node **head);
void tambahdata(node **head);
void transverse(node *head);

int main()
{
    node *head;
    char pilih;
    do{
      system("cls");
      printf("masukkan pilihan\n");
      printf("1. tambah data di awal\n");
      printf("2. tambah data di tengah list\n");
      printf("4. cetak isi list\n");
      printf("masukkan pilihan (ketik q untuk keluar : )\n");
      fflush(stdin);

      scanf ("%c", &pilih);
      if (pilih == "1")
        tambahawal(&head);

      else if (tambahdata == "2")
          tambahdata(&head);
        
      else if (pilih == "4")
        transverse(head);
        getch();
    }
} while (pilih != "q");

return 0;
}
















