void insertion_sort (int data[]) {
    int temp;
    for (int i=1; i<n; i++) {
        temp = data[i];
        j = i -1;
        while (data[j]>temp && j>=0) {
            data[j+i] = data[j];
            j--;
        }
        data[j+1] = temp;
    }
}