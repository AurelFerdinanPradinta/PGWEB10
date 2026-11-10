```php
<?php

// Mengambil data dari formulir
$ogc_fid = $_POST['ogc_fid'] ?? '';
$no = $_POST['no'] ?? '';
$nama_kode = trim($_POST['nama_kode'] ?? '');
$kemantren = trim($_POST['kemantren'] ?? '');
$kelurahan = trim($_POST['kelurahan'] ?? '');
$rw = $_POST['rw'] ?? '';
$nama_bank = trim($_POST['nama_bank_'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$jumlah = $_POST['jumlah'] ?? '';
$latitude = trim($_POST['latitude'] ?? '');
$longitude = trim($_POST['longitude'] ?? '');


// Memeriksa kelengkapan data wajib
if (
    !ctype_digit((string)$ogc_fid) ||
    (int)$ogc_fid < 1 ||
    !ctype_digit((string)$no) ||
    (int)$no < 1 ||
    $nama_kode === '' ||
    $kemantren === '' ||
    $kelurahan === '' ||
    !ctype_digit((string)$rw) ||
    $nama_bank === '' ||
    $alamat === ''
) {
    die("Periksa kembali kelengkapan data.");
}


// Memeriksa jumlah apabila diisi
if (
    $jumlah !== '' &&
    (
        !ctype_digit((string)$jumlah) ||
        (int)$jumlah < 0
    )
) {
    die("Jumlah harus berupa bilangan bulat dan tidak boleh negatif.");
}


// Memeriksa koordinat
if (
    ($latitude === '') !== ($longitude === '') ||
    ($latitude !== '' && (
        !is_numeric($latitude) ||
        !is_numeric($longitude) ||
        (float)$latitude < -90 ||
        (float)$latitude > 90 ||
        (float)$longitude < -180 ||
        (float)$longitude > 180
    ))
) {
    die("Periksa kembali koordinat latitude dan longitude.");
}


// Mengubah data numerik
$ogc_fid = (int)$ogc_fid;
$no = (int)$no;
$rw = (int)$rw;


// Mengubah nilai kosong menjadi NULL
$jumlah = ($jumlah === '') ? null : (int)$jumlah;
$latitude = ($latitude === '') ? null : $latitude;
$longitude = ($longitude === '') ? null : $longitude;


// Pengaturan koneksi database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bioporiyogya";


// Membuat koneksi
$conn = new mysqli(
    $servername,
    $username,
    $password,
    $dbname
);


// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}


// Mengatur karakter database
$conn->set_charset("utf8mb4");


// Menyiapkan perintah INSERT
$stmt = $conn->prepare(
    "INSERT INTO biopori
    (
        ogc_fid,
        `no`,
        `nama/kode`,
        kemantren,
        kelurahan,
        rw,
        nama_bank_,
        alamat,
        jumlah,
        latitude,
        longitude
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
);


if (!$stmt) {
    die("Gagal menyiapkan perintah: " . $conn->error);
}


// Menghubungkan parameter
$stmt->bind_param(
    "iisssisssss",
    $ogc_fid,
    $no,
    $nama_kode,
    $kemantren,
    $kelurahan,
    $rw,
    $nama_bank,
    $alamat,
    $jumlah,
    $latitude,
    $longitude
);


// Menjalankan penyimpanan data
if ($stmt->execute()) {

    $stmt->close();
    $conn->close();

    // Kembali ke halaman utama
    header("Location: ../index.php");
    exit;

} else {

    $error = $stmt->error;

    $stmt->close();
    $conn->close();

    die("Data gagal disimpan: " . $error);
}

?>
```