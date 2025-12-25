CREATE TABLE peserta (
    id_peserta INT AUTO_INCREMENT PRIMARY KEY,
    nama_peserta VARCHAR(100),
    jenis_kelamin ENUM('Laki-laki','Perempuan'),
    program_kursus VARCHAR(100),
    alamat TEXT,
    no_telepon VARCHAR(15)
);