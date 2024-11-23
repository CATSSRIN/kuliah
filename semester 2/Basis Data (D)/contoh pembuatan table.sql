create table mahasiswa(
	npm int,
    nama varchar(50),
    alamat varchar(50),
    tanggal_lahir date
    
);

create table mata_kuliah(
	kode_matkul varchar(10),
    nama varchar(50),
    sks int

);

create table nilai(
	id varchar(50),
    npm int(20),
    kelas int(3),
    nilai decimal(2, 2)

);


