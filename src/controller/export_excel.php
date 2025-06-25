<?php
require_once '../../vendor/autoload.php';

use Shuchkin\SimpleXLSXGen;

// Aktifkan debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Hapus output buffer
ob_clean();
ob_start();

// Baca data JSON
$filename = '../../dataUji.json';
$jsonData = file_get_contents($filename);

if (!$jsonData) {
    die('File JSON tidak ditemukan atau tidak bisa dibaca.');
}

$dataArray = json_decode($jsonData, true)['datauji'] ?? [];
if (!is_array($dataArray)) {
    die('Data JSON tidak valid.');
}

// Siapkan data untuk file Excel
$excelData = [
    ['Nama Siswa', 'Prediksi User', 'Hasil Naive Bayes', 'Output'], // Header
];

foreach ($dataArray as $data) {
    $excelData[] = [
        $data['nama_siswa'] ?? '',
        $data['prediksi_user'] ?? '',
        $data['hasil_naive_bayes'] ?? '',
        $data['hasil_prediksi'] ?? '',
    ];
}

// Buat file Excel dan unduh
$xlsx = SimpleXLSXGen::fromArray($excelData);
$xlsx->downloadAs('datauji_export.xlsx');
