<?php
session_start();

// Ambil data dari session
$nama_siswa = $_SESSION['nama_siswa'] ?? 'N/A';
$prediksi_user = $_SESSION['prediksi_user'] ?? 'N/A';
$hasil_naive_bayes = $_SESSION['hasil_naive_bayes'] ?? 'N/A';
$hasil_prediksi = $_SESSION['hasil_prediksi'] ?? 'N/A';

// Data baru untuk disimpan
$entry_baru = [
    "nama_siswa" => $nama_siswa,
    "prediksi_user" => $prediksi_user,
    "hasil_naive_bayes" => $hasil_naive_bayes,
    "hasil_prediksi" => $hasil_prediksi
];

// Nama file JSON
$filename = 'datauji.json';

// Cek apakah file JSON sudah ada
if (file_exists($filename)) {
    // Baca data lama dari file JSON
    $data_lama = json_decode(file_get_contents($filename), true);
    if (!$data_lama || !isset($data_lama['datauji'])) {
        $data_lama['datauji'] = []; // Inisialisasi key jika tidak ada
    }
} else {
    $data_lama = ['datauji' => []]; // Jika file tidak ada, inisialisasi key
}

// Fungsi untuk memeriksa duplikasi data
function dataSudahAda($data_lama, $entry_baru)
{
    foreach ($data_lama['datauji'] as $data) {
        if ($data == $entry_baru) {
            return true; // Data sudah ada
        }
    }
    return false;
}

// Tambahkan entry baru jika belum ada
if (!dataSudahAda($data_lama, $entry_baru)) {
    $data_lama['datauji'][] = $entry_baru;

    // Simpan data baru ke file JSON
    file_put_contents($filename, json_encode($data_lama, JSON_PRETTY_PRINT));

    // Konfirmasi penyimpanan
    $alertMessage = "Data berhasil disimpan ke Data Uji.";
} else {
    echo "<script>console.log('Data sudah ada');</script>";
}

// Decode JSON
$jsonData = file_get_contents($filename);
$dataArray = json_decode($jsonData, true);

// Validasi JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    die("Error decoding JSON: " . json_last_error_msg());
}

$dataArray = $dataArray['datauji'] ?? []; // Pastikan key ada

function hitungBanyakData($dataArray)
{
    $banyak_benar = 0;
    $banyak_salah = 0;

    foreach ($dataArray as $data) {
        if ($data['hasil_prediksi'] == 'BENAR') {
            $banyak_benar++;
        } elseif ($data['hasil_prediksi'] == 'SALAH') {
            $banyak_salah++;
        }
    }
    return [$banyak_benar, $banyak_salah];
}

function hitungPersentase($banyak_benar, $banyak_salah)
{
    $total_data = $banyak_benar + $banyak_salah;
    return $total_data > 0 ? ($banyak_benar / $total_data * 100) : 0;
}

list($banyak_benar, $banyak_salah) = hitungBanyakData($dataArray);
$akurasiNaiveBayes = hitungPersentase($banyak_benar, $banyak_salah);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Penerimaan Siswa</title>
    <link rel="icon" href="./assets/informatika.svg" type="image/svg+xml">
    <link rel="stylesheet" href="./assets/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

    <!-- Navbar start -->
    <nav class="navbar navbar-expand-lg bg-transparent px-5  mb-3">
        <div class=" container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
                <a class="navbar-brand" href="./index.php">
                    <img src="./assets/informatika.svg" class="me-2" width="120" alt="Logo" loading="lazy" />
                </a>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active text-light" href="./index.php">Beranda</a>
                    </li>
                </ul>
                <button class="btn transparent-btn text-white border-2 border-white">Get started</button>
            </div>
        </div>
    </nav>
    <!-- Navbar end -->

    <div class="container  pb-5">

        <!-- Alert -->
        <?php if (isset($alertMessage)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($alertMessage) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Header -->
        <div class="text-left text-white mb-4">
            <h5 class="display-5">Data Uji Naive Bayes</h5>
            <p class="lead">Visualisasi hasil prediksi menggunakan metode Naive Bayes</p>
        </div>

        <!-- Statistik -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-header">Banyak Data BENAR</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $banyak_benar ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-danger">
                    <div class="card-header">Banyak Data SALAH</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $banyak_salah ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success">
                    <div class="card-header">Akurasi Naive Bayes</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= number_format($akurasiNaiveBayes, 1) ?>%</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Uji -->
        <div class="mt-5">
            <h5 class=" text-white mb-3">Hasil Data Uji</h5>
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Nama Siswa</th>
                        <th>Prediksi User</th>
                        <th>Hasil Naive Bayes</th>
                        <th>Output</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= htmlspecialchars($nama_siswa) ?></td>
                        <td><?= htmlspecialchars($prediksi_user) ?></td>
                        <td><?= htmlspecialchars($hasil_naive_bayes) ?></td>
                        <td><?= htmlspecialchars($hasil_prediksi) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Tabel data uji -->
        <div class="mt-5">
            <h5 class=" text-white mb-3">Tabel Data Uji</h5>

            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Prediksi User</th>
                        <th>Hasil Naive Bayes</th>
                        <th>Output</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($dataArray)): ?>
                        <?php $count = 1; ?>
                        <?php foreach ($dataArray as $data): ?>
                            <tr>
                                <td><?= $count++ ?></td>
                                <td><?= htmlspecialchars($data['nama_siswa']) ?></td>
                                <td><?= htmlspecialchars($data['prediksi_user']) ?></td>
                                <td><?= htmlspecialchars($data['hasil_naive_bayes']) ?></td>
                                <td><?= htmlspecialchars($data['hasil_prediksi']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="mt-2">
                <a href="./src/controller/export_excel.php" class="btn btn-success">Download Excel</a>
            </div>
        </div>


        <!-- java script -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>