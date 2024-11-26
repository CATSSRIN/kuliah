#include <stdio.h>
#include <stdlib.h>

//========================================================

struct node {
    int data;
    struct node *next;
};
typedef struct node node;

typedef struct {
    node *head;
    node *tail;
    int count;
} Queue;

void nambah(Queue *queue);
void mengurangi(Queue *queue);
void print_all(Queue *queue);
void buat_queue_baru(Queue **queues, int *queue_count);
void delete_queue(Queue **queues, int *queue_count);
void buat_queue_baru(Queue *queue);

//========================================================

#define MAX_QUEUE_SIZE 3 // Limit queue size to 3

int main()
{
    Queue *queues[10] = { NULL }; // Supports up to 10 queues
    int queue_count = 1, active_queue = -1;
    char pilih;

    do {
        printf("\nMenu:\n");
        printf("1. Buat queue baru\n");
        printf("2. Hapus queue\n");
        printf("3. Pilih queue aktif\n");
        printf("4. Tambahkan nomor\n");
        printf("5. Hapus nomor depan\n");
        printf("6. Cetak isi queue aktif\n");
        printf("7. Hitung jumlah data di queue yang telah dipilih\n");
        printf("MASUKKAN PILIHAN (tekan q untuk keluar) : ");
        fflush(stdin);
        scanf(" %c", &pilih);

        switch (pilih) {
        case '1':
            buat_queue_baru(queues, &queue_count);
            break;
        case '2':
            delete_queue(queues, &queue_count);
            break;
        case '3':
            printf("Pilih queue (0-%d): ", queue_count - 1);
            fflush(stdin);
            scanf("%d", &active_queue);
            if (active_queue < 0 || active_queue >= queue_count || queues[active_queue] == NULL) {
                printf("queue tidak valid\n");
                active_queue = -1;
            }
            break;
        case '4':
            if (active_queue != -1)
                nambah(queues[active_queue]);
            else
                printf("Tidak ada queue aktif. Buat atau pilih queue dahulu.\n");
            break;
        case '5':
            if (active_queue != -1)
                mengurangi(queues[active_queue]);
            else
                printf("Tidak ada queue aktif. Buat atau pilih queue dahulu.\n");
            break;
        case '6':
            if (active_queue != -1)
                print_all(queues[active_queue]);
            else
                printf("Tidak ada queue aktif. Buat atau pilih queue dahulu.\n");
            break;
        case '7':
            if (active_queue != -1)
                buat_queue_baru(queues[active_queue]);
            else
                printf("Tidak ada queue aktif. Buat atau pilih queue dahulu.\n");
            break;
        case 'q':
            printf("Keluar dari program.\n");
            break;
        default:
            printf("Pilihan tidak valid.\n");
        }
    } while (pilih != 'q');

    // Free all queues
    for (int i = 0; i < queue_count; i++) {
        if (queues[i] != NULL) {
            while (queues[i]->head != NULL)
                mengurangi(queues[i]);
            free(queues[i]);
        }
    }

    return 0;
}

//========================================================

void nambah(Queue *queue) {
    if (queue->count >= MAX_QUEUE_SIZE) {
        printf("queue penuh.\n");
        return;
    }

    int bil;
    node *pNew;

    printf("Masukkan bilangan: ");
    fflush(stdin);
    scanf("%d", &bil);
    pNew = (node *)malloc(sizeof(node));

    if (pNew != NULL) {
        pNew->data = bil;
        pNew->next = NULL;
        if (queue->tail != NULL) {
            queue->tail->next = pNew;
        }
        queue->tail = pNew;
        if (queue->head == NULL) {
            queue->head = pNew;
        }
        queue->count++;
    } else {
        printf("gagal memasukkan data.\n");
    }
}

//========================================================

void mengurangi(Queue *queue) {
    if (queue->head != NULL) {
        node *pDel = queue->head;
        queue->head = queue->head->next;
        if (queue->head == NULL) {
            queue->tail = NULL;
        }
        free(pDel);
        queue->count--;
    } else {
        printf("queue kosong.\n");
    }
}

//========================================================

void print_all(Queue *queue) {
    if (queue->head == NULL) {
        printf("queue kosong.\n");
        return;
    }

    node *pWalker = queue->head;
    while (pWalker != NULL) {
        printf("%d -> ", pWalker->data);
        pWalker = pWalker->next;
    }
    printf("NULL\n");
}

//========================================================

void buat_queue_baru(Queue **queues, int *queue_count) {
    if (*queue_count >= 10) {
        printf("Maksimal queue tercapai.\n");
        return;
    }

    queues[*queue_count] = (Queue *)malloc(sizeof(Queue));
    if (queues[*queue_count] != NULL) {
        queues[*queue_count]->head = NULL;
        queues[*queue_count]->tail = NULL;
        queues[*queue_count]->count = 0;
        printf("queue %d berhasil dibuat.\n", *queue_count);
        (*queue_count)++;
    } else {
        printf("Gagal membuat queue baru.\n");
    }
}

//========================================================

void delete_queue(Queue **queues, int *queue_count) {
    int index;
    printf("Pilih queue yang akan dihapus (0-%d): ", *queue_count - 1);
    fflush(stdin);
    scanf("%d", &index);

    if (index < 0 || index >= *queue_count || queues[index] == NULL) {
        printf("queue tidak valid.\n");
        return;
    }

    while (queues[index]->head != NULL)
        mengurangi(queues[index]);
    free(queues[index]);
    queues[index] = NULL;
    printf("queue %d berhasil dihapus.\n", index);
}

//========================================================

void buat_queue_baru(Queue *queue) {
    printf("Jumlah data dalam queue: %d\n", queue->count);
}
