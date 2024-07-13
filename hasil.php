<?php
include 'Crud.php';
include 'navbar.php';

$crud = new Crud();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Periksa Gejala Tanaman Anggrek Menggunakan Certainty Factor</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.6/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>

<style>
        body {
            background-color: #fff;
            font-family: Arial, sans-serif;
            margin-top: 80px; /* Adjust margin-top based on your navbar height */
        }
        .container {
            max-width: 900px;
            margin: auto;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-back {
            margin-bottom: 20px;
        }
        .nav-tabs .nav-link {
            color: #007bff;
        }
        .nav-tabs .nav-link.active {
            color: #001;
            background-color: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
        }
        .tab-content {
            border: 1px solid #dee2e6;
            padding: 20px;
            border-top: none;
        }
        .tab-pane {
            margin-top: 20px;
        }
        .text-center {
            text-align: center;
        }
        .btn-pdf {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 mb-4 mt-4">
                <h1>Periksa Gejala Tanaman Anggrek Menggunakan Certainty Factor</h1>
                <a class="btn btn-primary btn-back" href="index.php">&lt;&lt; Kembali</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <nav class="nav nav-tabs" id="myTab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Proses Perhitungan</a>
                    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Hasil Perhitungan</a>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <div class="row">
                            <div class="col-md-12 text-center">
                            <?php
                                if (isset($_POST['button'])) {
                                    $groupKemungkinanPenyakit = $crud->getGroupPengetahuan(implode(",", $_POST['gejala']));
                                    $sql = $_POST['gejala'];
                                    $namaAnggrek = $_POST['nama_anggrek'];
                                    $test = $_POST['kondisi'];
                                    $wxgejala = implode($_POST['gejala']);
                                    $host = "localhost";
                                    $id = "root";
                                    $password = "";
                                    $db = "gerai_anggrek";

        // print_r($groupKemungkinanPenyakit);
                                if (isset($sql)) {
                                    for ($h = 0; $h < count($sql); $h++) {
                                        $kemungkinanPenyakit[] = $crud->getKemungkinanPenyakit($sql[$h]);

                                        for ($x = 0; $x < count($kemungkinanPenyakit[$h]); $x++) {
                                            for ($i = 0; $i < count($groupKemungkinanPenyakit); $i++) {
                                                $namaPenyakit = $groupKemungkinanPenyakit[$i]['nama_penyakit'];

                                                if ($kemungkinanPenyakit[$h][$x]['nama_penyakit'] == $namaPenyakit) {
                                                    $listIdKemungkinan[$namaPenyakit][] = $kemungkinanPenyakit[$h][$x]['id_pengetahuan'];
                                                }
                                            }
                                            $pengetahuanid[] = $kemungkinanPenyakit[$h][$x]['id_pengetahuan'];
                                        }
                                    }

                                    $id_penyakit_terbesar = '';
                                    $nama_penyakit_terbesar = '';
                                    $kombin = [];
                                    $cfkombin = 0;

                                    for ($h = 0; $h < count($groupKemungkinanPenyakit); $h++) {
                                        $namaPenyakit = $groupKemungkinanPenyakit[$h]['nama_penyakit'];
                                        $cfuser = [];
                                        echo "<br/>===========================================<br/>Proses Penyakit " . $h . "." . $namaPenyakit . "<br/>===========================================<br/>";
                                       

                                        for ($x = 0; $x < count($listIdKemungkinan[$namaPenyakit]); $x++) {
                                            $daftarKemungkinanPenyakit = $crud->getListPenyakit($listIdKemungkinan[$namaPenyakit][$x]);
                                            echo "<br/>proses " . $x . "<br/>-------------------------------------------<br/>";

                                            for ($i = 0; $i < count($daftarKemungkinanPenyakit); $i++) {
                                                $persen = 100;
                                                $mdbaru = (float)$daftarKemungkinanPenyakit[$i]['md'];

                                                // Hitung MB baru berdasarkan kondisi input pengguna
                                                $mbbaru = (isset($test[$h])) ? (float)$test[$h] : (float)$daftarKemungkinanPenyakit[$i]['mb'];

                                                if (count($listIdKemungkinan) == 0) {
                                                    echo "Jumlah Gejala = " . count($listIdKemungkinan[$namaPenyakit]) . "<br/>";
                                                    $mb = $mbbaru;
                                                    $md = $daftarKemungkinanPenyakit[$i]['md'];
                                                    $cf = $mb * $md;
                                                    $cf1 = $cf * $persen;
                                                    $daftar_cf[$namaPenyakit][] = $cf;
                                                    echo "<br/>proses 1<br/>-------------------------------------------<br/>";
                                                    echo "cfR = " . $mb . "<br/>";
                                                    echo "cfEvid = " . $md . "<br/>";
                                                    echo "cf = cfR * cfEvid = " . $mb . " * " . $md . " = " . $cf1 . "%<br/><br/><br/>";
                                                } else {
                                                    if ($x == 0) {
                                                        echo "Jumlah Gejala = " . count($listIdKemungkinan[$namaPenyakit]) . "<br/>";
                                                        $mb = $mbbaru;
                                                        $md = $daftarKemungkinanPenyakit[$i]['md'];
                                                        echo "<br/>cfR = " . $mb . "<br/>";
                                                        echo "cfEvid = " . $md . "<br/>";
                                                        $cf = $mb * $md;
                                                        $cf1 = $cf * $persen;
                                                        $cflama = $cf;
                                                        $cfkombin = $cflama;
                                                        echo "cf = cfR * cfEvid = " . $mb . " * " . $md . " = " . $cf . "<br/>";
                                                        echo "cf = cf * 100% = " . $cf . " * " . $persen . " = " . $cf1 . "%<br/><br/><br/>";
                                                        $daftar_cf[$namaPenyakit][] = $cf;
                                                        array_push($kombin, $cflama);
                                                    } else {
                                                        if ($mdbaru > 0 && $kombin > 0) {
                                                            $mdbaru = $daftarKemungkinanPenyakit[$i]['md'];
                                                            $cflama = 0;
                                                            for ($z = 0; $z < count($kombin); $z++) {
                                                                $cflama = $kombin[$z] + ($mdbaru * (1 - $kombin[$z]));
                                                            }
                                                            array_push($kombin, $cflama);
                                                            echo "cfbaru = " . $mdbaru . "<br/>";
                                                            echo "cflama = " . $kombin[$z - 1] . "<br/>";
                                                            echo "proses CF = cflama + (cfbaru * (1 - cflama)) = " . $kombin[$z - 1] . " + ($mdbaru * (1 - " . $kombin[$z - 1] . ")) = " . $cflama . "<br/>";
                                                            $cf = $cflama * $persen;
                                                            echo "cf = CFlama - 100% = " . $cflama . " * " . $persen . "% = " . $cf . "%<br/><br/><br/>";
                                                            $daftar_cf[$namaPenyakit][] = $cf;
                                                        } else if ($mdbaru < -0 && $kombin < -0) {
                                                            $mdbaru = $daftarKemungkinanPenyakit[$i]['md'];
                                                            $cflama = 0;
                                                            for ($z = 0; $z < count($kombin); $z++) {
                                                                $cflama = $kombin[$z] + ($mdbaru * (1 + $kombin[$z]));
                                                            }
                                                            array_push($kombin, $cflama);
                                                            echo "cfbaru = " . $mdbaru . "<br/>";
                                                            echo "cflama = " . $kombin[$z - 1] . "<br/>";
                                                            echo "proses CF = cflama + (cfbaru * (1 + cflama)) = " . $kombin[$z - 1] . " + ($mdbaru * (1 + " . $kombin[$z - 1] . ")) = " . $cflama . "<br/>";
                                                            $cf = $cflama * $persen;
                                                            echo "cf = CFlama - 100% = " . $cflama . " * " . $persen . "% = " . $cf . "%<br/><br/><br/>";
                                                            $daftar_cf[$namaPenyakit][] = $cf;
                                                        } else if ($mdbaru < -0 || $kombin < -0) {
                                                            for ($z = 0; $z < count($kombin); $z++) {
                                                                $cflama = ($kombin[$z] + $cfkombin) / (1 - min(abs($kombin[$z]), abs($cfkombin)));
                                                            }
                                                            array_push($kombin, $cflama);
                                                            echo "cfbaru = " . $cfkombin . "<br/>";
                                                            echo "cflama = " . $kombin[$z - 1] . "<br/>";
                                                            echo "proses CF = {CF1 + CF2} / (1-min{| CF1|,| CF2|})  = (" . $kombin[$z - 1] . "+" . $cfkombin . ")/(1-min{|" . $kombin[$z - 1] . "|,|" . $cfkombin . "|}) = " . $cflama . "<br/>";
                                                            $cf = $cflama * $persen;
                                                            echo "cf = CFlama - 100% = " . $cflama . " * " . $persen . "% = " . $cf . "%<br/><br/><br/>";
                                                            $daftar_cf[$namaPenyakit][] = $cf;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            ?>

                         </div>
                     
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <div class="row">
                            <div class="col-md-12 text-center">
            <?php
            $crud->hasilCFTertinggi($daftar_cf, $groupKemungkinanPenyakit);
            $pdfFilePath = $crud->hasilAkhir($daftar_cf, $groupKemungkinanPenyakit,$namaAnggrek);
            ?>
            <!-- Add button to download PDF -->
            <button id="print-pdf" class="btn btn-primary btn-pdf">Cetak PDF</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Script to handle PDF download
        document.getElementById('print-pdf').addEventListener('click', function () {
            // Redirect to the PDF file for download
            window.location.href = '<?php echo $pdfFilePath; ?>';
        });
    </script>
</body>
</html>