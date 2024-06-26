<?php

// ini_set('display_errors', 1);
// error_reporting(E_ALL);

include("../../include/session.php");


$con = mysql_connect(MYSQL_SERVER, MYSQL_USER, MYSQL_USERPASS) or die('Could not connect: ' . mysql_error());

mysql_select_db(MYSQL_DATABASE, $con);


set_time_limit(-1);


function curPageURL() {

    $pageURL = 'http';

// if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}

    $pageURL .= "://";

    if ($_SERVER["SERVER_PORT"] != "80") {

        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];

    } else {

        $pageURL .= $_SERVER["SERVER_NAME"];

    }

    return $pageURL;

}



// Fungsi Konversi Tanggal - 20 Nov 2014 (Programmer: Hadi)

function convDate($date_raw){

    if($date_raw <> null){



        $date_ = explode('-', $date_raw);



        $date  = $date_[0];

        $month = $date_[1];

        $year  = $date_[2];



        switch ($month) {

            case '01': $month = 'Jan';

                break;

            case '02': $month = 'Feb';

                break;

            case '03': $month = 'Mar';

                break;

            case '04': $month = 'Apr';

                break;

            case '05': $month = 'May';

                break;

            case '06': $month = 'Jun';

                break;

            case '07': $month = 'Jul';

                break;

            case '08': $month = 'Aug';

                break;

            case '09': $month = 'Sep';

                break;

            case '10': $month = 'Okt';

                break;

            case '11': $month = 'Nov';

                break;

            case '12': $month = 'Des';

                break;

        }



        $date_new = $date.'-'.$month.'-'.$year;

    }else{

        $date_new = "";

    }



    return $date_new;

}


$template = $_GET['template'];

$theext=$_GET['md'];
//
//$thewhere=$_GET['whe'];
//
//$theorder=$_GET['ord'];
//
//$thep0=addslashes($_GET['p0']);
//
//$thep1=addslashes($_GET['p1']);
//
//$thep2=$_GET['p2'];
//
//$thep3=$_GET['p3'];
//
//$limitDataGET = array_key_exists('limit_data', $_GET) ? $_GET['limit_data'] : 0;
//
//$offsetDataGET = array_key_exists('offset', $_GET) ? $_GET['offset'] : 0;
//
//$totalDataExportGET = array_key_exists('total_data_export', $_GET) ? $_GET['total_data_export'] : 0;


$SubTitle1="";

$SubTitle2="";



$whereclause='';

$orderclause="";

//function queryMerkByNoPendaftaran()
//{
//    $query = "select";
//}

