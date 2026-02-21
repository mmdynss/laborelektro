<?php
// export_excel.php

// 1. Koneksi Manual
$conn = mysqli_connect("localhost","root","","stock_barang");

// 2. Set Header agar didownload sebagai Excel
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Stok_UMRAH.xls");
?>

<h3>Data Stok Barang - Laboratorium UMRAH</h3>
<p>Tanggal Unduh: <?php echo date("d-m-Y"); ?></p>

<table border="1">
    <thead>
        <tr style="background-color: #f2f2f2; font-weight: bold;">
            <th>No</th>
            <th>Nama Barang</th>
            <th>Kategori</th> <th>Deskripsi</th>
            <th>Stok</th>
            <th>Status Stok</th>
            <th>Terakhir Di-input/Edit</th> 
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        $ambilsemuadatastock = mysqli_query($conn,"select * from stock");
        
        while($data = mysqli_fetch_array($ambilsemuadatastock)){
            $namabarang = $data['namabarang'];
            $deskripsi = $data['deskripsi'];
            $stock = $data['stock'];
            $kategori = $data['kategori'];
            
            // AMBIL TANGGAL LANGSUNG DARI TABEL STOCK (Lebih Akurat)
            $tanggal = $data['tanggal'];
            
            // 1. Logika Status Baru (20 ke bawah = Segera Dibeli)
            if($stock <= 20){
                $status = "Segera Dibeli";
                $warnastatus = "color: red; font-weight:bold;"; // Tambahan styling excel
            } elseif($stock < 50){
                $status = "Menipis";
                $warnastatus = "color: orange;";
            } else {
                $status = "Aman";
                $warnastatus = "color: green;";
            }

        ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $namabarang; ?></td>
            <td><?php echo $kategori; ?></td>
            <td><?php echo $deskripsi; ?></td>
            <td style="text-align: center;"><?php echo $stock; ?></td>
            
            <td style="<?=$warnastatus;?>"><?php echo $status; ?></td>

            <td><?php echo $tanggal; ?></td>
        </tr>
        <?php 
        } 
        ?>
    </tbody>
</table>