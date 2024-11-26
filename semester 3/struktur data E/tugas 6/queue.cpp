#include <stdio.h>
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

void tambahawal(node **head){
  int bil;
  node *pNew;

  system("cls");
  fflush(stdin);
  printf("masukkan bilangan : ");
  fflush(stdin);
  scanf("%d", &bil);
  pNew = (node *)malloc(sizeof(node));

  if (pNew != NULL){
    pNew->data = bil;
    pNew->next = NULL;
    //add before first logical node or to an empty list
    pNew -> next = *head;
    *head = pNew;
  }
  else{
    printf("Alokasi memori gagal");
    getch();
  }
}

void tambahdata(node **head){
  int pos, bil;
  node *pNew, *pCur;

  system("cls");
  transverse(*head);
  printf("\nposisi penyisipan setelah data bernilai : ");
  fflush(stdin);
  scanf("%d", &pos);
  printf("\nbilangan : ");
  fflush(stdin);
  scanf("%d", &bil);

  pCur = *head;
  while (pCur != NULL && pCur -> data != pos) {
    pCur = pCur -> next;
  }

  pNew = (node *)malloc(sizeof(node));

  if (pCur == NULL){
    printf("\nnode tidak ditemukan");
    getch();
  }
  else if (pNew == NULL){
    printf("\nalokasi memeori gagal");
    getch();
  }
  else{
    pNew->data = bil;
    pNew->next = NULL;
    pNew -> next = pCur -> next;
    pCur -> next = pNew;
  }
}

void transverse(node *head){
  node *pWalker;

  pWalker = head;
  while (pWalker != NULL) {
    printf("%d -> ", pWalker->data);
    pWalker = pWalker->next;
  }
  printf("NULL\n");
}
