function queryStatement($offset=null, $limit = null) {
    $querystatement = "
        select subquery.NoPemohon, subquery.NamaPemohon, subquery.NamaAgen, subquery.Nama, 
               subquery.Merk, subquery.KelasID, subquery.Logo, subquery.NoPendaftaranLama,subquery.Keterangan, 
               subquery.NoAgenda, subquery.TanggalPermohonan, subquery.NoPendaftaran, subquery.TanggalPenerimaan, 
               subquery.TanggalJatuhTempo, subquery.id, subquery.Perpanjangan, subquery.MerkID, subquery.TanggalSisa 
        from 
            (select p.NoPemohon, np.NamaPemohon, nap.NamaAgen, kh.Nama, m.Merk, 
                    m.KelasID, m.Logo, pd.NoPendaftaranLama,
                    if(sp.StatusKondisi=1, concat(sp.KeteranganIn,' (Pendaftaran)'),
                    if(sp.StatusKondisi=2, concat(sp.KeteranganIn,' (Usul Tolak)'),
                    if(sp.StatusKondisi=3, concat(sp.KeteranganIn,' (Tanggapan)'),
                    if(sp.StatusKondisi=4, concat(sp.KeteranganIn,' (Ditolak)'),
                    if(sp.StatusKondisi=5, concat(sp.KeteranganIn,' (Banding)'),
                    if(sp.StatusKondisi=6, concat(sp.KeteranganIn,' (Sanggahan)'),
                    if(sp.StatusKondisi=7, concat(sp.KeteranganIn,' (Oposisi)'),
                    if(sp.StatusKondisi=8, concat(sp.KeteranganIn,' (Perpanjangan)'),
                    if(sp.StatusKondisi=9, concat(sp.KeteranganIn,' (Pengalihan Hak)'),
                    if(sp.StatusKondisi=10, concat(sp.KeteranganIn,' (Perubahan Nama)'),
                    if(sp.StatusKondisi=11, concat(sp.KeteranganIn,' (Perubahan Alamat)'),
                    if(sp.StatusKondisi=12, concat(sp.KeteranganIn,' (Perubahan Nama & Alamat)'),
                    if(sp.StatusKondisi=13, concat(sp.KeteranganIn,' (Merger)'),
                    if(ph.perpanjangan='Y', concat(sp.KeteranganIn,' (Perpanjangan)'),concat(sp.KeteranganIn,''))))))))))))))) as Keterangan,
                 pd.NoAgenda, date_format(pd.TanggalPermohonan,'%d-%m-%Y') as TanggalPermohonan,
                 pd.NoPendaftaran, date_format(pd.TanggalPenerimaan,'%d-%m-%Y') as TanggalPenerimaan,
                 date_format(pd.TanggalAkhirBerlakuMerk,'%d-%m-%Y') as TanggalJatuhTempo, 
                 ph.id, ph.Perpanjangan, pd.MerkID, pd.TanggalAkhirBerlakuMerk, np.id as np_id, 
                 datediff(pd.TanggalAkhirBerlakuMerk, NOW()) as TanggalSisa 
             from sit_merk m left join sit_kelas k on k.id=m.KelasID 
                 left join sit_permintaandetail pd on pd.MerkID=m.id 
                 left join sit_permintaanheader ph on ph.id=pd.PermintaanHeaderID 
                 left join sit_statusperkembangan sp on sp.MerkID=m.id 
                 left join sit_kuasahukum kh on kh.id=ph.KuasaHukumID 
                 left join sit_pemohon p on p.id=pd.PemohonID 
                 left join sit_namapemohon np on np.PemohonID=p.id 
                 left join sit_namaagenpemohon nap on nap.id=pd.NamaAgenPemohonID 
             WHERE pd.NoPendaftaran LIKE \"%". $_GET['no_pendaftaran'] ."%\") as subquery 
        group by subquery.np_id, subquery.MerkID 
        order by subquery.TanggalAkhirBerlakuMerk asc
    ";

    return $querystatement;
}



//$Title="Review Merk ($thewhere)"; UQ-17Sep2010

$Title="TRADEMARK REVIEW";



$FileXLPDF="BIP LN-PERPANJANGAN-XO AND DEVICE-" . $_GET['no_pendaftaran'] . '_'.date("Ymd");



if($theext=='PDF'||$theext=='VW'){

    $host = curPageURL();
    $dir = dirname($_SERVER['SCRIPT_NAME']);

    $queryres = $db->query(queryStatement());

    $arr_data = array();

    ob_start();
    ?>

    <style>

        table, td, th {

            /*border:1px solid #000;*/

        }

        table td{

            vertical-align:top;

            line-height:150%;

            padding:5px 3px 3px 5px;

        }

        th{
            text-align:center;
            /*background-color:#BFBFBF;*/
        }

        .kop-surat {
            width: 100%;
            margin-top: 0.8cm;
        }

        p {
            padding-top: -0.3cm;
            margin-top: -0.3cm;
        }

        .float-left {
            float: left;
        }

        .fs-container {
            font-size: 11px;
            font-weight: 700;
            margin-right: 2cm;
            margin-left: 2cm;
        }

    </style>

    <page style="font-size: 10px;" >
        <div style="padding-top: 50px;font-size: 10px;" class="fs-container">
        
            <div class="kop-surat">
                <div align="left" style="width: 65%; float: left">
                    <p style="word-spacing: 0.22cm;margin-top:32px;">E  :<a href="www.brainmatics.com">carolina.ini@kawanlamacorp.com</a></p>
                    <p>Hp.:+62 812-8888-9413</p>
                </div>
                <div align="left" style="width: 35%; float: right; margin-right: 22px; margin-top:-52px;">
                    <p>Jakarta, 07 November 2023</p>
                    <p style="margin-top: 20px;">Kepada Yth.:</p>
                    <p style="font-weight: bold;">PT KAWAN LAMA SEJAHTERA</p>
                    <p>Gedung Kawan Lama</p>
                    <p>jl.Puri Kencana No.1</p>
                    <p>Meruya-Kembangan</p>
                    <p style="text-decoration: underline;">Jakarta Barat 11610</p>
                    <p>U.p.:Ibu Carolina Thamrin</p>
                </div>
            </div>

            <div style="margin-top: 0.85cm;margin-left:50px;">
                <div align="left" style="width: 10%; float: left">
                    <p style="margin-right: 200px;">Perihal:</p>
                </div>
                <div style="font-size: 10px;">
                    <p style="margin-left: 30px; word-spacing:0.47cm;">Permohonan pendaftaran merek dagang/jasa:</p> 
                    <p style="margin-left: 30px;">Merek &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; :
                    </p>
              
                   <div style="font-size:11px;font-family: 'Times New Roman', Times, serif; margin-top:-85px; margin-left:218px;padding:-4px;"> <p><li>Cruizer</li><li>topgear</li></p>
                   </div>
                            
                            <p style="margin-top:0.1cm; word-spacing:-0.078px;margin-left: 30px;">Kelas &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
                            : &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  28 </p>
                            <p style="word-spacing: -0.067px;margin-left: 30px;">Nama Pemohon &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  PT.TOYS GAMES INDONESIA </p>
                            <p style="word-spacing: -0.1px;margin-left: 30px;">No.Ref.Kami &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  PWP/DOS/XI/23/00984 </p>
                            
                            <p style="word-spacing: -0.16px;margin-left: 17px;">------------------------------------------------------------------------------------------------</p>


                            
                            
                  
                </div>
            </div>

            <div style="margin-top: 10px;">
             
                <div align="left" style="width: 90%; float: right;">
                 
                    <?php

                    $index = 1;

                    while($row=$db->fetchArray($queryres)) {
                        $logo_path = "Logo/".$row["Logo"];
                        if(file_exists($logo_path)){
                            $logo='<a href="Logo/'.$row["Logo"].'" target="_blank" bitly="BITLY_PROCESSED";>';
                            $logo.='<img width="30" height="30" src="'.$logo_path.'">';
                            $logo.='</a>';
                        }else{
                            $logo='<a href="Logo/'.$row["Logo"].'" target="_blank" bitly="BITLY_PROCESSED";>';
                            $logo = '<img width="30" height="30" src="Logo/default.png">';
                            $logo.='</a>';
                        }
                        ?>
                        <?php
                        $index++;
                        $arr_data[] = $row;
                    }
                    ?>
                </div>
            </div>

            <div style="margin-top: 0.75cm;font-size:10.5px;font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;">
                <p>Dengan hormat,</p>
                <p style="margin-top: 0.75cm; letter-spacing: 0.01cm; word-spacing: 0.01cm; text-align: justify;">
                Sehubungan dengan perihal tersebut diatas, dengan ini kami beritahukan bahwa permohonan pendaftaran merek-
                merek tersebut dipokok surat ini telah kami ajukan pada tanggal 07 November 2023, dibawah agenda nomor-nomor
                sebagai berikut :
                </p>
            </div>

            <table style="margin-top:10px; margin-left:50px;">
                <thead>
                <tr style="font-size: 9px;">
                    <th align="left" style="width: 250px;font-size: 9px;">Merek-Merek Dagang/Jasa</th>
                    <th align="left" style="width: 150px;font-size: 9px;">Kelas</th>
                    <th align="left" style="width: 180px;font-size: 9px;">Agenda No.</th>
                </tr>
                </thead>
                <tbody>
                <?php
                for($i = 0; $i < count($arr_data); $i++) {
                    $row = $arr_data[$i];

                    $logo_path = "Logo/".$row["Logo"];
                    if(file_exists($logo_path)){
                        $logo='<a href="Logo/'.$row["Logo"].'" target="_blank" bitly="BITLY_PROCESSED";>';
                        $logo.='<img width="1.24cm" height="1.24cm" src="'.$logo_path.'">';
                        $logo.='</a>';
                    }else{
                        $logo='<a href="Logo/'.$row["Logo"].'" target="_blank" bitly="BITLY_PROCESSED";>';
                        $logo = '<img width="1.24cm" height="1.24cm" src="Logo/default.png">';
                        $logo.='</a>';
                    }

                    ?>
                    <tr>
                        <td align="left" alt="logomerek" style="font-size: 9px;"><li>CRUIZER<li>TOPGEAR</td>
                        <td align="left" style="font-size: 9px;"><ul>28<ul>28</td>
                        <td align="left" style="font-size: 9px;"><ul>DID2023103109</ul><ul>DID2023103111</ul></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>

            <div style="margin-top: 150px">
                <div style="margin-top: 10px">
               
                    <div class="float-left" style="margin-top: -2.75cm">
                        <div style="margin-bottom: 0.125cm">
                            <p>Setiap perkembangan akan kami laporkan kepada Pihak Bapak/Ibu.</p>
                        </div>
                        <p style="margin-bottom: 0.125cm">Terlampir kami sampaikan filing receipt beserta debit note permohonan pendaftaran merek tersebut.</p>
                        <p style="margin-top: 0.455cm">Atas perhatian dan kerja samanya kami ucapkan banyak terima kasih.</p>
                        <p style="margin-top: 1.5cm; text-align: center ;">Hormat kami,</p>
                        <p style="text-align: center ;">PULUNGAN, WISTON & PARTNERS</p>
                        <p style="margin-top: 1.5cm; text-align: center ;">(H. AMRIS PULUNGAN, SH.)</p>
                    </div>
                </div>
            </div>
        </div>

    </page>
    <pagebreak page-break-before="always" />
    <page style="font-size: 10px;" >
        <div style="padding-top: 50px;font-size: 10px;" class="fs-container">
        
            <div class="kop-surat">
             
                <div align="left" style="width: 35%; float: right; margin-right: 22px;">
                    <p>Jakarta, 07 November 2023</p>
                    <p style="margin-top: 20px;">Kepada Yth.:</p>
                    <p style="font-weight: bold;">PT. TOYS GAMES INDONESIA</p>
                    <p>Gedung Kawan Lama Lt. 6 jl. Puri Kencana</p>
                    <p>No . 1 Rt. 005 /002 Kembangan Selatan -</p>
                    <p>Kembangan Jakarta Barat, DKI jakarta</p>
                </div>
            </div>

            <div style="margin-top: 0.85cm;margin-left:89px;">
                <div align="left" style="width: 10%; float: left">
                    <p style="margin-right: 200px;">Perihal:</p>
                </div>
                <div style="font-size: 10px;">
                    <p style="margin-left: 30px; word-spacing:0.16cm;">Permohonan pendaftaran merek dagang/jasa:</p> 
                    <p style="margin-left: 30px;">Merek &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; :
                    </p>
              
                   <div style="font-size:11px;font-family: 'Times New Roman', Times, serif; margin-top:-85px; margin-left:218px;padding:-4px;"> <p><ul><li>Cruizer</li><li>topgear</li></ul></p>
                   </div>
                            
                            <p style="margin-top:0.1cm; word-spacing:-0.078px;margin-left: 30px;">Kelas &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
                            : &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  28 </p>
                            <p style="word-spacing: -0.067px;margin-left: 30px;">Nama Pemohon &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  PT.TOYS GAMES INDONESIA </p>
                            <p style="word-spacing: -0.1px;margin-left: 30px;">No.Ref.Kami &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  PWP/DOS/XI/23/00984 </p>
                            <p style="word-spacing: -0.16px;margin-left: 17px;">------------------------------------------------------------------------------------------------</p>
                </div>
            </div>

            <div style="margin-top: -10px; text-align:center;">
                <h4 style="font-weight: bold;text-decoration:underline; font-size: 10px">DEBIT-NOTE NO.PWP/DOS./XI/23/02496-02358</h4>
                <h1 style="font-weight:normal;font-size: 10px; text-decoration:underline;">NPWP:02.101.537.5-024.000</h1>
            </div>

            <div style="margin-top: 25px; font-size:10px;">
                <p>Biaya Permohonan Pendaftaran Merek Dagang/Jasa</p>


                <!-- <div align="left" style="width: 47%;">
                        <ul>
                            <li>
                            <p style="text-align: justify;">
                            Biaya Resmi untuk mengajukan permohonan 
                            pendaftaran Merek Pada Kementrian Hukum ^ HAM R.I:
                            2 X Rp. 1.800.000,-.........................................................
                            </p>
                            </li>
                            <li style="margin-top: 0.25cm;">
                            <p>
                            Honorarium pengacara untuk mengajukan permohonan
                            pendaftaran Merek Pada Kementrian Hukum & HAM R.I:
                            2 X Rp. 1.650.000,-.........................................................
                            </p>
                            </li>
                            </ul>
                            
                        </div> -->


                <div style="margin-left: -26px">
                    <div style="margin-top: 0.2cm">
                        <div align="left" style="width: 33.4%; float: right;">
                            <div align="left" style="width: 40%; float: left">
                                <p>Rp.</p>
                            </div>
                            <div align="left" style="width: 60%; float: right;">
                                <p style="text-align: right;margin-right:10.5px;">3.600.000,-</p>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: 0.1cm;margin-left: 1.16cm;">
                        <div align="left" style="width: 44%; float: left">
                        <li style=" list-style-type: upper-alpha">
                            <p style="font-size: 10px;text-align:justify; ">  
                            Biaya Resmi untuk mengajukan permohonan 
                            pendaftaran Merek Pada Kementrian Hukum & HAM R.I:
                            <br>2 X Rp. 1.800.000,-....................................
                            </p>
                            </li>
                        </div>
                    </div>
                </div>
                <div style="margin-left: -26px">
                    <div style="margin-top: 0.2cm">
                        <div align="left" style="width: 33.4%; float: right;">
                            <div align="left" style="width: 40%; float: left">
                                <p>Rp.</p>
                            </div>
                            <div align="left" style="width: 60%; float: right;">
                                <p style="text-align: right;margin-right:10.5px;">3.300.000,-</p>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: 0.1cm;margin-left: 1.16cm;">
                        <div align="left" style="width: 47%; float: left">
                        <li style=" list-style-type: upper-alpha">
                            <p style="font-size: 10px;text-align:left; ">  
                            Honorarium pengacara untuk mengajukan permohonan
                            pendaftaran Merek Pada Kementrian Hukum & HAM R.I:<br>
                            2 X Rp. 1.650.000,-..............................................
                            </p>
                            </li>
                        </div>
                    </div>
                </div>
                <div style="margin-left: -26px">
                    <div style="margin-top: 0.2cm">
                        <div align="left" style="width: 33.4%; float: right;">
                            <div align="left" style="width: 40%; float: left">
                                <p>Rp.</p>
                            </div>
                            <div align="left" style="width: 60%; float: right;">
                                <p style="text-align: right;margin-right:10.5px;">363.000,-</p>
                            </div>
                            
                        <hr style="margin: 0; display: inline; width: 100%">
                        </div>
                    </div>
                    <div style="margin-top: 0.1cm;margin-left: 0.73cm;">
                        <div align="left" style="width: 65%; float: left">
                            <p>- &nbsp; PPN 11%(b)…………………………………………………………</p>
                        </div>
                    </div>
                </div>
                <div style="margin-top: 0.2cm">
                    <p style="margin-left:180px;float: right; margin-top:0.068cm;">Sub Total :…………</p>
                    <div align="left" style="width: 35%; float: right;">
                        <div align="left" style="width: 40%; float: left">
                            <p>Rp.</p>
                        </div>
                        <div align="left" style="width: 60%; float: right;">
                            <p style="text-align:right; margin-right:10.5px;">7.263.000,-</p>
                        </div>
                        
                    </div>
                </div>
                <div style="margin-top: 0.35cm;margin-left: 0.04cm;">
                        <div align="left" style="width: 65%; float: left">
                            <p>- &nbsp; -/-PPh Pasal 23 (2% x Rp 3.300.000,-)*……………………….</p>
                        </div>
                        <div align="left" style="width: 35%; float: right;">
                            <div align="left" style="width: 40%; float: left">
                                <p>Rp.</p>
                            </div>
                            <div align="left" style="width: 60%; float: right;">
                                <p style="text-align: right;">66.000,(-)</p>
                            </div>
                            
                        <hr align="right" style="margin-top:-0.35cm; display: inline; width: 100%">
                        </div>
                </div>
                <div style="margin-top: 0.03cm">
                    <p style="margin-left:180px;float: right; margin-top:0.068cm; font-weight:bold;">TOTAL :……………</p>
                </div>
                    <div align="left" style="width: 35%; float: right;margin-top: -5px;">
                        <div align="left" style="width: 40%; float: left">
                            <p>Rp.</p>
                        </div>
                        <div align="left" style="width: 60%; float: right;">
                            <p style="font-size: 10px; font-weight:bold ;text-align: right; margin-right:10.5px">7.197.000,-</p>
                        </div>
                    </div>
                </div>
            </div>

            <div style="margin-top: 10px; font-size:10px;">
                <p style="margin-left: 65px; margin-bottom: 0.6cm">Dimohon agar uang tersebut dapat dipindah-bukukan ke rekening kami:</p>
                <div>
                    <div align="left" style="width: 35%; float: left;margin-top: 30px; margin-left:120px;">
                        <p>No. Rekening</p>
                        <p>Atas Nama</p>
                        <p>Nama Bank</p>
                    </div>
                    <div align="left" style="width: 40%; float: right; margin-bottom:-180px; margin-top: -241px;padding:210px;">
                        <p>: &nbsp;706-0302370</p>
                        <p>: &nbsp;PULUNGAN WISTON & PARTNERS</p>
                        <p>: &nbsp;BANK BCA Cabang Cempaka Putih, Jakarta Pusat</p>
                    </div>
                </div>
            </div>

            <div style="margin-top: 82px">
                <div style="margin-top: 10px">
                    <div class="float-left" style="margin-top: -2.75cm; font-size:10px">
                        <div style="margin-bottom: 0.125cm">
                            <p style="margin-left:119.6px;margin-bottom:20px;">
                             atau dibayar tunai di kantor kami.
                        </p>
                            <p style="width:83% ;margin-left:63.3px; word-spacing:0.21 cm">
                            Untuk keperluan accounting kami, dimohon supaya bukti transfer dapat dikirim melalui fax No. 021-4288-3426(27),
                            Terima kasih.
                        </p>
                        </div>
                        <p style="margin-top: 1.5cm; text-align: center ;">Hormat kami,</p>
                        <p style="text-align: center ;">PULUNGAN, WISTON & PARTNERS</p>
                        <p style="margin-top: 1.5cm; text-align: center ;">(H. AMRIS PULUNGAN, SH.)</p>
                    </div>
                </div>
            </div>
        </div>

    </page>

    <?php

    // mengganti package yang digunakan untuk mengexport data dengan pdf menjadi mpdf dari ang sebelumnya html2pdf | Yusuf | 10 Juli 2023
    $content=ob_get_clean();

//    require('fpdf/html2pdf/html2pdf.class.php');
    require_once 'mpdf/mpdf.php';

    try
    {
        $mPdf = new Mpdf('', 'A4', 9, 'Arial', 5, 5, 5, 5, 0, 0);

        $mPdf->autoPageBreak = true;
        $mPdf->autoMarginPadding = true;

        $mPdf->addPage('P');

    
        $mPdf->setAutoTopMargin = 'pad';
        $mPdf->setAutoBottomMargin = 'pad';

        $mPdf->WriteHTML($content);
        $mPdf->Output(''.$FileXLPDF.'.pdf','D');
    }

    catch(\Exception $e) {

        echo $e->getMessage();

        exit;

    }
}
else{

    die("Fatal Error !!!");

}

exit;

?>
