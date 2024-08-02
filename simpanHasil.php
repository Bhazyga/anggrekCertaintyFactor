

<?php

include 'koneksi.php';

class simpanHasil extends Koneksi
{
    public function __construct()
    {
        parent::__construct();
    }

    public function readGejala()
    {
        $sql = "SELECT * FROM gejala"; 
        $result = $this->conn->query($sql);

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }
public function simpanData($daftar_cf, $groupKemungkinanPenyakit)
{
    foreach ($groupKemungkinanPenyakit as $group) {
        $namaPenyakit = $group['nama_penyakit'];
        $nilaiCF = max($daftar_cf[$namaPenyakit]);

        $sql = "INSERT INTO hasilperhitungancf (nama_penyakit, nilai_cf) VALUES ('$namaPenyakit', $nilaiCF)";
        $result = $this->conn->query($sql);

        if (!$result) {
            echo "Error: " . $this->conn->error;
            return false;
        }
    }

    return true;
}
}