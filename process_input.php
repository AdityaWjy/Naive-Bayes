<?php

// import
include './src/controller/functions.php';
include 'connectDataset.php';

// Get input data from
$nama_siswa = $_POST['nama_siswa'];
$nilai_A1 = $_POST['nilai_A1'];
$nilai_A2 = $_POST['nilai_A2'];
$nilai_A3 = $_POST['nilai_A3'];
$nilai_A4 = $_POST['nilai_A4'];
$prediksi_user = $_POST['prediksi'];

// Convert input value to the appropriate category
$input_konversi = [
    'A1' => konversiNilai($nilai_A1),
    'A2' => konversiNilai($nilai_A2),
    'A3' => konversiNilai($nilai_A3),
    'A4' => konversiNilai($nilai_A4)
];

// Get probabilitas diterima dan tidak diterima
list($banyak_Diterima, $banyak_Tidak) = hitungBanyakData($dataArray);
list($prob_diterima, $prob_tidak) = hitungProbabilitas($banyak_Diterima, $banyak_Tidak);

// Get frekuensi and probabilitas kelas
$frekuensi = hitungFrekuensi($dataArray);
$probabilitas_kelas = hitungProbabilitasKelas($frekuensi, $banyak_Diterima, $banyak_Tidak);

// Panggil fungsi hitungProbabilitasUser dengan input yang telah dikonversi
$hasil_probabilitas = hitungProbabilitasUser($input_konversi, $probabilitas_kelas, $prob_diterima, $prob_tidak, 1, 1);

// Get result of calculation
$probabilitas_diterima = $hasil_probabilitas['probabilitas_diterima'];
$probabilitas_tidak = $hasil_probabilitas['probabilitas_tidak'];

// Check if the prediction is correct
$hasil_naive_bayes = ($probabilitas_diterima > $probabilitas_tidak) ? "Diterima" : "Tidak Diterima";
$hasil_prediksi = ($hasil_naive_bayes === $prediksi_user) ? "BENAR" : "SALAH";

session_start();


// Simpan variabel yang diperlukan ke dalam session
$_SESSION['nama_siswa'] = $nama_siswa;
$_SESSION['prediksi_user'] = $prediksi_user;
$_SESSION['hasil_naive_bayes'] = $hasil_naive_bayes;
$_SESSION['hasil_prediksi'] = $hasil_prediksi;

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
    <nav class="navbar navbar-expand-lg bg-transparent px-5  mb-5">
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

    <!-- Content start -->
    <div class="container mt-5 pb-5 mb-5">
        <!-- Informasi siswa  -->
        <h4 class="text-white mb-3">Hasil Perhitungan Naive Bayes</h4>

        <div class="row">
            <div class="col-5">
                <div class="mb-5" ">
             <h5 class=" text-white mb-3">Informasi Siswa </h5>
                    <table class="table table-bordered table-striped rounded-3 overflow-hidden">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Nilai A1</th>
                                <th>Nilai A2</th>
                                <th>Nilai A3</th>
                                <th>Nilai A4</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= htmlspecialchars($nama_siswa) ?></td>
                                <td><?= htmlspecialchars($nilai_A1) ?></td>
                                <td><?= htmlspecialchars($nilai_A2) ?></td>
                                <td><?= htmlspecialchars($nilai_A3) ?></td>
                                <td><?= htmlspecialchars($nilai_A4) ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table table-bordered table-striped rounded-3 overflow-hidden">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Kategori A1</th>
                                <th>Kategori A2</th>
                                <th>Kategori A3</th>
                                <th>Kategori A4</th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= htmlspecialchars($nama_siswa) ?></td>
                                <td><?= $input_konversi['A1'] ?></td>
                                <td><?= $input_konversi['A2'] ?></td>
                                <td><?= $input_konversi['A3'] ?></td>
                                <td><?= $input_konversi['A4'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="col">
                <!-- hasil prediksi -->
                <div class="mb-5">
                    <h5 class="text-white mb-3">Hasil Prediksi</h5>
                    <table class="table table-bordered table-striped rounded-3 overflow-hidden">
                        <thead class="table-light">
                            <tr>
                                <th>Prediksi User</th>
                                <th>Probabilitas Diterima</th>
                                <th>Probabilitas Tidak Diterima</th>
                                <th>Hasil Naive Bayes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= htmlspecialchars($prediksi_user) ?></td>
                                <td><?= number_format($probabilitas_diterima, 5) ?></td>
                                <td><?= number_format($probabilitas_tidak, 5) ?></td>
                                <td><?= $hasil_naive_bayes ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- hasil naive bayes -->

        <div class="mb-5">
            <h5 class="text-white mb-3">Hasil Naive Bayes</h5>

            <div class="py-4 alert <?= $hasil_naive_bayes === 'Diterima' ? 'alert-success' : 'alert-danger' ?>">
                <strong>Hasil Naive Bayes:</strong> <?= $hasil_naive_bayes ?>
            </div>
        </div>

        <!-- evaluasi prediksi -->
        <div class="mb-5">
            <h5 class="text-white mb-3">Evaluasi Prediksi</h5>


            <?php if ($hasil_prediksi === "BENAR"): ?>
                <div class="alert alert-success">
                    <strong>Prediksi Anda Benar.</strong> Hasil Naive Bayes juga menunjukkan: <?= $hasil_naive_bayes ?>
                </div>
            <?php else: ?>
                <div class="alert alert-danger">
                    <strong>Prediksi Anda Salah.<br></strong> Hasil Naive Bayes menunjukkan: <strong> <?= $hasil_naive_bayes ?> </strong>
                </div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <a href="./dataUji.php" class="btn btn-primary w-100 py-3">Lihat Data Uji</a>

        </div>
    </div>
    <!-- content end -->

    <!-- java script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>