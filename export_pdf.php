<?php
// export_pdf.php
$conn = mysqli_connect("localhost","root","","stock_barang");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Stok Barang - UMRAH</title>
    <style>
        /* CSS untuk Tampilan A4 dan Cetak */
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 0;
            padding: 20px;
        }

        /* Desain Kop Surat */
        .kop-surat {
            width: 100%;
            border-bottom: 3px double #000; /* Garis ganda di bawah kop */
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-surat td {
            vertical-align: middle;
        }
        .logo {
            width: 100px; /* Ukuran Logo */
        }
        .judul-kop {
            text-align: center;
        }
        .judul-kop h3, .judul-kop h2, .judul-kop p {
            margin: 0;
        }
        .judul-kop h3 { font-size: 14pt; font-weight: normal; }
        .judul-kop h2 { font-size: 16pt; font-weight: bold; }
        .judul-kop p { font-size: 10pt; font-style: italic; }

        /* Desain Tabel Data */
        .tabel-data {
            width: 100%;
            border-collapse: collapse;
            font-size: 11pt;
        }
        .tabel-data th, .tabel-data td {
            border: 1px solid #000;
            padding: 6px;
        }
        .tabel-data th {
            background-color: #f2f2f2;
            text-align: center;
        }
        
        /* Helper Text Colors */
        .text-danger { color: red !important; font-weight: bold; }
        .text-warning { color: orange !important; font-weight: bold; }
        .text-success { color: green !important; }
        
        /* Tanda Tangan (Opsional) */
        .tanda-tangan {
            margin-top: 50px;
            float: right;
            text-align: center;
            width: 300px;
        }

        /* Agar saat di-print background color tetap muncul */
        @media print {
            -webkit-print-color-adjust: exact;
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <table class="kop-surat">
        <tr>
            <td align="center" width="15%">
                <img src="images/logo_umrah.png" alt="Logo" class="logo" onerror="this.style.display='none'">
            </td>
            <td align="center" width="85%" class="judul-kop">
                <h3>KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</h3>
                <h2>UNIVERSITAS MARITIM RAJA ALI HAJI</h2>
                <h2>LABORATORIUM TEKNIK ELEKTRO</h2>
                <p>Jalan Politeknik Senggarang, Tanjungpinang, Kepulauan Riau</p>
                <p>Website: www.umrah.ac.id | Email: lab.elektro@umrah.ac.id</p>
            </td>
        </tr>
    </table>
    <center>
        <h3>LAPORAN STOK BARANG</h3>
        <p>Per Tanggal: <?php echo date("d F Y"); ?></p>
    </center>

    <table class="tabel-data">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Deskripsi</th>
                <th>Stok</th>
                <th>Status</th>
                <th>Tanggal Input</th>
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
                $tanggal = $data['tanggal'];

                // LOGIKA WARNA & STATUS (Sama persis dengan Excel)
                if($stock <= 20){
                    $status = "Segera Dibeli";
                    $class_status = "text-danger";
                } elseif($stock < 50){
                    $status = "Menipis";
                    $class_status = "text-warning";
                } else {
                    $status = "Aman";
                    $class_status = "text-success";
                }
            ?>
            <tr>
                <td style="text-align: center;"><?php echo $no++; ?></td>
                <td><?php echo $namabarang; ?></td>
                <td><?php echo $kategori; ?></td>
                <td><?php echo $deskripsi; ?></td>
                <td style="text-align: center;"><?php echo $stock; ?></td>
                
                <td class="<?=$class_status;?>" style="text-align: center;">
                    <?php echo $status; ?>
                </td>

                <td style="text-align: center;"><?php echo date('d-m-Y', strtotime($tanggal)); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="tanda-tangan">
        <p>Tanjungpinang, <?php echo date("d F Y"); ?></p>
        <p>Kepala Laboratorium,</p>
        <br><br><br>
        <p><strong>(Rusfa S.T., M.T)</strong></p>
        <p>NIDN.0010048606</p>
    </div>

</body>
</html>