<?php

// Mengambil data dari formulir
$id = $_POST['id'] ?? '';

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


// Memeriksa ID
if (
    !ctype_digit((string)$id) ||
    (int)$id < 1
) {
    die("ID data tidak valid.");
}


// Memeriksa kelengkapan data
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


// Memeriksa jumlah
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
    ($latitude === '') !== ($longitude === '')
) {
    die("Latitude dan longitude harus diisi bersamaan.");
}

if ($latitude !== '' && (
    !is_numeric($latitude) ||
    !is_numeric($longitude) ||
    (float)$latitude < -90 ||
    (float)$latitude > 90 ||
    (float)$longitude < -180 ||
    (float)$longitude > 180
)) {
    die("Periksa kembali koordinat latitude dan longitude.");
}


// Mengubah tipe data
$id = (int)$id;
$ogc_fid = (int)$ogc_fid;
$no = (int)$no;
$rw = (int)$rw;


// Mengatur nilai kosong menjadi NULL
$jumlah = ($jumlah === '') ? null : (string)(int)$jumlah;

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

$conn->set_charset("utf8mb4");


// Memperbarui data biopori
$sql = "UPDATE biopori SET
            ogc_fid = ?,
            `no` = ?,
            `nama/kode` = ?,
            kemantren = ?,
            kelurahan = ?,
            rw = ?,
            nama_bank_ = ?,
            alamat = ?,
            jumlah = ?,
            latitude = ?,
            longitude = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Gagal menyiapkan perintah: " . $conn->error);
}


// Menghubungkan parameter
$stmt->bind_param(
    "iisssisssssi",
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
    $longitude,
    $id
);


// Menjalankan perintah
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

    die("Data gagal diperbarui: " . $error);

}

?>