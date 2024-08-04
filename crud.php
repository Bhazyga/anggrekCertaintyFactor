<?php

include 'koneksi.php';

class Crud extends Koneksi
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

 

    public function getGroupPengetahuan($value)
{
    // p, g , pyt merupakan inisialisasi dari tabel yang dituju
    $sql = "SELECT pyt.nama_penyakit, COUNT(p.id_gejala) AS jumlah_gejala
            FROM pengetahuan p
            JOIN gejala g ON p.id_gejala = g.id_gejala
            JOIN penyakit pyt ON p.kode_penyakit = pyt.kode_penyakit
            WHERE p.id_gejala IN ($value)
            GROUP BY pyt.kode_penyakit
            ORDER BY pyt.kode_penyakit";

    $result = $this->conn->query($sql);

    if ($result) {
        // merubah data tabel menjadi array
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    } else {
        return []; 
    }
}


    public function getKemungkinanPenyakit($sql)
    {
      $sql = "SELECT pyt.nama_penyakit, p.id_pengetahuan FROM pengetahuan p
        JOIN gejala g ON p.id_gejala = g.id_gejala
        JOIN penyakit pyt ON p.kode_penyakit = pyt.kode_penyakit
        WHERE g.id_gejala IN ($sql)";
      
      $result = $this->conn->query($sql);

      if (isset($result)) {
        $row = [];
        while ($row = $result->fetch_assoc()) {
          $rows[] = $row;
        }

        return $rows;
      }

    }
   
    
    public function getListPenyakit($value)
    {
      $sql = "SELECT * FROM pengetahuan p
        JOIN gejala g ON p.id_gejala = g.id_gejala
        JOIN penyakit pyt ON p.kode_penyakit = pyt.kode_penyakit
        WHERE p.id_pengetahuan IN ($value)";
      
      $result = $this->conn->query($sql);

      if (isset($result)) {
        $row = [];
        while ($row = $result->fetch_assoc()) {
          $rows[] = $row;
        }

        return $rows;
      }
    }

    public function hasilCFTertinggi($daftar_cf,$groupKemungkinanPenyakit)
    {
      for ($i=0; $i < count($groupKemungkinanPenyakit); $i++) { 
        $namaPenyakit = $groupKemungkinanPenyakit[$i]['nama_penyakit'];
        for ($x=0; $x < count($daftar_cf[$namaPenyakit]); $x++) {
          $merubahIndexCF = max($daftar_cf[$namaPenyakit]);
        }
        echo "<table align='center' width='600' class='table table-bordered table-striped table-hover'>
        <tr style='background-color:#f0f0f0;'>     
        <th>Nama Penyakit</th>
        <th>Nilai CF Tertinggi DI Kandidat Penyakit</th>      
        </tr>";        
          echo "<tr>";     
          echo "<td>" .$namaPenyakit. "</td>";  
          echo "<td>" .$merubahIndexCF. "%" ."</td>";        
          echo "</tr>";            
        echo "</table>";
      }
      
    }

    public function hasilAkhir($daftar_cf, $groupKemungkinanPenyakit, $namaAnggrek)
    {
        require_once __DIR__ . '/vendor/autoload.php';

        $mpdf = new \Mpdf\Mpdf();

        $logoPath = 'gambar/anggrekbaru.png';
        $mpdf->SetHTMLHeader('<div style="text-align: left;"><img src="' . $logoPath . '" style="width: 100px; height: auto;"></div>');

        $mpdf->WriteHTML('<div> <h1 style="text-align: center;">Hasil Perhitungan CF</h1></div>');
        $mpdf->WriteHTML('<div> <h4 style="text-align: center; font-size:13px;">TAMAN ANGGREK INDONESIA PERMAI,
        Jl. Pratama Raya Pintu 1 Tmii No.22 dan 16 Kavling 21, Pinang Ranti, Kec. Makasar,
        Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13560 </h4></div>');
       $mpdf->writeHTML('<div style="border-top: 1px solid black; margin-top: 15px; padding-top: 5px;">&nbsp;</div>');
     

        $merubahIndexCF = [];

        foreach ($groupKemungkinanPenyakit as $i => $penyakit) {
            $namaPenyakit = $penyakit['nama_penyakit'];

            if (!empty($daftar_cf[$namaPenyakit])) {
                $merubahIndexCF[$i] = max($daftar_cf[$namaPenyakit]);
            } else {
                $merubahIndexCF[$i] = 0;
            }
        }

        if (!empty($merubahIndexCF)) {
            $hasilMax = max($merubahIndexCF);
        } else {
            $hasilMax = 0;
        }

        foreach ($groupKemungkinanPenyakit as $i => $penyakit) {
            $namaPenyakit = $penyakit['nama_penyakit'];

            if ($merubahIndexCF[$i] === $hasilMax) {
             
                $html = '<table align="center" width="100%" style="border-collapse: collapse; margin-top: 20px;">
                    <tr style="background-color: green;">
                        <th style="border: 1px solid black; padding: 8px;">Nama Penyakit</th>
                        <th style="border: 1px solid black; padding: 8px;">Nilai CF</th>
                        <th style="border: 1px solid black; padding: 8px;">Nama Anggrek</th>
                    </tr>';

                $html .= '<tr>
                        <td style="border: 1px solid black; padding: 8px;">' . $namaPenyakit . '</td>
                        <td style="border: 1px solid black; padding: 8px;">' . $merubahIndexCF[$i] . '%</td>
                        <td style="border: 1px solid black; padding: 8px;">' . $namaAnggrek . '</td>
                    </tr>';

                $html .= '</table>';

                $mpdf->WriteHTML($html);

                $nilaiCF = $merubahIndexCF[$i];
                $this->simpanHasil($namaPenyakit, $nilaiCF, $namaAnggrek);
            }
        }

        setlocale(LC_TIME, 'id_ID.UTF-8');
        $currentDate = strftime('%A %d-%m-%Y');

        $mpdf->SetHTMLFooter('<div style="text-align: right; margin-bottom:30px;">Jakarta, ' . $currentDate . '</div>
        <div style="text-align: right; margin-top: 5%;"> Yang bertanda tangan dibawah ini</div>
        <div style="text-align: right; margin-top: 20%;"> Jaka Purwanta</div>
        <div style="border-top: 1px solid black; margin-top: 2%; padding-top: 5px;">&nbsp;</div>
        ');
    

        $pdfFilePath = 'hasil_perhitungan_cf.pdf';
        $mpdf->Output(__DIR__ . '/' . $pdfFilePath, \Mpdf\Output\Destination::FILE);

        return $pdfFilePath;
    }

    

            
    
    private function simpanHasil($namaPenyakit, $nilaiCF, $namaAnggrek) {
        $sql = "INSERT INTO hasilperhitungancf (nama_penyakit, nilai_cf, nama_anggrek) VALUES (?, ?, ?)";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("sds", $namaPenyakit, $nilaiCF, $namaAnggrek);
    
            if ($stmt->execute()) {
                echo "Hasil perhitungan berhasil disimpan.";
            } else {
                echo "Gagal menyimpan hasil perhitungan: " . $stmt->error;
            }
    
            $stmt->close();
        } else {
            echo "Gagal mempersiapkan statement: " . $this->conn->error;
        }
    }
    


    public function insertPengetahuan($id_gejala, $kode_penyakit, $mb, $md) {
        $stmt = $this->conn->prepare("INSERT INTO pengetahuan (id_gejala, kode_penyakit, mb, md) VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . $this->conn->error);
        }
        $stmt->bind_param("isdd", $id_gejala, $kode_penyakit, $mb, $md);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            return false;
        }
    }

    public function insertGejala($nama_gejala)
    {
        $stmt = $this->conn->prepare("INSERT INTO gejala (nama_gejala) VALUES (?)");
        if ($stmt === false) {
            die("Prepare failed: " . $this->conn->error);
        }
        $stmt->bind_param("s", $nama_gejala);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            return false;
        }
    }

    public function updateGejala($id_gejala, $nama_gejala)
    {
        $stmt = $this->conn->prepare("UPDATE gejala SET nama_gejala = ? WHERE id_gejala = ?");
        if ($stmt === false) {
            die("Prepare failed: " . $this->conn->error);
        }
        $stmt->bind_param("si", $nama_gejala, $id_gejala);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            return false;
        }
    }

    public function deleteGejala($id_gejala)
    {
        $stmt = $this->conn->prepare("DELETE FROM gejala WHERE `id_gejala` = ?");
        if ($stmt === false) {
            die("Prepare failed: " . $this->conn->error);
        }
        $stmt->bind_param("i", $id_gejala);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            return false;
        }
    }

    public function fetchAllGejala()
    {
        $sql = "SELECT * FROM gejala";
        $result = $this->conn->query($sql);

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }
    

    public function fetchAll($table) {
        $result = $this->conn->query("SELECT * FROM $table");
        if ($result === false) {
            die("Query failed: " . $this->conn->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function deleteById($table, $id) {
        $stmt = $this->conn->prepare("DELETE FROM $table WHERE id_pengetahuan = ?");
        if ($stmt === false) {
            die("Prepare failed: " . $this->conn->error);
        }
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            return false;
        }
    }

    public function updatePengetahuan($id, $id_gejala, $kode_penyakit, $mb, $md) {
        $stmt = $this->conn->prepare("UPDATE pengetahuan SET id_gejala = ?, kode_penyakit = ?, mb = ?, md = ? WHERE id_pengetahuan = ?");
        if ($stmt === false) {
            die("Prepare failed: " . $this->conn->error);
        }
        $stmt->bind_param("isddi", $id_gejala, $kode_penyakit, $mb, $md, $id);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            return false;
        }
    }

      public function insertPenyakit($kode_penyakit, $nama_penyakit) {
        $stmt = $this->conn->prepare("INSERT INTO penyakit (kode_penyakit, nama_penyakit) VALUES (?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . $this->conn->error);
        }
        $stmt->bind_param("ss", $kode_penyakit, $nama_penyakit);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            return false;
        }
    }

    public function fetchAllPenyakit() {
        $result = $this->conn->query("SELECT * FROM penyakit");
        if ($result === false) {
            die("Query failed: " . $this->conn->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function deletePenyakitById($id) {
        $stmt = $this->conn->prepare("DELETE FROM penyakit WHERE kode_penyakit = ?");
        if ($stmt === false) {
            die("Prepare failed: " . $this->conn->error);
        }
        $stmt->bind_param("s", $id);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            return false;
        }
    }

    public function fetchAllPengetahuan() {
        $sql = "SELECT p.*, py.nama_penyakit
                FROM pengetahuan p
                JOIN penyakit py ON p.kode_penyakit = py.kode_penyakit";
        $result = $this->conn->query($sql);

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }


    public function updatePenyakit($current_kode_penyakit, $new_kode_penyakit, $nama_penyakit) {
        $stmt = $this->conn->prepare("UPDATE penyakit SET kode_penyakit = ?, nama_penyakit = ? WHERE kode_penyakit = ?");
        if ($stmt === false) {
            die("Prepare failed: " . $this->conn->error);
        }
        $stmt->bind_param("sss", $new_kode_penyakit, $nama_penyakit, $current_kode_penyakit);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            return false;
        }
    }

    public function penyakitExists($kode_penyakit) {
        $count = 0;
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM penyakit WHERE kode_penyakit = ?");
        if ($stmt === false) {
            die("Prepare failed: " . $this->conn->error);
        }
        $stmt->bind_param("s", $kode_penyakit);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count > 0;
    }
    
  }
  


 ?>
