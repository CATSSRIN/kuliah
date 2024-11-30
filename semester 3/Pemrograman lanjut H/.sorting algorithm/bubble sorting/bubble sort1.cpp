void bubble_sort (int data[]) {
    for (int i=1; 1<n; i++) {
        for (int j=n-1; j>=i; j--) {
            if (data[j]<data[j-1])
                tukar(&data[j], &data[j-1]);

        }
    }
}

