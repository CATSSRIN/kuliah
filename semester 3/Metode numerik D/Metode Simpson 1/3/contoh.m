
% batas integral
a = 0; % batas bawah
b = 4; % batas atas

% fungsi yang akan diintegrasikan 
f = @(x) exp(x);

% nilai tengah (c)
c = (a + b) / 2;

% menghitung integral menggunakan aturan simpson 1/3 dengan satu pies
I = (b - a) / 6 * (f(a) + 4 * f(c) + f(b));

% menampilkan hasil
fprintf('Hasil integral dengan metode Simpson 1/3: %.4f\n', I);

% menghitung eksak integral (untuk referensi)
I_eksak = exp(b) - exp(a);

% menghitung kesalahan relatif 
epsilon = abs((I_eksak - I) / I_eksak) * 100;

% menampilkan kesalahan relatif
fprintf('Kesalahan relatif terhadap nilai eksak adalah: %.3f%%\n', epsilon);