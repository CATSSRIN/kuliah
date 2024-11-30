void bubblesort2 (int data[]) {
    for (int i=1; i<6; i++) {
        for (int j=0; j<6-1; j++) {
            if (data[j]>data[j+1])
                tukar(&data[j], &data[j+1]);

        }
    }
}