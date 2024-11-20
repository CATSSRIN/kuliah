
if (strcmp(mhs[j].npm, mhs[j + 1].npm) > 0)

//----------------------------------------------------------------
//contoh

void tukar(Mahasiswa* a, Mahasiswa* b) {
    Mahasiswa temp = *a;
    *a = *b;
    *b = temp;
}

void bubbleSort(Mahasiswa mhs[], int n) {
    for (int i = 0; i < n - 1; i++) {
        for (int j = 0; j < n - i - 1; j++) {
            if (strcmp(mhs[j].npm, mhs[j + 1].npm) < 0) 
                {tukar(&mhs[j], &mhs[j + 1]);
            }
        }
    }
}