<?php

class Koneksi
{
    private $localhost = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "gerai_anggrek";

    protected $conn;

    public function __construct()
    {
        // Using the class properties inside the constructor
        $this->conn = mysqli_connect($this->localhost, $this->username, $this->password, $this->database);

        // Check connection
        if (!$this->conn) {
            die("Error connecting to the database: " . mysqli_connect_error());
        } else {
            // Connection successful (optional message)
            // echo "Connected successfully";
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }
    public function getDataForChart()
    {
        $query = "SELECT kode_penyakit, COUNT(*) as total FROM penyakit GROUP BY kode_penyakit";
        $result = mysqli_query($this->conn, $query);

        $categories = [];
        $values = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $categories[] = $row['kode_penyakit'];
                $values[] = $row['total'];
            }
        }

        return ['categories' => $categories, 'values' => $values];
    }
    public function getDataForSecondChart()
    {
        $query = "SELECT kode_penyakit, COUNT(*) as total FROM pengetahuan GROUP BY kode_penyakit";
        $result = mysqli_query($this->conn, $query);

        $labels = [];
        $data = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $labels[] = $row['kode_penyakit'];
                $data[] = $row['total'];
            }
        }

        return ['labels' => $labels, 'data' => $data];
    }
    public function getGejalaDataForChart() {

        $query = "SELECT nama_gejala, COUNT(*) AS count FROM gejala GROUP BY nama_gejala";
        $result = mysqli_query($this->conn,$query);
        
        $categories = [];
        $values = [];
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row['nama_gejala'];
                $values[] = $row['count'];
            }
        }
        
        return ['categories' => $categories, 'values' => $values];
    }

    //Buble chart buat pengetahuan

    public function getBubbleChartData() {
        $sql = "
            SELECT kode_penyakit, SUM(mb) AS total_mb, SUM(md) AS total_md, COUNT(*) AS count
            FROM pengetahuan
            GROUP BY kode_penyakit
        ";
        $result = mysqli_query($this->conn,$sql);
        $data = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = [
                    'kode_penyakit' => $row['kode_penyakit'],
                    'mb' => $row['total_mb'],
                    'md' => $row['total_md'],
                    'count' => $row['count']
                ];
            }
        }
        return $data;
    }

}

?>
