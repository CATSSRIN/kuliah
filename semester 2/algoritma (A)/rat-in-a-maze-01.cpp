// -- Header 'bits/stdc++.h' sudah include headers yang diperlukan.
#include <bits/stdc++.h>

using namespace std;

// -- Function untuk menentukan movement dari 'Rat'
void findMinPathSum(vector<vector<int>>& matrix, int row, int col, int& pathSum, int& minPath) {
    pathSum += matrix[row][col];

    if (row == matrix.size() - 1 && col == matrix[0].size() - 1) {
        minPath = min(minPath, pathSum);
    }
    else {
        if (row < matrix.size() - 1) {
            findMinPathSum(matrix, row + 1, col, pathSum, minPath);
        }
        if (col < matrix[0].size() - 1) {
            findMinPathSum(matrix, row, col + 1, pathSum, minPath);
        }
    }

    pathSum -= matrix[row][col];
}

int main()
{
    int size;
    cin >> size;
    vector<vector<int>> grid(size, vector<int>(size));
    for (int row = 0; row < size; row++)
    {
        for (int col = 0; col < size; col++)
        {
            cin >> grid[row][col];
        }
    }

    int pathSum = 0;
    int minPath = INT_MAX;
    findMinPathSum(grid, 0, 0, pathSum, minPath);
    cout << minPath << endl;
}

/*
-- 'size' adalah ukuran dari matrix (maze).
-- 'pathSum' adalah jumlah dari total langkah yang diambil.
-- 'minPath' adalah langkah paling minium yang dapat diambil untuk mencapai titik akhir.
== 'findMinPathSum' berfungsi untuk mencari langkah tercepat dari tiap row & column pada matrix (maze).
*/