<?php include 'config.php'; ?>

<?php
require 'vendor/autoload.php';

if(isset($_POST['submit'])){
    $err ="";
    $ekstensi="";
    $success="";

    $file_name = $_FILES['filexls']['name'];
    $file_data = $_FILES['filexls']['tmp_name'];

    if(empty($file_name)){
        $err .="<li> silakan</li>";
    }else{
        $ekstensi = pathinfo($file_name)['extension'];

    }

    $ekstensi_allowed = array("xls", "xlsx");
    if(!in_array($ekstensi, $ekstensi_allowed)){
        $err .="<li>SILAHKAN BENER</li>";
    }

    if(empty($err)){
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file_data);
        $spreadsheet = $reader->load($file_data);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        $jumlahData = 0;
        for($i=1;$i<count ($sheetData);$i++){
            $nik = $sheetData[$i]['0'];
            $nama = $sheetData[$i]['1'];
            $jenis_barang = $sheetData[$i]['2'];
            $kecamatan = $sheetData[$i]['3'];
            $alamat = $sheetData[$i]['4'];
            $nohprt = $sheetData[$i]['5'];

            #echo "$nik - $nama - $jenis_barang - $kecamatan - $alamat - $nohprt <br/>";
            $sql1 = "insert into penerima(nik, nama, jenis_barang, kecamatan, alamat, nohprt) value('$nik', '$nama', '$jenis_barang', '$kecamatan', '$alamat', ' $nohprt')";

            mysqli_query($conn, $sql1);
            $jumlahData++;
        }

        if($jumlahData > 0){
            $success = "$jumlahData berhasil dimasukkan ke database";
        }
    }

    if($err){
        ?>
        <div class="alert alert-danger">
            <ul><?php echo $err ?></ul>
        </div>
        <?php    
    }

    if($success){
        ?>
        <div class="alert alert-primary">
            <ul><?php echo $success ?></ul>
        </div>
        <?php 
    } 
}