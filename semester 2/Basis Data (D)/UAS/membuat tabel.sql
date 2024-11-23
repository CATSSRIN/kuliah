create table mahasiswa(
	npm int(20),
    nama varchar(50),
    alamat varchar(100),
    tanggal_lahir date
);

create table mata_kuliah(
	kodemat varchar(6),
    Nama_kelas varchar(20),
    sks int(2)

);

create table nilai(
	id int(2),
    npm int(20),
    kelas varchar(20),
    nilai decimal(5, 2)

);