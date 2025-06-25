<?php

// Fungsi untuk mendapatkan probabilitas berdasarkan atribut, kategori, dan kelas
function getProbabilitas($atribut, $kategori, $kelas)
{
    global $frekuensi; // Mengakses frekuensi yang telah dihitung sebelumnya

    // Membuat nama kunci untuk probabilitas berdasarkan atribut, kategori, dan kelas
    $key = "{$atribut}_{$kategori}_{$kelas}";

    // Mengembalikan probabilitas berdasarkan frekuensi
    return isset($frekuensi[$key]) ? $frekuensi[$key] : 0;
}


// Fungsi untuk menghitung banyak data diterima dan tidak diterima
function hitungBanyakData($dataArray)
{
    $banyak_Diterima = 0;
    $banyak_Tidak = 0;

    foreach ($dataArray['dataset'] as $siswa) {
        if ($siswa['Hasil'] == 'Diterima') {
            $banyak_Diterima++;
        } elseif ($siswa['Hasil'] == 'Tidak') {
            $banyak_Tidak++;
        }
    }

    return [$banyak_Diterima, $banyak_Tidak];
}

// Fungsi untuk menghitung probabilitas diterima dan tidak diterima
function hitungProbabilitas($banyak_Diterima, $banyak_Tidak)
{
    $totalData = $banyak_Diterima + $banyak_Tidak;
    $prob_diterima = $banyak_Diterima / $totalData;
    $prob_tidak = $banyak_Tidak / $totalData;

    return [$prob_diterima, $prob_tidak];
}

// Fungsi untuk menghitung frekuensi kategori per atribut dan hasil
function hitungFrekuensi($dataArray)
{
    $frekuensi = [];

    // Inisialisasi kategori untuk setiap atribut
    $kategori = ['SB', 'B', 'C', 'K', 'SK'];

    // Menghitung frekuensi untuk setiap atribut dan hasil (Diterima/Tidak)
    foreach (['A1', 'A2', 'A3', 'A4'] as $atribut) {
        foreach ($kategori as $kategoriItem) {
            // Inisialisasi frekuensi untuk Diterima dan Tidak
            $frekuensi["{$atribut}_{$kategoriItem}_Diterima"] = 0;
            $frekuensi["{$atribut}_{$kategoriItem}_Tidak"] = 0;

            foreach ($dataArray['dataset'] as $siswa) {
                if ($siswa['Hasil'] == 'Diterima' && $siswa[$atribut] == $kategoriItem) {
                    $frekuensi["{$atribut}_{$kategoriItem}_Diterima"]++;
                } elseif ($siswa['Hasil'] == 'Tidak' && $siswa[$atribut] == $kategoriItem) {
                    $frekuensi["{$atribut}_{$kategoriItem}_Tidak"]++;
                }
            }
        }
    }

    return $frekuensi;
}

function hitungProbabilitasKelas($frekuensi, $banyak_Diterima, $banyak_Tidak)
{
    $probabilitas = [];

    // Menghitung probabilitas untuk setiap kategori per atribut dan kelas
    $kategori = ['SB', 'B', 'C', 'K', 'SK'];
    foreach (['A1', 'A2', 'A3', 'A4'] as $atribut) {
        foreach ($kategori as $kategoriItem) {
            // Probabilitas untuk diterima
            $key_diterima = "{$atribut}_{$kategoriItem}_Diterima";
            $probabilitas["prob_{$atribut}_{$kategoriItem}_Diterima"] =
                $frekuensi[$key_diterima] / $banyak_Diterima;

            // Probabilitas untuk tidak diterima
            $key_tidak = "{$atribut}_{$kategoriItem}_Tidak";
            $probabilitas["prob_{$atribut}_{$kategoriItem}_Tidak"] =
                $frekuensi[$key_tidak] / $banyak_Tidak;
        }
    }

    return $probabilitas;
}


// Fungsi untuk menghitung probabilitas diterima dan tidak diterima berdasarkan input user
function hitungProbabilitasUser($input, $probabilitas, $prob_diterima, $prob_tidak, $probabilitas_diterima, $probabilitas_tidak)
{
    // Inisialisasi dengan prior probabilitas
    $probabilitas_diterima = $prob_diterima;
    $probabilitas_tidak = $prob_tidak;

    // Mengalikan probabilitas untuk setiap atribut (A1, A2, A3, A4) berdasarkan input pengguna
    foreach (['A1', 'A2', 'A3', 'A4'] as $atribut) {
        $kategori = $input[$atribut]; // Ambil kategori yang dipilih user

        // Periksa apakah kategori ada di probabilitas diterima
        if (isset($probabilitas["prob_{$atribut}_{$kategori}_Diterima"])) {
            $probabilitas_diterima *= $probabilitas["prob_{$atribut}_{$kategori}_Diterima"];
            echo "<script>console.log('User input category: {$kategori} matches probability for {$atribut} and Diterima: " . number_format($probabilitas["prob_{$atribut}_{$kategori}_Diterima"], 5) . "');</script>";
        } else {
            echo "<script>console.log('User input category: {$kategori} does NOT match any probability for {$atribut} in Diterima.');</script>";
        }

        // Periksa apakah kategori ada di probabilitas diterima
        if (isset($probabilitas["prob_{$atribut}_{$kategori}_Tidak"])) {
            $probabilitas_tidak *= $probabilitas["prob_{$atribut}_{$kategori}_Tidak"];
            echo "<script>console.log('User input category: {$kategori} matches probability for {$atribut} and Tidak: " . number_format($probabilitas["prob_{$atribut}_{$kategori}_Tidak"], 5) . "');</script>";
        } else {
            echo "<script>console.log('User input category: {$kategori} does NOT match any probability for {$atribut} in Tidak.');</script>";
        }
    }

    // Tampilkan hasil probabilitas diterima dan tidak diterima ke console
    echo "<script>console.log('Total Probabilitas Diterima: " . number_format($probabilitas_diterima, 5) . "');</script>";
    echo "<script>console.log('Total Probabilitas Tidak Diterima: " . number_format($probabilitas_tidak, 5) . "');</script>";

    // Mengembalikan probabilitas diterima dan tidak diterima
    return [
        'probabilitas_diterima' => $probabilitas_diterima,
        'probabilitas_tidak' => $probabilitas_tidak
    ];
}

function konversiNilai($nilai)
{
    if ($nilai >= 80 && $nilai <= 100) {
        return 'SB';
    } elseif ($nilai >= 70 && $nilai < 80) {
        return 'B';
    } elseif ($nilai >= 60 && $nilai < 70) {
        return 'C';
    } elseif ($nilai >= 55 && $nilai < 60) {
        return 'K';
    } elseif ($nilai >= 0 && $nilai < 55) {
        return 'SK';
    } else {
        return 'Nilai Tidak Valid'; // Jika nilai kurang dari 50 atau lebih
    }
}
