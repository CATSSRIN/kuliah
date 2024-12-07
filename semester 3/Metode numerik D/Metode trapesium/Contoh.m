
% definisikan batas-batas integral
a = 0; % batas bawah
b = 4; % batas atas

% definisikan fungsi f(x) = exp(x)
f = @(x) exp(x);

% hitung nilai eksak integral secara analitis
I_analitis = exp(b) - exp(a);

% hitung dengan metode trapezium satu pias
I_trapesium = (b - a) * (f(a) + f(b)) / 2;

% hitung kesalahan relatif terhadap nilai eksak
error_rel = (I_analitis - I_trapesium) / I_analitis * 100;

% tampikan hasil
fprintf('Nilai eksak integral: %.6f\n', I_analitis);
fprintf('Hasil integral dengan metode trapesium: %.6f\n', I_trapesium);
fprintf('Kesalahan relatif: %.2f%%\n', error_rel);
