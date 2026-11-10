<?php

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

// Mengambil seluruh data biopori
$sql = "SELECT * FROM biopori ORDER BY id ASC";
$result = $conn->query($sql);

// Menyimpan data dari database
$data_biopori = [];

if ($result && $result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        $data_biopori[] = $row;

    }

}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <!-- Metadata -->
    <meta charset="UTF-8">

    <!-- Judul pada tab browser -->
    <title>Data Biopori Yogyakarta</title>

    <!-- Leaflet CSS Library -->
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css"
    >

    <style>

        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        /* Tampilan peta */
        #map {
            width: 100%;
            height: 500px;
            margin-bottom: 30px;
        }

        table {
            border-collapse: collapse;
        }

        th,
        td {
            padding: 5px;
        }

    </style>

</head>

<body>

    <h2>Data Sebaran Lokasi Biopori Kota Yogyakarta</h2>

    <a href="input/index.html">Input Data Biopori</a>

    <br><br>

    <!-- Tempat peta ditampilkan -->
    <div id="map"></div>

    <!-- Leaflet JavaScript Library -->
    <script
        src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js">
    </script>

    <script>

        /* Initial Map */
        var map = L.map('map').setView(
            [-7.781000, 110.360000],
            14
        );


        /* Tile Basemap */

        /* Esri Imagery */
        var basemap1 = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
            {
                attribution: 'Tiles &copy; Esri'
            }
        );


        /* Google Street */
        var basemap2 = L.tileLayer(
            'https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',
            {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                attribution: 'Map data &copy; Google'
            }
        );


        /* Menampilkan basemap pertama */
        basemap1.addTo(map);


        /* Data Biopori dari database */
        var dataBiopori = <?php
            echo json_encode(
                $data_biopori,
                JSON_HEX_TAG |
                JSON_HEX_APOS |
                JSON_HEX_AMP |
                JSON_HEX_QUOT
            );
        ?>;


        /* Layer Marker */
        var markerBiopori = L.layerGroup();


        /* Membuat marker berdasarkan data database */
        dataBiopori.forEach(function(data) {

            /* Memeriksa koordinat */
            if (
                data.latitude !== null &&
                data.longitude !== null &&
                data.latitude !== '' &&
                data.longitude !== ''
            ) {

                var latitude = parseFloat(data.latitude);
                var longitude = parseFloat(data.longitude);


                /* Memeriksa koordinat valid */
                if (
                    !isNaN(latitude) &&
                    !isNaN(longitude)
                ) {

                    /* Membuat marker */
                    var marker = L.marker(
                        [latitude, longitude]
                    );


                    /* Popup marker */
                    marker.bindPopup(

                        "<b>Nama/Kode:</b> " +
                        data["nama/kode"] +

                        "<br><b>Kemantren:</b> " +
                        data.kemantren +

                        "<br><b>Kelurahan:</b> " +
                        data.kelurahan +

                        "<br><b>RW:</b> " +
                        data.rw +

                        "<br><b>Nama Bank:</b> " +
                        data.nama_bank_ +

                        "<br><b>Alamat:</b> " +
                        data.alamat +

                        "<br><b>Jumlah:</b> " +
                        (
                            data.jumlah === null
                            ? "-"
                            : data.jumlah
                        )

                    );


                    /* Menambahkan marker ke layer */
                    marker.addTo(markerBiopori);

                }

            }

        });


        /* Menampilkan seluruh marker */
        markerBiopori.addTo(map);


        /* Control Layer */
        var baseMaps = {

            "Esri Imagery": basemap1,

            "Google Street": basemap2

        };


        var overlayMaps = {

            "Lokasi Biopori": markerBiopori

        };


        L.control.layers(
            baseMaps,
            overlayMaps,
            {
                collapsed: false
            }
        ).addTo(map);


        /* Scale Bar */
        L.control.scale({

            maxWidth: 150,

            position: 'bottomright'

        }).addTo(map);

    </script>


    <h2>Tabel Data Biopori</h2>


    <?php

    if (count($data_biopori) > 0) {

        echo "<table border='1' cellpadding='5' cellspacing='0'>";


        echo "<tr>
                <th>ID</th>
                <th>OGC FID</th>
                <th>No</th>
                <th>Nama/Kode</th>
                <th>Kemantren</th>
                <th>Kelurahan</th>
                <th>RW</th>
                <th>Nama Bank</th>
                <th>Alamat</th>
                <th>Jumlah</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th colspan='2'>Aksi</th>
              </tr>";


        /* Menampilkan seluruh data */
        foreach ($data_biopori as $row) {

            echo "<tr>";

            echo "<td>" .
                htmlspecialchars((string)$row["id"]) .
                "</td>";

            echo "<td>" .
                htmlspecialchars((string)$row["ogc_fid"]) .
                "</td>";

            echo "<td>" .
                htmlspecialchars((string)$row["no"]) .
                "</td>";

            echo "<td>" .
                htmlspecialchars((string)$row["nama/kode"]) .
                "</td>";

            echo "<td>" .
                htmlspecialchars((string)$row["kemantren"]) .
                "</td>";

            echo "<td>" .
                htmlspecialchars((string)$row["kelurahan"]) .
                "</td>";

            echo "<td>" .
                htmlspecialchars((string)$row["rw"]) .
                "</td>";

            echo "<td>" .
                htmlspecialchars((string)$row["nama_bank_"]) .
                "</td>";

            echo "<td>" .
                htmlspecialchars((string)$row["alamat"]) .
                "</td>";

            echo "<td>" .
                htmlspecialchars((string)$row["jumlah"]) .
                "</td>";

            echo "<td>" .
                htmlspecialchars((string)$row["latitude"]) .
                "</td>";

            echo "<td>" .
                htmlspecialchars((string)$row["longitude"]) .
                "</td>";


            /* Tombol Hapus */
            echo "<td>

                    <a
                        href='delete.php?id=" .
                        (int)$row["id"] .
                        "'
                        onclick=\"return confirm('Apakah Anda yakin ingin menghapus data ini?')\"
                    >

                        Hapus

                    </a>

                  </td>";


            /* Tombol Edit */
            echo "<td>

                    <a
                        href='edit/index.php?id=" .
                        (int)$row["id"] .
                        "'
                    >

                        Edit

                    </a>

                  </td>";


            echo "</tr>";

        }


        echo "</table>";

    } else {

        echo "Belum ada data biopori.";

    }


    /* Menutup koneksi database */
    $conn->close();

    ?>

</body>

</html>