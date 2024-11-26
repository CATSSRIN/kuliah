struct node{
    int data;
    node *next;
};
typedef struct node node;

struct queue{
    int count;
    node *front;
    node *rear;
};
typedef struct queue queue;


queue createqueue(void)
{
        queue myqueue;
        myqueue.count = 0;
        myqueue.front = NULL;
        myqueue.rear = NULL;
        return myqueue;
}