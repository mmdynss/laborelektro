<?php
// --- 1. TANGKAP DATA DARI FORM ---
if (!isset($_POST['nama'])) {
    // Jika user akses langsung tanpa form, redirect ke halaman form
    header("Location: halaman_sertifikat.php");
    exit;
}

// Data Mahasiswa
$nama           = htmlspecialchars($_POST['nama']);
$nim            = htmlspecialchars($_POST['nim']);

// Data Sertifikat
$no_sertifikat  = htmlspecialchars($_POST['no_sertifikat']);
$matkul         = htmlspecialchars($_POST['matkul']);
$semester       = htmlspecialchars($_POST['semester']);
$tahun_akademik = htmlspecialchars($_POST['tahun_akademik']);
$no_sk_dekan    = htmlspecialchars($_POST['no_sk_dekan']);

// Data Pejabat (Ka. Lab)
$jabatan        = htmlspecialchars($_POST['jabatan']);
$ka_lab         = htmlspecialchars($_POST['ka_lab']);
$nip_ka_lab     = htmlspecialchars($_POST['nip_ka_lab']);

// Tanggal Otomatis (Hari Ini)
$bulan = array (
    1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
);
$tgl_cetak = date('d') . ' ' . $bulan[(int)date('m')] . ' ' . date('Y');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat - <?php echo $nama; ?></title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;800&family=Playfair+Display:ital,wght@0,700;1,900&family=Alex+Brush&family=Outfit:wght@300;400;600;800&display=swap');

        :root {
            --primary-blue: #0f172a;
            --brand-blue: #1e40af;
            --accent-blue: #3b82f6;
            --gold-metallic: linear-gradient(135deg, #bf953f, #fcf6ba, #b38728, #fbf5b7, #aa771c);
            --gold-solid: #c5a059;
            --paper-white: #ffffff;
            --text-main: #334155;
            --accent-green: #10b981;
            --accent-red: #ef4444;
        }

        body {
            background-color: #cbd5e1;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Outfit', sans-serif;
        }

        .cert-canvas {
            width: 297mm;
            height: 210mm;
            background-color: var(--paper-white);
            position: relative;
            overflow: hidden;
            box-shadow: 0 40px 70px rgba(0,0,0,0.25);
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
        }

        /* Ornamen Background */
        .bg-element {
            position: absolute;
            z-index: 1;
            pointer-events: none;
        }

        .shape-1 {
            top: -120px;
            right: -100px;
            width: 450px;
            height: 450px;
            background: var(--brand-blue);
            border-radius: 100px;
            transform: rotate(40deg);
            opacity: 0.08;
        }

        .shape-2 {
            bottom: -180px;
            left: -120px;
            width: 550px;
            height: 550px;
            background: var(--gold-solid);
            border-radius: 140px;
            transform: rotate(-20deg);
            opacity: 0.04;
        }

        /* Shape Hijau di Kiri Atas */
        .shape-3 {
            top: -150px;
            left: -100px;
            width: 350px;
            height: 350px;
            background: var(--accent-green);
            border-radius: 80px;
            transform: rotate(-15deg);
            opacity: 0.05;
        }

        /* Shape Merah di Kanan Bawah */
        .shape-4 {
            bottom: -100px;
            right: -50px;
            width: 300px;
            height: 300px;
            background: var(--accent-red);
            border-radius: 60px;
            transform: rotate(15deg);
            opacity: 0.05;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 380px;
            opacity: 0.1;
            z-index: 0;
        }

        /* Bingkai */
        .outer-frame {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border: 1px solid var(--gold-solid);
            z-index: 5;
        }

        .inner-frame {
            position: absolute;
            top: 8px;
            left: 8px;
            right: 8px;
            bottom: 8px;
            border: 3.5px solid var(--gold-solid);
            z-index: 5;
        }

        .corner-accent {
            position: absolute;
            width: 60px;
            height: 60px;
            border: 5px solid var(--primary-blue);
            z-index: 6;
        }

        /* Pengaturan Sudut */
        .top-l { top: 20px; left: 20px; border-right: 0; border-bottom: 0; }
        .top-r { top: 20px; right: 20px; border-left: 0; border-bottom: 0; }
        .bot-l { bottom: 20px; left: 20px; border-right: 0; border-top: 0; }
        .bot-r { bottom: 20px; right: 20px; border-left: 0; border-top: 0; }

        /* Content Wrapper */
        .content-wrapper {
            position: relative;
            z-index: 10;
            padding: 40px 80px;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-sizing: border-box;
            text-align: center;
        }

        /* Header */
        .univ-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 15px;
        }

        .univ-logo { 
            height: 85px; 
            width: auto;
            object-fit: contain;
        }

        .univ-info {
            text-align: left;
        }

        .univ-info h1 {
            font-size: 19pt;
            font-weight: 800;
            color: var(--primary-blue);
            margin: 0;
            text-transform: uppercase;
            line-height: 1.2;
            letter-spacing: 0.5px;
        }

        .univ-info p {
            margin: 4px 0 0;
            font-size: 10.5pt;
            color: var(--brand-blue);
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Title Area */
        .title-block {
            margin-bottom: 15px;
        }

        .main-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 40pt;
            font-weight: 800;
            color: var(--primary-blue);
            margin: 0;
            letter-spacing: 10px;
            line-height: 1;
            text-transform: uppercase;
        }

        .sub-title {
            font-family: 'Alex Brush', cursive;
            font-size: 30pt;
            background: var(--gold-metallic);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-top: -5px;
            display: block;
        }

        .cert-id {
            font-size: 8.5pt;
            color: #94a3b8;
            font-weight: 500;
            margin-top: 5px;
            letter-spacing: 2px;
        }

        /* Recipient Section */
        .recipient-block {
            margin: 20px 0;
            width: 100%;
        }

        .label-text {
            font-size: 10pt;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: #64748b;
            margin-bottom: 5px;
        }

        .recipient-name {
            font-family: 'Playfair Display', serif;
            font-size: 34pt;
            font-weight: 900;
            color: var(--primary-blue);
            margin: 5px 0;
            display: inline-block;
            padding: 0 30px;
            border-bottom: 1.5px solid rgba(197, 160, 89, 0.4);
        }

        .recipient-id {
            font-size: 11pt;
            color: var(--brand-blue);
            font-weight: 600;
            margin-top: 5px;
            letter-spacing: 2px;
        }

        /* Deskripsi */
        .cert-desc {
            font-size: 11pt;
            line-height: 1.8;
            max-width: 850px;
            color: var(--text-main);
            margin: 0 auto;
            flex-grow: 1;
            display: flex;
            align-items: center;
        }

        .highlight-bold {
            font-weight: 800;
            color: var(--primary-blue);
        }

        /* Signatures Footer */
        .cert-footer {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 10px;
            padding-bottom: 30px; 
        }

        /* Quote Section */
        .quote-block {
            text-align: left;
            max-width: 400px;
            border-left: 3px solid var(--gold-solid);
            padding-left: 15px;
            margin-bottom: 10px;
        }

        .quote-text {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-size: 11pt;
            color: var(--brand-blue);
            margin: 0;
            line-height: 1.4;
        }

        .quote-author {
            font-size: 8.5pt;
            font-weight: 700;
            color: var(--gold-solid);
            margin-top: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sig-item {
            text-align: center;
            width: 350px;
        }

        .sig-date { 
            font-size: 10.5pt; 
            color: var(--text-main); 
            margin-bottom: 8px; 
            text-align: center;
        }
        
        .sig-role { 
            font-weight: 800; 
            font-size: 10pt; 
            color: var(--primary-blue); 
            margin-bottom: 55px; 
            text-transform: uppercase; 
            letter-spacing: 1px;
            line-height: 1.3;
        }

        .sig-name { 
            font-weight: 800; 
            font-size: 12pt; 
            color: var(--primary-blue); 
            border-bottom: 2.2px solid var(--gold-solid); 
            display: inline-block; 
            padding: 0 8px; 
            margin-bottom: 4px;
            white-space: nowrap;
        }

        .sig-nip { 
            font-size: 9pt; 
            color: #475569; 
            margin: 0; 
            white-space: nowrap; 
        }

        /* Tombol Hint Cetak */
        .edit-hint { 
            position: fixed; top: 20px; left: 50%; transform: translateX(-50%); 
            background: #e74c3c; color: #fff; padding: 10px 20px; border-radius: 20px; 
            font-size: 14px; font-family: sans-serif; box-shadow: 0 2px 5px rgba(0,0,0,0.3); 
            z-index: 9999; cursor: pointer; font-weight: bold; 
        }

        @media print {
            .bg-element { display: none; }
            body { background: white; padding: 0; margin: 0; }
            .cert-canvas { box-shadow: none; border: none; }
            .edit-hint { display: none; }
            @page { size: A4 landscape; margin: 0; }
        }
    </style>
</head>
<body>

    <div class="edit-hint" onclick="window.print()">
        🖨️ Klik Disini Untuk Mencetak PDF
    </div>

    <div class="cert-canvas">
        <div class="bg-element shape-1"></div>
        <div class="bg-element shape-2"></div>
        <div class="bg-element shape-3"></div>
        <div class="bg-element shape-4"></div>
        
        <img src="https://upload.wikimedia.org/wikipedia/id/thumb/9/96/Lambang_Universitas_Maritim_Raja_Ali_Haji.png/250px-Lambang_Universitas_Maritim_Raja_Ali_Haji.png" class="watermark" alt="Watermark">

        <div class="outer-frame"></div>
        <div class="inner-frame"></div>
        
        <div class="corner-accent top-l"></div>
        <div class="corner-accent top-r"></div>
        <div class="corner-accent bot-l"></div>
        <div class="corner-accent bot-r"></div>

        <div class="content-wrapper">
            <div class="univ-header">
                <img src="https://upload.wikimedia.org/wikipedia/id/thumb/9/96/Lambang_Universitas_Maritim_Raja_Ali_Haji.png/250px-Lambang_Universitas_Maritim_Raja_Ali_Haji.png" alt="Logo UMRAH" class="univ-logo">
                <div class="univ-info">
                    <h1>Universitas Maritim Raja Ali Haji (UMRAH)</h1>
                    <p>FAKULTAS TEKNIK DAN TEKNOLOGI KEMARITIMAN • TEKNIK ELEKTRO</p>
                </div>
            </div>

            <div class="title-block">
                <h2 class="main-title">Sertifikat</h2>
                <span class="sub-title">Penghargaan Atas Dedikasi</span>
                <div class="cert-id">Nomor: <?php echo $no_sertifikat; ?></div>
            </div>

            <div class="recipient-block">
                <div class="label-text">Diberikan Sebagai Bentuk Apresiasi Kepada:</div>
                <h3 class="recipient-name"><?php echo $nama; ?></h3>
                <div class="recipient-id">NIM. <?php echo $nim; ?></div>
            </div>

            <div class="cert-desc">
                <p>
                    Piagam ini diberikan sebagai apresiasi atas kontribusi luar biasa, dedikasi, serta profesionalisme tinggi dalam melaksanakan tugas sebagai 
                    <span class="highlight-bold">Asisten Praktikum</span> pada mata kuliah 
                    <span class="highlight-bold"><?php echo $matkul; ?></span> di Laboratorium Teknik Elektro UMRAH Semester <?php echo $semester; ?> Tahun Akademik <?php echo $tahun_akademik; ?>, 
                    berdasarkan <span class="highlight-bold">SK Dekan FTTK Nomor: <?php echo $no_sk_dekan; ?></span>.
                </p>
            </div>

            <div class="cert-footer">
                <div class="quote-block">
                    <p class="quote-text">"Belajar dan Bertanya Tiada Jemu"</p>
                    <p class="quote-author">— Gurindam Dua Belas (Raja Ali Haji)</p>
                </div>

                <div class="sig-item">
                    <div class="sig-date">Tanjungpinang, <?php echo $tgl_cetak; ?></div>
                    
                    <div class="sig-role"><?php echo $jabatan; ?></div>
                    
                    <div class="sig-name"><?php echo $ka_lab; ?></div>
                    
                    <p class="sig-nip">NIP. <?php echo $nip_ka_lab; ?></p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>