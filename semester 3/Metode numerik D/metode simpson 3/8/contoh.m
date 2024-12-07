% fungsi yang ingin diintegralkan
f = @(x) exp(x);

% batas integral
a = 0; % batas bawah
b = 4; % batas atas

% jumlah pias (dalam kasus ini, 1 pias)
n = 1;

% lebar setiap pias
h = (b - a) / (3 * n);

% titik-titik yang digunakan untuk menghitung nilai fungsi
x0 = a;
x1 = a + h;
x2 = a + 2 * h;
x3 = b;

% menghitung nilai fungsi di setiap titik
f0 = f(x0);
f1 = f(x1);
f2 = f(x2);
f3 = f(x3);

% hitung nilai integral dengan aturan Simpson 3/8
I = (b - a) / 8 * (f0 + 3 * f1 + 3 * f2 + f3);

% hitung nilai eksak integral (untuk referensi)
I_eksak = exp(b) - exp(a);

% hitung kesalahan relatif
error_relatif = (I - I_eksak) / I_eksak * 100;

% tampilkan kesalahan relatif
disp(['kesalahan relatif: ', num2str(error_relatif), '%']);