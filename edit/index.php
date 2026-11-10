<?php

// Memeriksa ID dari URL
if (
    !isset($_GET['id']) ||
    !ctype_digit($_GET['id']) ||
    (int)$_GET['id'] < 1
) {
    die("ID data tidak valid.");
}

$id = (int)$_GET['id'];


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


// Mengambil data berdasarkan ID
$stmt = $conn->prepare(
    "SELECT * FROM biopori WHERE id = ?"
);

if (!$stmt) {
    die("Gagal menyiapkan perintah: " . $conn->error);
}

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();


// Memeriksa data
if ($result->num_rows === 0) {
    die("Data biopori tidak ditemukan.");
}

$row = $result->fetch_assoc();

$stmt->close();
$conn->close();


// Mengamankan tampilan data
function e($value) {
    return htmlspecialchars(
        (string)($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Data Biopori</title>
</head>

<body>

    <h2>Form Edit Data Biopori</h2>

    <form action="edit.php" method="POST">

        <input
            type="hidden"
            name="id"
            value="<?php echo e($row['id']); ?>"
        >

        <p>
            <label>OGC FID</label><br>
            <input
                type="number"
                name="ogc_fid"
                value="<?php echo e($row['ogc_fid']); ?>"
                min="1"
                step="1"
                required
            >
        </p>

        <p>
            <label>No</label><br>
            <input
                type="number"
                name="no"
                value="<?php echo e($row['no']); ?>"
                min="1"
                step="1"
                required
            >
        </p>

        <p>
            <label>Nama/Kode</label><br>
            <input
                type="text"
                name="nama_kode"
                value="<?php echo e($row['nama/kode']); ?>"
                maxlength="100"
                required
            >
        </p>

        <p>
            <label>Kemantren</label><br>
            <input
                type="text"
                name="kemantren"
                value="<?php echo e($row['kemantren']); ?>"
                maxlength="100"
                required
            >
        </p>

        <p>
            <label>Kelurahan</label><br>
            <input
                type="text"
                name="kelurahan"
                value="<?php echo e($row['kelurahan']); ?>"
                maxlength="100"
                required
            >
        </p>

        <p>
            <label>RW</label><br>
            <input
                type="number"
                name="rw"
                value="<?php echo e($row['rw']); ?>"
                min="0"
                step="1"
                required
            >
        </p>

        <p>
            <label>Nama Bank</label><br>
            <input
                type="text"
                name="nama_bank_"
                value="<?php echo e($row['nama_bank_']); ?>"
                maxlength="150"
                required
            >
        </p>

        <p>
            <label>Alamat</label><br>
            <input
                type="text"
                name="alamat"
                value="<?php echo e($row['alamat']); ?>"
                maxlength="255"
                required
            >
        </p>

        <p>
            <label>Jumlah</label><br>
            <input
                type="number"
                name="jumlah"
                value="<?php echo e($row['jumlah']); ?>"
                min="0"
                step="1"
            >
        </p>

        <p>
            <label>Latitude</label><br>
            <input
                type="number"
                name="latitude"
                value="<?php echo e($row['latitude']); ?>"
                step="any"
            >
        </p>

        <p>
            <label>Longitude</label><br>
            <input
                type="number"
                name="longitude"
                value="<?php echo e($row['longitude']); ?>"
                step="any"
            >
        </p>

        <br>

        <button type="submit">
            Simpan Perubahan
        </button>

        <a href="../index.php">
            Batal
        </a>

    </form>

</body>
</html>