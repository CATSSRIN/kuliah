#include <iostream>
#include <vector>

using namespace std;

// Function to check if the current cell is valid or not
bool isValidCell(int x, int y, int n, vector<vector<int>>& maze) {
    return (x >= 0 && x < n && y >= 0 && y < n && maze[x][y] == 1);
}

// Function to solve the rat in a maze problem using backtracking
bool solveMaze(int x, int y, int n, vector<vector<int>>& maze, vector<vector<int>>& path) {
    // If the rat reaches the destination, return true
    if (x == n - 1 && y == n - 1) {
        path[x][y] = 1;
        return true;
    }

    // Check if the current cell is valid
    if (isValidCell(x, y, n, maze)) {
        // Mark the current cell as part of the path
        path[x][y] = 1;

        // Move right
        if (solveMaze(x, y + 1, n, maze, path))
            return true;

        // Move down
        if (solveMaze(x + 1, y, n, maze, path))
            return true;

        // If none of the above movements work, backtrack
        path[x][y] = 0;
        return false;
    }

    return false;
}

int main() {
    int n;
    cout << "Enter the size of the maze: ";
    cin >> n;

    vector<vector<int>> maze(n, vector<int>(n));
    vector<vector<int>> path(n, vector<int>(n, 0));

    cout << "Enter the maze (0 for blocked cell, 1 for open cell):\n";
    for (int i = 0; i < n; i++) {
        for (int j = 0; j < n; j++) {
            cin >> maze[i][j];
        }
    }

    if (solveMaze(0, 0, n, maze, path)) {
        cout << "Path found!\n";
        cout << "The path taken by the rat:\n";
        for (int i = 0; i < n; i++) {
            for (int j = 0; j < n; j++) {
                cout << path[i][j] << " ";
            }
            cout << endl;
        }
    } else {
        cout << "No path found!\n";
    }

    return 0;
}