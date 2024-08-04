<?php
include 'koneksi.php';


require_once __DIR__ . '/vendor/autoload.php'; // Adjust the path based on your project structure


if (isset($_POST['cetakPengetahuan']) ) {
    $mpdf = new \Mpdf\Mpdf();

    $logoPath = 'gambar/anggrekbaru.png';
    $mpdf->SetHTMLHeader('<div style="text-align: left;"><img src="' . $logoPath . '" style="width: 100px; height: auto;"></div>');

    $mpdf->WriteHTML('<div> <h1 style="text-align: center;">Laporan Data Pengetahuan </h1></div>');
    $mpdf->WriteHTML('<div> <h4 style="text-align: center; font-size:13px;">TAMAN ANGGREK INDONESIA PERMAI,
    Jl. Pratama Raya Pintu 1 Tmii No.22 dan 16 Kavling 21, Pinang Ranti, Kec. Makasar,
    Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13560 </h4></div>');
   $mpdf->writeHTML('<div style="border-top: 1px solid black; margin-top: 15px; padding-top: 5px;">&nbsp;</div>');
 
    $koneksi = new Koneksi();
    $conn = $koneksi->getConnection();

    // Query data from the database
    $sql = "SELECT * FROM pengetahuan"; 
    $result = $conn->query($sql);

    if ($result) {
        $mpdf->WriteHTML('<table border="1" style="width: 100%; border-collapse: collapse;">');
        $mpdf->WriteHTML('<thead>
        <tr>
        <th>ID Pengetahuan</th>
        <th>ID Gejala</th>
        <th>Kode Penyakit</th>
        <th>MD</th>
        </tr></thead>');
        $mpdf->WriteHTML('<tbody>');

        while ($row = mysqli_fetch_assoc($result)) {
            $mpdf->WriteHTML('<tr>');
            $mpdf->WriteHTML('<td>' . $row['id_pengetahuan'] . '</td>'); // Adjust column names as per your table
            $mpdf->WriteHTML('<td>' . $row['id_gejala'] . '</td>'); // Adjust column names as per your table
            $mpdf->WriteHTML('<td>' . $row['kode_penyakit'] . '</td>'); // Adjust column names as per your table
            $mpdf->WriteHTML('<td>' . $row['md'] . '</td>'); // Adjust column names as per your table
            $mpdf->WriteHTML('</tr>');
        }

        $mpdf->WriteHTML('</tbody></table>');
    } else {
        $mpdf->WriteHTML('<p>No data available.</p>');
    }

    setlocale(LC_TIME, 'id_ID.UTF-8');
    $currentDate = strftime('%A %d-%m-%Y');


    $mpdf->SetHTMLFooter('<div style="text-align: right; margin-bottom:30px;">Jakarta, ' . $currentDate . '</div>
    <div style="text-align: right; margin-top: 5%;"> Yang bertanda tangan dibawah ini</div>
    <div style="text-align: right; margin-top: 20%;"> Jaka Purwanta</div>
    <div style="border-top: 1px solid black; margin-top: 2%; padding-top: 5px;">&nbsp;</div>
    ');


    // Output a PDF file directly to the browser
    $mpdf->Output('laporan.pdf', \Mpdf\Output\Destination::INLINE);
}


if (isset($_POST['cetakGejala']) ) {
    $mpdf = new \Mpdf\Mpdf();

    $logoPath = 'gambar/anggrekbaru.png';
    $mpdf->SetHTMLHeader('<div style="text-align: left;"><img src="' . $logoPath . '" style="width: 100px; height: auto;"></div>');

    $mpdf->WriteHTML('<div> <h1 style="text-align: center;">Laporan Data Gejala </h1></div>');
    $mpdf->WriteHTML('<div> <h4 style="text-align: center; font-size:13px;">TAMAN ANGGREK INDONESIA PERMAI,
     Jl. Pratama Raya Pintu 1 Tmii No.22 dan 16 Kavling 21, Pinang Ranti, Kec. Makasar,
     Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13560 </h4></div>');
    $mpdf->writeHTML('<div style="border-top: 1px solid black; margin-top: 15px; padding-top: 5px;">&nbsp;</div>');
 
    $koneksi = new Koneksi();
    $conn = $koneksi->getConnection();

    // Query data from the database
    $sql = "SELECT * FROM gejala"; 
    $result = $conn->query($sql);

    if ($result) {
        $mpdf->WriteHTML('<table border="1" style="width: 100%; border-collapse: collapse;">');
        $mpdf->WriteHTML('<thead>
        <tr>
        <th>ID Gejala</th>
        <th>Nama Gejala</th>
        </tr></thead>');
        $mpdf->WriteHTML('<tbody>');

        while ($row = mysqli_fetch_assoc($result)) {
            $mpdf->WriteHTML('<tr>');
            $mpdf->WriteHTML('<td>' . $row['id_gejala'] . '</td>'); // Adjust column names as per your table
            $mpdf->WriteHTML('<td>' . $row['nama_gejala'] . '</td>'); // Adjust column names as per your table
            $mpdf->WriteHTML('</tr>');
        }

        $mpdf->WriteHTML('</tbody></table>');
    } else {
        $mpdf->WriteHTML('<p>No data available.</p>');
    }

    setlocale(LC_TIME, 'id_ID.UTF-8');
    $currentDate = strftime('%A %d-%m-%Y');


    $mpdf->SetHTMLFooter('<div style="text-align: right; margin-bottom:30px;">Jakarta, ' . $currentDate . '</div>
    <div style="text-align: right; margin-top: 5%;"> Yang bertanda tangan dibawah ini</div>
    <div style="text-align: right; margin-top: 20%;"> Jaka Purwanta</div>
    <div style="border-top: 1px solid black; margin-top: 2%; padding-top: 5px;">&nbsp;</div>
    ');



    // Output a PDF file directly to the browser
    $mpdf->Output('laporan.pdf', \Mpdf\Output\Destination::INLINE);
}

if (isset($_POST['cetakPenyakit']) ) {
    $mpdf = new \Mpdf\Mpdf();

    $logoPath = 'gambar/anggrekbaru.png';
    $mpdf->SetHTMLHeader('<div style="text-align: left;"><img src="' . $logoPath . '" style="width: 100px; height: auto;"></div>');

    $mpdf->WriteHTML('<div> <h1 style="text-align: center;">Laporan Data Penyakit </h1></div>');
    $mpdf->WriteHTML('<div> <h4 style="text-align: center; font-size:13px;">TAMAN ANGGREK INDONESIA PERMAI,
     Jl. Pratama Raya Pintu 1 Tmii No.22 dan 16 Kavling 21, Pinang Ranti, Kec. Makasar,
     Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13560 </h4></div>');
    $mpdf->writeHTML('<div style="border-top: 1px solid black; margin-top: 15px; padding-top: 5px;">&nbsp;</div>');
 
    $koneksi = new Koneksi();
    $conn = $koneksi->getConnection();

    // Query data from the database
    $sql = "SELECT * FROM penyakit"; 
    $result = $conn->query($sql);

    if ($result) {
        $mpdf->WriteHTML('<table border="1" style="width: 100%; border-collapse: collapse;">');
        $mpdf->WriteHTML('<thead>
        <tr>
        <th>Kode Penyakit</th>
        <th>Nama Penyakit</th>
        </tr></thead>');
        $mpdf->WriteHTML('<tbody>');

        while ($row = mysqli_fetch_assoc($result)) {
            $mpdf->WriteHTML('<tr>');
            $mpdf->WriteHTML('<td>' . $row['kode_penyakit'] . '</td>'); // Adjust column names as per your table
            $mpdf->WriteHTML('<td>' . $row['nama_penyakit'] . '</td>'); // Adjust column names as per your table
            $mpdf->WriteHTML('</tr>');
        }

        $mpdf->WriteHTML('</tbody></table>');
    } else {
        $mpdf->WriteHTML('<p>No data available.</p>');
    }

    setlocale(LC_TIME, 'id_ID.UTF-8');
        $currentDate = strftime('%A %d-%m-%Y');

    
        $mpdf->SetHTMLFooter('<div style="text-align: right; margin-bottom:30px;">Jakarta, ' . $currentDate . '</div>
        <div style="text-align: right; margin-top: 5%;"> Yang bertanda tangan dibawah ini</div>
        <div style="text-align: right; margin-top: 20%;"> Jaka Purwanta</div>
        <div style="border-top: 1px solid black; margin-top: 2%; padding-top: 5px;">&nbsp;</div>
        ');



    // Output a PDF file directly to the browser
    $mpdf->Output('laporan.pdf', \Mpdf\Output\Destination::INLINE);
}
?>