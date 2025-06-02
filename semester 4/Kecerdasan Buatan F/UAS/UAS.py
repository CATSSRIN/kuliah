# Langkah 2: Import library yang dibutuhkan
import numpy as np
import skfuzzy as fuzz
from skfuzzy import control as ctrl
import matplotlib.pyplot as plt

# Langkah 3: Definisi Variabel Linguistik dan Fungsi Keanggotaan

# Antecedents (Input)
# Kualitas Pelayanan: dinilai dari 0 hingga 10
kualitas_pelayanan = ctrl.Antecedent(np.arange(0, 11, 1), 'kualitas_pelayanan')
# Kualitas Makanan: dinilai dari 0 hingga 10
kualitas_makanan = ctrl.Antecedent(np.arange(0, 11, 1), 'kualitas_makanan')

# Consequent (Output)
# Besarnya Tip: dalam persentase, dari 0 hingga 25%
tip = ctrl.Consequent(np.arange(0, 26, 1), 'tip')

# Membuat fungsi keanggotaan otomatis untuk kualitas pelayanan
# (buruk, sedang, baik)
kualitas_pelayanan['buruk'] = fuzz.trimf(kualitas_pelayanan.universe, [0, 0, 5])
kualitas_pelayanan['sedang'] = fuzz.trimf(kualitas_pelayanan.universe, [0, 5, 10])
kualitas_pelayanan['baik'] = fuzz.trimf(kualitas_pelayanan.universe, [5, 10, 10])

# Membuat fungsi keanggotaan otomatis untuk kualitas makanan
# (tidak_enak, biasa_saja, enak)
kualitas_makanan['tidak_enak'] = fuzz.trimf(kualitas_makanan.universe, [0, 0, 5])
kualitas_makanan['biasa_saja'] = fuzz.trimf(kualitas_makanan.universe, [0, 5, 10])
kualitas_makanan['enak'] = fuzz.trimf(kualitas_makanan.universe, [5, 10, 10])

# Membuat fungsi keanggotaan untuk tip
# (rendah, sedang, tinggi)
tip['rendah'] = fuzz.trimf(tip.universe, [0, 0, 13])
tip['sedang'] = fuzz.trimf(tip.universe, [0, 13, 25])
tip['tinggi'] = fuzz.trimf(tip.universe, [13, 25, 25])

# Untuk melihat bentuk fungsi keanggotaan (opsional)
# kualitas_pelayanan.view()
# kualitas_makanan.view()
# tip.view()
# plt.show()

# Langkah 4: Definisi Aturan Fuzzy
# Aturan 1: JIKA pelayanan buruk ATAU makanan tidak_enak MAKA tip rendah
aturan1 = ctrl.Rule(kualitas_pelayanan['buruk'] | kualitas_makanan['tidak_enak'], tip['rendah'])

# Aturan 2: JIKA pelayanan sedang MAKA tip sedang
aturan2 = ctrl.Rule(kualitas_pelayanan['sedang'], tip['sedang'])

# Aturan 3: JIKA pelayanan baik ATAU makanan enak MAKA tip tinggi
aturan3 = ctrl.Rule(kualitas_pelayanan['baik'] | kualitas_makanan['enak'], tip['tinggi'])

# Aturan 4: JIKA pelayanan baik DAN makanan enak MAKA tip tinggi
aturan4 = ctrl.Rule(kualitas_pelayanan['baik'] & kualitas_makanan['enak'], tip['tinggi'])

# Aturan 5: JIKA pelayanan buruk DAN makanan tidak_enak MAKA tip rendah
aturan5 = ctrl.Rule(kualitas_pelayanan['buruk'] & kualitas_makanan['tidak_enak'], tip['rendah'])


# Langkah 5: Pembuatan Sistem Kontrol Fuzzy
# Membuat kontrol sistem dengan aturan yang telah didefinisikan
sistem_kontrol_tip = ctrl.ControlSystem([aturan1, aturan2, aturan3, aturan4, aturan5])

# Membuat instance dari sistem kontrol (simulasi)
penentu_tip = ctrl.ControlSystemSimulation(sistem_kontrol_tip)

# Langkah 6: Simulasi
# Memberikan input ke sistem
# Contoh: Kualitas pelayanan = 6.5, Kualitas makanan = 9.8
penentu_tip.input['kualitas_pelayanan'] = 6.5
penentu_tip.input['kualitas_makanan'] = 9.8

# Melakukan perhitungan
penentu_tip.compute()

# Mendapatkan output
nilai_tip = penentu_tip.output['tip']
print(f"Besarnya tip yang direkomendasikan: {nilai_tip:.2f}%")

# Kita juga bisa melihat visualisasi output dan fungsi keanggotaan yang aktif
tip.view(sim=penentu_tip)
plt.show()

# Contoh lain
penentu_tip.input['kualitas_pelayanan'] = 2.0
penentu_tip.input['kualitas_makanan'] = 4.0
penentu_tip.compute()
print(f"Besarnya tip untuk pelayanan 2.0 dan makanan 4.0: {penentu_tip.output['tip']:.2f}%")
tip.view(sim=penentu_tip)
plt.show()