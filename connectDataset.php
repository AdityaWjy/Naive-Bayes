<?php
// Langkah 1: Tentukan path ke file JSON
$filePath = 'dataset.json'; // Misalnya file dataset.json berada di direktori yang sama dengan file PHP

// Langkah 2: Baca isi file JSON
$jsonData = file_get_contents($filePath);

// Langkah 3: Decode JSON menjadi array atau objek PHP
$dataArray = json_decode($jsonData, true); // true untuk mengonversi menjadi array, jika false menjadi objek
