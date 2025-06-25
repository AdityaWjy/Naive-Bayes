<?php
include 'connectDataset.php';
include './src/controller/functions.php';

// Get banyak data diterima dan tidak diterima
list($banyak_Diterima, $banyak_Tidak) = hitungBanyakData($dataArray);

// Get probabilitas diterima dan tidak diterima
list($prob_diterima, $prob_tidak) = hitungProbabilitas($banyak_Diterima, $banyak_Tidak);

// Get frekuensi kategori per atribut dan hasil
$frekuensi = hitungFrekuensi($dataArray);

// Get probabilitas kategori per atribut dan hasil
$probabilitas = hitungProbabilitasKelas($frekuensi, $banyak_Diterima, $banyak_Tidak);

// Daftar kategori untuk setiap atribut
$kategori = ['SB', 'B', 'C', 'K', 'SK'];

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
                        <a class="nav-link active text-light" href="#jumlahData">Jumlah Data</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="#frekuensi">Frekuensi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="#probabilitas">Probabilitas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="#form">Form</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="./dataUji.php">Data uji</a>
                    </li>
                </ul>
                <button class="btn transparent-btn text-white border-2 border-white">Get started</button>
            </div>
        </div>
    </nav>
    <!-- Navbar end -->

    <div class="container pb-5" style="margin-top: 100px;">

        <!-- Data table -->
        <div class="row" id="jumlahData">
            <div class="col text-white">

                <h4>
                    Penerapan Algoritma Naive Bayes untuk Klasifikasi Prediksi Penerimaan Siswa Baru
                </h4>

                <h4 class="mt-4">Keterangan Data</h4>

                <div class="mt-3">

                    <p>Jumlah Banyak Diterima: <?php echo $banyak_Diterima; ?> Siswa</p>
                    <p>Jumlah Banyak Tidak Diterima: <?php echo $banyak_Tidak; ?> Siswa</p>

                </div>

                <div>
                    <p>Probabilitas Diterima: <?php echo $prob_diterima; ?></p>
                    <p>Probabilitas Tidak Diterima: <?php echo $prob_tidak; ?></p>
                </div>

                <div>
                    <button class="btn btn-primary px-5" data-bs-toggle="modal" data-bs-target="#dataLatihModal">Tambah Data Latih</button>
                </div>
            </div>


            <div class="col text-white">
                <div style="max-height: 400px; overflow-y: auto;">
                    <table class="table rounded-3 table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Siswa</th>
                                <th>A1</th>
                                <th>A2</th>
                                <th>A3</th>
                                <th>A4</th>
                                <th>Hasil</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Iterasi melalui array untuk menampilkan data siswa dalam tabel
                            foreach ($dataArray['dataset'] as $siswa) {
                                echo "<tr>";
                                echo "<td>" . $siswa['Nama Siswa'] . "</td>";
                                echo "<td>" . $siswa['A1'] . "</td>";
                                echo "<td>" . $siswa['A2'] . "</td>";
                                echo "<td>" . $siswa['A3'] . "</td>";
                                echo "<td>" . $siswa['A4'] . "</td>";
                                echo "<td>" . $siswa['Hasil'] . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <!-- modal -->
        <div class="modal fade" id="dataLatihModal" tabindex="-1" aria-labelledby="dataLatihModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="dataLatihModalLabel">Data Latih Naive Bayes</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <!-- Form Input Data Siswa -->
                        <form method="POST" action="./process_input.php">
                            <div class="mb-3">
                                <label for="nama_siswa" class="form-label">Nama Siswa:</label>
                                <input type="text" class="form-control rounded border-dark" id="nama_siswa" name="nama_siswa" required>
                            </div>
                            <div class="mb-3">
                                <label for="nilai_A1" class="form-label">Nilai A1:</label>
                                <input type="text" class="form-control border-dark" id="nilai_A1" name="nilai_A1" required>
                            </div>
                            <div class="mb-3">
                                <label for="nilai_A2" class="form-label">Nilai A2:</label>
                                <input type="text" class="form-control border-dark" id="nilai_A2" name="nilai_A2" required>
                            </div>
                            <div class="mb-3">
                                <label for="nilai_A3" class="form-label">Nilai A3:</label>
                                <input type="text" class="form-control border-dark" id="nilai_A3" name="nilai_A3" required>
                            </div>
                            <div class="mb-3">
                                <label for="nilai_A4" class="form-label">Nilai A4:</label>
                                <input type="text" class="form-control border-dark" id="nilai_A4" name="nilai_A4" required>
                            </div>
                            <div class="mb-3">
                                <label for="prediksi" class="form-label">Prediksi user: (Diterima / Tidak Diterima)</label>
                                <input type="text" class="form-control border-dark" id="prediksi" name="prediksi" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Submit</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <!-- Konversi -->
        <div class="mb-5" style="margin-top: 120px;">
            <h5 class="text-white mb-3">Konversi Nilai</h5>
            <table class="table table-bordered table-striped rounded-3 overflow-hidden">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nilai</th>
                        <th scope="col">Konversi</th>
                        <th scope="col">Konversi Huruf</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>80 - 100</td>
                        <td>Sangat Baik</td>
                        <td>SB</td>
                    </tr>
                    <tr>
                        <th scope="row">2</th>
                        <td>70 - 79</td>
                        <td>Baik</td>
                        <td>B</td>
                    </tr>
                    <tr>
                        <th scope="row">3</th>
                        <td>60 - 69</td>
                        <td>Cukup</td>
                        <td>C</td>
                    </tr>

                    <tr>
                        <th scope="row">4</th>
                        <td>55 - 59</td>
                        <td>Kurang</td>
                        <td>K</td>
                    </tr>
                    <tr>
                        <th scope="row">5</th>
                        <td>50 - 54</td>
                        <td>Sangat Kurang</td>
                        <td>SK</td>
                    </tr>
                </tbody>
            </table>
        </div>



        <!-- frekuensi  -->
        <div class="mb-5" id="frekuensi">

            <h5 class="text-white mb-3">Frekuensi Kategori per Atribut dan Hasil</h5>

            <table class="table table-bordered table-striped rounded-3 overflow-hidden">
                <thead class="table-light">
                    <tr>
                        <th>Kategori</th>
                        <th>A1 Diterima</th>
                        <th>A1 Tidak Diterima</th>
                        <th>A2 Diterima</th>
                        <th>A2 Tidak Diterima</th>
                        <th>A3 Diterima</th>
                        <th>A3 Tidak Diterima</th>
                        <th>A4 Diterima</th>
                        <th>A4 Tidak Diterima</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Menghitung frekuensi setiap kategori per atribut dan hasil
                    $frekuensi = hitungFrekuensi($dataArray);

                    // Daftar kategori untuk setiap atribut
                    $kategori = ['SB', 'B', 'C', 'K', 'SK'];

                    // Menampilkan frekuensi per kategori untuk setiap atribut
                    foreach ($kategori as $kategoriItem) {
                        echo "<tr>";
                        echo "<td>{$kategoriItem}</td>";

                        // Menampilkan frekuensi untuk A1, A2, A3, A4
                        for ($i = 1; $i <= 4; $i++) {
                            // Menampilkan frekuensi diterima dan tidak diterima untuk setiap atribut
                            echo "<td>" . $frekuensi["A{$i}_{$kategoriItem}_Diterima"] . "</td>";
                            echo "<td>" . $frekuensi["A{$i}_{$kategoriItem}_Tidak"] . "</td>";
                        }

                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- probabilitas -->
        <div class="mb-5" id="probabilitas">
            <h5 class="text-white mb-3">Probabilitas Kategori per Atribut dan Kelas</h5>

            <table class=" table table-bordered table-striped rounded-3 overflow-hidden">
                <thead class="table-light">
                    <tr>
                        <th>Kategori</th>
                        <th>A1 Diterima</th>
                        <th>A1 Tidak Diterima</th>
                        <th>A2 Diterima</th>
                        <th>A2 Tidak Diterima</th>
                        <th>A3 Diterima</th>
                        <th>A3 Tidak Diterima</th>
                        <th>A4 Diterima</th>
                        <th>A4 Tidak Diterima</th>
                    </tr>
                </thead>
                <tbody>
                    <?php


                    // Menampilkan probabilitas per kategori untuk setiap atribut
                    foreach ($kategori as $kategoriItem) {
                        echo "<tr>";
                        echo "<td>{$kategoriItem}</td>";

                        // Menampilkan probabilitas untuk A1, A2, A3, A4
                        for ($i = 1; $i <= 4; $i++) {
                            // Menampilkan probabilitas diterima dan tidak diterima untuk setiap atribut
                            echo "<td>" . number_format($probabilitas["prob_A{$i}_{$kategoriItem}_Diterima"], 3) . "</td>";
                            echo "<td>" . number_format($probabilitas["prob_A{$i}_{$kategoriItem}_Tidak"], 3) . "</td>";
                        }

                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php
        // Menampilkan hasil setelah form disubmit
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            include './process_input.php';
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>