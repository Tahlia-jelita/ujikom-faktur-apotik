<?php
include 'koneksi.php';
// Ambil data profil apotek dari database
$query = mysqli_query($conn, "SELECT * FROM perusahaan LIMIT 1");
$data = mysqli_fetch_array($query);

// Pecah NIB dari field no_siup untuk kebutuhan visual dokumen
$nib = "9120202451951"; // Default NIB formal sesuai contoh
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Izin Usaha Perdagangan - <?=$data['nama_perusahaan']?></title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; color: #000; background: #fff; padding: 30px; line-height: 1.5; }
        .siup-box { width: 100%; max-width: 800px; margin: auto; border: 1px solid #ccc; padding: 50px; box-shadow: 0 0 10px rgba(0,0,0,0.1); position: relative; }
        
        /* Lambang Garuda Dummy */
        .garuda-header { text-align: center; margin-bottom: 25px; }
        .garuda-header .garuda-logo { font-size: 45px; font-weight: bold; color: #333; margin-bottom: 5px; line-height: 1; }
        .garuda-header h2 { font-size: 18px; font-weight: bold; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 5px; }
        .garuda-header h3 { font-size: 20px; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 5px; }
        .garuda-header p { font-size: 14px; font-style: italic; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        
        /* Konten Dokumen */
        .legal-text { font-size: 14px; text-align: justify; margin-bottom: 25px; text-indent: 40px; }
        
        /* Tabel Atribut */
        .info-table { width: 100%; margin-bottom: 30px; border-collapse: collapse; }
        .info-table td { padding: 6px 4px; vertical-align: top; font-size: 14px; border: none; }
        .info-table td.label { width: 30%; }
        .info-table td.colon { width: 2%; }
        
        /* Peraturan Tambahan */
        .notes-section { font-size: 13px; margin-top: 40px; border-top: 1px solid #000; padding-top: 15px; }
        .notes-section ol { margin-left: 20px; padding-left: 0; }
        .notes-section li { margin-bottom: 5px; text-align: justify; }
        
        /* Footer QR Code Sign */
        .footer-sign { margin-top: 40px; display: flex; justify-content: space-between; align-items: flex-end; }
        .qr-code { border: 2px solid #000; padding: 10px; font-size: 11px; text-align: center; font-family: monospace; width: 110px; height: 110px; display: flex; flex-direction: column; justify-content: center; align-items: center; background: #eee; }
        .sign-box { text-align: center; font-size: 14px; width: 250px; }
        
        /* Sembunyikan tombol print saat dicetak */
        .no-print { text-align: center; margin-bottom: 20px; }
        @media print { .no-print { display: none; } .siup-box { border: none; box-shadow: none; padding: 20px; } }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" style="padding: 10px 20px; background: #dfb76c; border: none; font-weight: bold; cursor: pointer; border-radius: 4px;">CETAK DOKUMEN SIUP</button>
    </div>

    <div class="siup-box">
        <div class="garuda-header">
            <div class="garuda-logo">🦅</div>
            <h2>Pemerintah Republik Indonesia</h2>
            <h3>Izin Usaha</h3>
            <small style="font-weight: bold; letter-spacing: 1px;">(Surat Izin Usaha Perdagangan)</small>
            <p>Pemerintah Republik Indonesia c.q. Lembaga Pengelola dan Penyelenggara OSS menerbitkan Izin Usaha berupa Surat Izin Usaha Perdagangan kepada entitas operasional berikut:</p>
        </div>

        <table class="info-table">
            <tr>
                <td class="label">Nama Perusahaan</td>
                <td class="colon">:</td>
                <td class="value"><strong><?=strtoupper($data['nama_perusahaan'])?></strong></td>
            </tr>
            <tr>
                <td class="label">Nomor Induk Berusaha (NIB)</td>
                <td class="colon">:</td>
                <td class="value"><strong><?=$nib?></strong></td>
            </tr>
            <tr>
                <td class="label">Alamat Perusahaan</td>
                <td class="colon">:</td>
                <td class="value"><?=$data['alamat']?></td>
            </tr>
            <tr>
                <td class="label">Kontak Perusahaan</td>
                <td class="colon">:</td>
                <td class="value">Telp: <?=$data['no_telp']?> | Fax: <?=$data['fax']?></td>
            </tr>
            <tr>
                <td class="label">Email Entitas</td>
                <td class="colon">:</td>
                <td class="value" style="color: blue; text-decoration: underline;"><?=$data['email']?></td>
            </tr>
            <tr>
                <td class="label">Kode KBLI / Klasifikasi</td>
                <td class="colon">:</td>
                <td class="value">47722 — Perdagangan Eceran Barang Farmasi Di Apotek</td>
            </tr>
            <tr>
                <td class="label">Izin Operasional Utama</td>
                <td class="colon">:</td>
                <td class="value">Pelayanan Kefarmasian & Distribusi Obat Kelas Premium (VVIP)</td>
            </tr>
        </table>

        <div class="legal-text" style="font-weight: bold; text-align: center; text-indent: 0; color: #aa0000;">
            Surat Izin Usaha Perdagangan ini dinyatakan TELAH BERLAKU EFEKTIF.
        </div>

        <div class="notes-section">
            <strong style="font-size: 13px;">Ketentuan Dokumen Perizinan Elektronik:</strong>
            <ol>
                <li>Dengan telah dimilikinya Izin Usaha berdasarkan komitmen (Berlaku Efektif) maka perusahaan dapat melakukan kegiatan sebagaimana diatur dalam regulasi perundang-undangan kesehatan.</li>
                <li>Pelaku usaha wajib mematuhi standar komitmen prasarana dan pemenuhan sarana teknis apotek sesuai waktu yang ditentukan oleh Dinas Kesehatan dan DPMPTSP Kabupaten/Kota setempat.</li>
                <li>Dokumen ini diterbitkan secara otomatis oleh sistem integrasi elektronik dan sah tanpa memerlukan tanda tangan basah penanggung jawab.</li>
            </ol>
        </div>

        <div class="footer-sign">
            <div class="qr-code">
                <span style="font-size: 24px; margin-bottom: 5px;">📱</span>
                <span style="font-size: 8px; font-weight: bold; line-height: 1;">ROYAL SECURE LOG<br>VERIFIED OSS</span>
            </div>
            
            <div class="sign-box">
                Dikeluarkan tanggal: <span style="font-weight: bold;"><?=date('d F Y')?></span><br>
                <strong>Lembaga Pengelola OSS</strong><br>
                <span style="font-size: 11px; color:#555;">Menteri Investasi/Kepala BKPM</span>
                <br><br><br><br>
                <small style="color: #666; font-style: italic;">Sistem Elektronik Terintegrasi (v2004-Active)</small>
            </div>
        </div>
    </div>

</body>
</html>