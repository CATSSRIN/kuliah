#include <stdio.h>
#include <string.h>


struct buku {
    int id;
    char nama_buku[100];
    char author[100];
    int stock;
    char category;
};

int main() {
    buku myBook;
    myBook.id = 1;
    strcpy(myBook.nama_buku, "A");
    strcpy(myBook.author, "B");
    myBook.stock = 10;
    myBook.category = 'C';

    printf("ID: %d\n", myBook.id);
    printf("Nama Buku: %s\n", myBook.nama_buku);
    printf("Author: %s\n", myBook.author);
    printf("Stock: %d\n", myBook.stock);
    printf("Category: %c\n", myBook.category);


    buku tatang;
    tatang.id = 2;
    strcpy(tatang.nama_buku, "tatang");
    strcpy(tatang.author, "radit");
    tatang.stock = 20;
    tatang.category = 'D';  

    printf("ID: %d\n", tatang.id);
    printf("nama buku: %s\n", tatang.nama_buku);
    printf("author: %s\n", tatang.author);
    printf("stock: %d\n", tatang.stock);
    printf("category: %c\n", tatang.category);

    return 0;
}