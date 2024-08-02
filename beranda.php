<?php
include 'navbar.php';
include 'koneksi.php';

$koneksi = new Koneksi();

$data = $koneksi->getDataForChart();
$categories = $data['categories'];
$values = $data['values'];

$namaPenyakit = "Nama dari penyakit anggrek";
$deskripsiPenyakit = "Gejala adalah Indikasi awal penyakit anggrek";

$judulBerita = "Pengetahuan Anggrek: Panduan untuk Pemula";
$isiBerita = "Dalam dunia anggrek, pengetahuan dasar sangatlah penting untuk keberhasilan dalam bercocok tanam. Di sini kami membahas panduan dasar tentang perawatan anggrek untuk pemula.
Cek gejala dengan menginput indikasi gejala dari anggrek anda, kemudian submit agar gejala yang anda berikan di hitung oleh pengetahuan penyakit berdasarkan gejala dengan bobot yang kami hitung
. <br/><br/><br/>
Dibawah ini adalah data yang kami munculkan di dalam chart
";

$data2 = $koneksi->getChart2();
$labels2 = $data2['labels'];
$data2Values = $data2['data'];

$gejalaData = $koneksi->getGejalaDataForChart();
$gejalaCategories = $gejalaData['categories'];
$gejalaValues = $gejalaData['values'];

$bubbleChartData = $koneksi->getBubbleChartData();



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gejala Chart</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
            padding-top: 80px; 
        }
        .container {
            padding: 20px;
        }
        .card {
            background-color: #f0f0f0;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .card p {
            font-size: 18px;
            color: #333;
            margin: 0;
        }
        .card-full-width {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .card-full-width h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <h2>Selamat Datang!</h2>
                    <p>Ini adalah halaman beranda dari website Perhitungan Certainty Factor untuk mendeteksi Penyakit Anggrek. Silakan jelajahi informasi yang tersedia.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <h2>Informasi Penyakit Anggrek</h2>
                    <p><strong>Nama Penyakit:</strong> <?php echo $namaPenyakit; ?></p>
                    <p><strong>Gejala:</strong> <?php echo $deskripsiPenyakit; ?></p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card-full-width">
                    <h2><?php echo $judulBerita; ?></h2>
                    <p><?php echo $isiBerita; ?></p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <canvas id="myLineChart"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="myBubbleChart"></canvas>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <canvas id="gejalaChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var categories = <?php echo json_encode($categories); ?>;
            var values = <?php echo json_encode($values); ?>;
            var ctxLine = document.getElementById('myLineChart').getContext('2d');
            var myLineChart = new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: categories,
                    datasets: [{
                        label: 'Tabel Penyakit',
                        data: values,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        fill: true
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            var bubbleData = <?php echo json_encode($bubbleChartData); ?>;
            var bubbleDataset = bubbleData.map(item => ({
                x: item.mb,
                y: item.md,
                r: item.count
            }));

            var ctxBubble = document.getElementById('myBubbleChart').getContext('2d');
            var myBubbleChart = new Chart(ctxBubble, {
                type: 'bubble',
                data: {
                    datasets: [{
                        label: 'Tabel Pengetahuan',
                        data: bubbleDataset,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'MB'
                            },
                            beginAtZero: true
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'MD'
                            },
                            beginAtZero: true
                        }
                    }
                }
            });

            var gejalaCategories = <?php echo json_encode($gejalaCategories); ?>;
            var gejalaValues = <?php echo json_encode($gejalaValues); ?>;
            var ctxGejala = document.getElementById('gejalaChart').getContext('2d');
            var gejalaChart = new Chart(ctxGejala, {
                type: 'bar',
                data: {
                    labels: gejalaCategories,
                    datasets: [{
                        label: 'Tabel Gejala',
                        data: gejalaValues,
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
